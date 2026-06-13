# Bookables

[![Latest Version on Packagist](https://img.shields.io/packagist/v/puntodev/bookables.svg?style=flat-square)](https://packagist.org/packages/puntodev/bookables)
[![Build Status](https://img.shields.io/github/actions/workflow/status/puntodev/bookables/php.yml?branch=master&style=flat-square)](https://github.com/puntodev/bookables/actions/workflows/php.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/puntodev/bookables.svg?style=flat-square)](https://packagist.org/packages/puntodev/bookables)
[![License](https://img.shields.io/packagist/l/puntodev/bookables.svg?style=flat-square)](LICENSE.md)

A small, **framework-agnostic** PHP library for computing **bookable availability and
time slots**. You describe when something is available — with a recurring weekly
schedule or a single date range — and Bookables turns that into concrete time ranges
and ready-to-book slots for any window of dates.

It's a pure domain library: no framework coupling, no database, no HTTP. Drop it into
any PHP application (Laravel, Symfony, plain PHP, …) that needs to answer *"when can
this resource be booked?"*. It builds on [`nesbot/carbon`](https://carbon.nesbot.com/)
for date/time math and [`league/period`](https://period.thephpleague.com/) for the
`Period` value object used to represent ranges and slots.

## Requirements

- PHP `>=8.4 <9.0`
- `ext-json`

## Installation

Install via [Composer](https://getcomposer.org/):

```bash
composer require puntodev/bookables
```

## Concepts

The library is organized around a simple pipeline:

```
WeeklySchedule  ──▶  Agenda  ──▶  TimeSlotter  ──▶  bookable slots
 (availability)     (concrete       (slices ranges     (Period[])
                     date ranges)    into slots)
```

| Piece | Responsibility |
| --- | --- |
| **`WeeklySchedule`** | A value object describing recurring weekly availability (per-day time ranges). JSON/array serializable, with validation. |
| **`Agenda`** (contract) | `possibleRanges(from, to)` → the concrete `Period` ranges available within a date window. |
| **`WeeklyScheduleAgenda`** | An `Agenda` backed by a `WeeklySchedule`. |
| **`SingleDateRangeAgenda`** | An `Agenda` for one fixed start/end range. |
| **`TimeSlotter`** (contract) | `makeSlotsForDates(start, end)` → the bookable slots (as `Period`s) within a window. |
| **`AgendaSlotter`** | Slices an `Agenda`'s ranges into fixed-duration slots, with optional gaps before/after. |
| **`DaySlotter`** | Produces sliding-window slots across the whole day (ignores any agenda). |
| **`HasAgenda`** / **`TimeBookable`** (contracts) | Interfaces you implement on your own entities (e.g. a professional, room, or resource). |

> All ranges and slots are returned as [`League\Period\Period`](https://period.thephpleague.com/5.0/period/)
> objects. Call `->toIso8601()` (or any `Period` method) to inspect them.

## Usage

### 1. Define a weekly schedule

A `WeeklySchedule` describes, for each day of the week, the time ranges during which
the resource is available. Build one from an array or from JSON — both validate the
input and throw an `Exception` if it's malformed.

```php
use Puntodev\Bookables\WeeklySchedule;

$schedule = WeeklySchedule::fromArray([
    'hours_in_advance' => 24,
    'disable_all' => false,
    'daily' => [
        'Mon' => [
            ['start' => '08:00', 'end' => '12:00'],
            ['start' => '14:00', 'end' => '18:00'],
        ],
        'Tue' => [['start' => '09:00', 'end' => '17:00']],
        'Wed' => [],
        // ...Thu, Fri, Sat, Sun
    ],
]);
```

The JSON form (handy for persisting a schedule in a database column):

```php
$schedule = WeeklySchedule::fromJson(
    '{"hours_in_advance": 24, "disable_all": false, "daily": {"Sun":[{"start":"14:00","end":"15:00"}]}}'
);

$schedule->toJson();   // serialize back to a JSON string
$schedule->toArray();  // or to an array
```

There's also a ready-made sample schedule (Mon–Fri 08:00–12:00 & 14:00–18:00, Sat
10:00–12:00):

```php
$schedule = WeeklySchedule::fromArray(WeeklySchedule::defaultWorkingHours());
```

#### Schedule JSON schema

| Key | Type | Description |
| --- | --- | --- |
| `daily` | object | Map of day-of-week (`Sun`, `Mon`, `Tue`, `Wed`, `Thu`, `Fri`, `Sat`) to a list of `{ "start": "HH:MM", "end": "HH:MM" }` ranges. Times must be a zero-padded time of day (`HH:MM` or `HH:MM:SS`, `00:00`–`23:59`); relative expressions like `now` are rejected. `start` must be before `end`. |
| `hours_in_advance` | int | Minimum booking notice, in hours. **Metadata only** — stored and exposed via `hoursInAdvance()`, but not enforced by the slotters (see notes below). |
| `disable_all` | bool | When `true`, the schedule yields no availability regardless of `daily`. Optional, defaults to `false`. |

### 2. Get available ranges from an agenda

An `Agenda` turns availability into the concrete date ranges that fall inside a
requested `[from, to]` window.

```php
use Carbon\CarbonImmutable;
use Puntodev\Bookables\Agenda\WeeklyScheduleAgenda;

$agenda = new WeeklyScheduleAgenda($schedule);

$ranges = $agenda->possibleRanges(
    CarbonImmutable::parse('2020-01-20'),
    CarbonImmutable::parse('2020-01-21'),
);

foreach ($ranges as $range) {
    echo $range->toIso8601(), PHP_EOL;
    // 2020-01-20T08:00:00.000000Z/2020-01-20T12:00:00.000000Z
    // 2020-01-20T14:00:00.000000Z/2020-01-20T18:00:00.000000Z
    // ...
}
```

For one-off availability that isn't weekly (e.g. a single open window), use
`SingleDateRangeAgenda`. It returns the intersection of its fixed range with the
requested window (or no range at all if they don't overlap):

```php
use Puntodev\Bookables\Agenda\SingleDateRangeAgenda;

$agenda = new SingleDateRangeAgenda(
    CarbonImmutable::parse('2020-01-20 09:00'),
    CarbonImmutable::parse('2020-01-20 17:00'),
);
```

### 3. Turn ranges into bookable slots

A `TimeSlotter` slices ranges into the actual slots a user can book.

**`AgendaSlotter`** produces fixed-duration slots inside each of an agenda's ranges:

```php
use Puntodev\Bookables\Slots\AgendaSlotter;

// 30-minute slots, back to back
$slotter = new AgendaSlotter($agenda, duration: 30);

$slots = $slotter->makeSlotsForDates(
    CarbonImmutable::parse('2020-01-23'),
    CarbonImmutable::parse('2020-01-23'),
);

// 2020-01-23T08:00:00.000000Z/2020-01-23T08:30:00.000000Z
// 2020-01-23T08:30:00.000000Z/2020-01-23T09:00:00.000000Z
// ...
```

You can reserve a gap before and/or after each appointment (in minutes). The stride
between slot starts becomes `duration + max(timeAfter, timeBefore)`:

```php
// 50-minute appointments with a 10-minute gap after each → slots every 60 minutes
$slotter = new AgendaSlotter($agenda, duration: 50, timeAfter: 10);

// 2020-01-23T08:00:00.000000Z/2020-01-23T08:50:00.000000Z
// 2020-01-23T09:00:00.000000Z/2020-01-23T09:50:00.000000Z
// ...
```

**`DaySlotter`** ignores agendas entirely and lays a sliding window of slots across
the full 24 hours of each day — useful when availability is "any time" and you only
care about duration and stepping. When `step` is smaller than `duration`, slots
overlap.

```php
use Puntodev\Bookables\Slots\DaySlotter;

// 30-minute slots starting every 15 minutes
$slotter = new DaySlotter(duration: 30, step: 15);

$slots = $slotter->makeSlotsForDates(
    CarbonImmutable::parse('2020-01-23'),
    CarbonImmutable::parse('2020-01-23'),
);

// 2020-01-23T00:00:00.000000Z/2020-01-23T00:30:00.000000Z
// 2020-01-23T00:15:00.000000Z/2020-01-23T00:45:00.000000Z
// 2020-01-23T00:30:00.000000Z/2020-01-23T01:00:00.000000Z
// ...
```

### Timezones

Agendas compute availability in the timezone of the `Carbon` instances you pass in.
`WeeklyScheduleAgenda` interprets the schedule's `HH:MM` times in that timezone. Note
that `Period::toIso8601()` renders in UTC (`Z`), so the same wall-clock schedule in
different timezones produces different UTC output:

```php
$tz = 'Pacific/Auckland';
$ranges = $agenda->possibleRanges(
    CarbonImmutable::parse('2020-01-20', $tz),
    CarbonImmutable::parse('2020-01-20', $tz),
);
// 08:00–12:00 Auckland time → 2020-01-19T19:00:00Z / 2020-01-19T23:00:00Z
```

### Modeling your own bookable entities

The `HasAgenda` and `TimeBookable` contracts are there for your application to
implement on its own models — for example, a professional or room that exposes an
agenda:

```php
use Puntodev\Bookables\Contracts\Agenda;
use Puntodev\Bookables\Contracts\HasAgenda;

class Professional implements HasAgenda
{
    public function agenda(): Agenda
    {
        return new WeeklyScheduleAgenda($this->weeklySchedule());
    }
}
```

## Notes & caveats

- **`hours_in_advance` is not enforced** by the slotters. It's carried as metadata
  (available via `hoursInAdvance()`); filtering out slots that are too soon is the
  consuming application's responsibility.
- **`disable_all` is enforced** — a `WeeklyScheduleAgenda` over a disabled schedule
  yields no ranges.
- Ranges and slots are immutable `League\Period\Period` objects; all internal date
  math uses Carbon's immutable variants.
- **Requested date ranges are capped.** `WeeklyScheduleAgenda`, `AgendaSlotter` and
  `DaySlotter` generate one entry per day (and per slot) in the `[from, to]` window,
  so an unbounded range would exhaust memory. Each takes an optional `maxDays`
  argument (default `366`) and throws
  `Puntodev\Bookables\Exceptions\DateRangeTooLargeException` when the window is
  larger. Pass `0` (or less) to disable the limit if you have your own bound:

  ```php
  use Puntodev\Bookables\Exceptions\DateRangeTooLargeException;

  $agenda  = new WeeklyScheduleAgenda($schedule, maxDays: 92);
  $slotter = new AgendaSlotter($agenda, duration: 30, maxDays: 92);
  $slotter = new DaySlotter(duration: 30, step: 15, maxDays: 92);

  try {
      $slots = $slotter->makeSlotsForDates($from, $to);
  } catch (DateRangeTooLargeException $e) {
      // reject the request (e.g. HTTP 422)
  }
  ```
- **Slot durations must be positive.** `AgendaSlotter` (`duration`) and `DaySlotter`
  (`duration`, `step`) reject non-positive values with `InvalidArgumentException`;
  `timeAfter`/`timeBefore` must not be negative.

## Testing

```bash
composer test
```

Generate an HTML coverage report:

```bash
composer test-coverage
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details. In short: keep the library
framework-agnostic, write everything in English, and include tests with every change.

## Security

If you discover any security-related issues, please email
mariano.goldman@puntodev.com.ar instead of using the issue tracker.

## Credits

- [Mariano Goldman](https://github.com/puntodev)

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.
