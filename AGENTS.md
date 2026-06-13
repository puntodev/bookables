# AGENTS.md

Guidance for AI coding agents (and humans) working in this repository.

## What this project is

`puntodev/bookables` is a small, **framework-agnostic** PHP library for computing
**bookable availability and time slots**. Given a description of when something is
available (a weekly schedule or a single date range), it produces concrete date
ranges and bookable slots within a requested window.

It is a pure domain library: no Laravel/Symfony coupling, no database, no HTTP. It
is meant to be embedded inside a larger booking/scheduling application.

## Tech stack

- **PHP** `>=8.4 <9.0`, `ext-json`.
- **Runtime dependencies**
  - [`nesbot/carbon`](https://carbon.nesbot.com/) `^3.11` — date/time handling.
  - [`league/period`](https://period.thephpleague.com/) `^5.3` — the `Period`
    value object used to represent ranges and slots.
- **Dev dependencies**: `phpunit/phpunit` `^13.2`, `mockery/mockery` `^1.6`.
- **Autoloading** (PSR-4):
  - `Puntodev\Bookables\` → `src/`
  - `Tests\` → `tests/`

## Repository layout

```
src/
  WeeklySchedule.php            Value object: recurring weekly availability (JSON/array serializable)
  Contracts/
    Agenda.php                  possibleRanges(from, to): Period[]
    HasAgenda.php               agenda(): Agenda   (marker for bookable entities)
    TimeSlotter.php             makeSlotsForDates(start, end): Period[]
    TimeBookable.php            available()/unavailable()  (for consumers to implement)
  Agenda/
    WeeklyScheduleAgenda.php    Agenda backed by a WeeklySchedule
    SingleDateRangeAgenda.php   Agenda for a single fixed start/end range
  Slots/
    AgendaSlotter.php           Slices an Agenda's ranges into fixed-duration slots
    DaySlotter.php              Sliding-window slots across the whole day (ignores agenda)
tests/
  Agenda/WeeklyScheduleAgendaTest.php
  Slots/AgendaSlotterTest.php
  Slots/DaySlotterTest.php
  WeeklyScheduleUnitTest.php
  Concerns/WithRangeAssertions.php   assertRanges() helper (compares Period::toIso8601())
```

## Core concepts and data flow

The typical pipeline is **schedule → agenda → slotter → slots**:

1. **`WeeklySchedule`** describes recurring weekly availability. Construction is
   only via the static factories — the constructor is `private`:
   - `WeeklySchedule::fromArray(array)` / `WeeklySchedule::fromJson(string)` —
     both run `validate()` and **throw a plain `\Exception`** on malformed input.
   - `WeeklySchedule::defaultWorkingHours()` returns a sample array (Mon–Fri
     08:00–12:00 & 14:00–18:00, Sat 10:00–12:00).
   - Fields: `daily` (per day-of-week `Sun`..`Sat`, each a list of
     `{start, end}` `HH:MM` ranges), `hours_in_advance` (int), `disable_all` (bool).
   - `forDate(CarbonInterface)` / `forDay(string)` return that day's ranges, or
     `[]` when `disable_all` is true.
2. **`Agenda::possibleRanges(from, to)`** turns availability into concrete
   `League\Period\Period[]` clamped to the `[from, to]` window.
   - `WeeklyScheduleAgenda` iterates each day in the window and emits one `Period`
     per matching schedule range.
   - `SingleDateRangeAgenda` emits at most one `Period` (the intersection of its
     fixed range with the window).
3. **`TimeSlotter::makeSlotsForDates(start, end)`** chops ranges into bookable
   slots (also `Period[]`).
   - `AgendaSlotter(Agenda $agenda, int $duration, int $timeAfter = 0, int $timeBefore = 0)`:
     fixed `$duration`-minute slots inside each agenda range. The stride between
     slot starts is `duration + max(timeAfter, timeBefore)`.
   - `DaySlotter(int $duration, int $step)`: ignores any agenda and produces
     `duration`-minute slots every `step` minutes across the full 24h of each day
     in the window (sliding window — slots may overlap when `step < duration`).

## Conventions and gotchas

- **Ranges and slots are always `League\Period\Period`** objects, not arrays.
  Serialize with `->toIso8601()`. Tests compare on the ISO-8601 string form.
- **Immutability**: all internal date math uses `Carbon`'s immutable variants
  (`toImmutable()`, `CarbonImmutable`, `CarbonPeriodImmutable`). `AgendaSlotter`
  and `DaySlotter` are `readonly class`es. Keep new code immutable.
- **Timezones**: agendas compute in the timezone of the input `Carbon` instances.
  `WeeklyScheduleAgenda` interprets `HH:MM` schedule times in that timezone and
  clamps to the window. `Period::toIso8601()` renders in UTC (`Z`), so identical
  wall-clock schedules in different timezones produce different UTC output — see
  `WeeklyScheduleAgendaTest::possibleRangesWithTimeZone`.
- **`hours_in_advance` is metadata only.** It is stored and exposed via
  `hoursInAdvance()` but **not enforced** by the slotters — minimum-notice
  filtering is the consumer's responsibility.
- **`disable_all` IS enforced** inside `WeeklySchedule::forDay/forDate` (returns
  `[]`), so a `WeeklyScheduleAgenda` over a disabled schedule yields no ranges.
- **`HasAgenda` and `TimeBookable` are not used internally** — they exist for
  consuming applications to implement on their own entities.
- Validation errors are plain `\Exception` with descriptive messages; the unit
  tests assert on those exact messages, so update tests if you change wording.

## Commands

```bash
composer install            # install dependencies
composer test               # run the PHPUnit suite (vendor/bin/phpunit)
composer test-coverage      # run tests with HTML coverage report
```

PHPUnit is configured in `phpunit.xml.dist`. It is **strict**:
`failOnRisky`, `failOnWarning`, and `failOnPhpunitDeprecation` are all enabled,
and coverage metadata is enforced. Tests use PHPUnit attributes (`#[Test]`,
`#[DataProvider]`), not docblock annotations.

## Quality gates / CI

- **GitHub Actions** (`.github/workflows/php.yml`) runs on push/PR to `master`
  across PHP **8.4** and **8.5**: `composer validate` then `composer test`.
- **Code style**: PSR-12 (`.styleci.yml` preset `psr12`). `.editorconfig`
  enforces 4-space indent, LF line endings, UTF-8, trailing-whitespace trim, and
  a final newline.
- Versioning follows **SemVer**; do not break public APIs casually.

## Working agreements

- **English only.** This is an open-source project: all code, comments, commit
  messages, PRs, and docs must be written in English.
- **Add tests** for any behavior change — PRs without tests are not accepted
  (see `CONTRIBUTING.md`). Prefer data-provider-driven tests like the existing
  slotter tests.
- Keep the library framework-agnostic — do not add framework dependencies.
- Update `README.md` and `CHANGELOG.md` when behavior or the public API changes.
