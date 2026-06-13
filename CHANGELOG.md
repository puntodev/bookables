# Changelog

All notable changes to `bookables` will be documented in this file

## Unreleased

### Security / hardening

- Cap requested date ranges to avoid unbounded slot generation (denial-of-service).
  `WeeklyScheduleAgenda`, `AgendaSlotter` and `DaySlotter` now take an optional
  `maxDays` argument (default `366`, `0` disables) and throw
  `Puntodev\Bookables\Exceptions\DateRangeTooLargeException` when exceeded.
- Reject non-positive `duration`/`step` (and negative `timeAfter`/`timeBefore`) in
  the slotters with `InvalidArgumentException`, preventing degenerate zero-interval
  loops.
- `WeeklySchedule` now validates schedule times strictly as a time of day
  (`HH:MM` or `HH:MM:SS`, `00:00`–`23:59`), rejecting relative expressions such as
  `now` or `+1 day`.
- `WeeklySchedule::fromJson()` now throws an `Exception` on malformed or non-object
  JSON instead of a `TypeError`.

## 1.0.0 - 201X-XX-XX

- initial release
