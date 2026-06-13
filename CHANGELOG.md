# Changelog

All notable changes to `bookables` are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

Released entries below are maintained automatically from the GitHub release notes
(see `.github/workflows/update-changelog.yml`); the `Unreleased` section tracks the
range of changes on `master` that have not been released yet.

## [Unreleased]

## v4.1.2 - 2026-03-07

- Update dependencies (#24).

## v4.1.1 - 2025-11-22

- Update dependencies (#23).

## v4.1.0 - 2025-11-09

- Support for PHP 8.3 and newer (#22).

## v4.0.1 - 2025-02-16

- All interfaces take and return immutable Carbon dates and periods (#21).

## v4.0.0 - 2025-02-05

- Make the library compatible with PHP 8.3 and later (#20).
- Update libraries (#19).

## v3.1.0 - 2023-03-18

- Upgrade to PHPUnit 10 (#17).

## v3.0.0 - 2022-12-14

- Make the library compatible with PHP 8.2 and later (#16).

## v2.6.0 - 2022-04-11

- Remove the production dependency on `nunomaduro/collision` (#15).

## v2.5.0 - 2021-11-22

- Redesign the agenda slotter (#14).
- Add a day slotter (#13).
- Segregate the slotter/agenda interfaces (#12).

## v2.4.0 - 2021-11-22

- Add the agenda slotter (#11).
- Update libraries (#10).

## v2.3.0 - 2021-06-11

- Add an option to align (or not) minutes to the stepping (#9).

## v2.2.0 - 2021-04-17

- Convert a schedule to array and to JSON (#8).

## v2.1.0 - 2021-04-09

- Remove the unused `Bookable` interface (#7).
- Require PHP 8+ and update libraries (#6).

## v2.0.0 - 2021-03-14

- Add a field to temporarily override a schedule and disable the agenda without
  deleting it (#5).
- Update libraries (#4).

## v1.0.0 - 2020-11-26

- Support for PHP 8 (#3).

## v0.0.4 - 2020-09-02

- Add contracts, agenda, and time slots (#2).

## v0.0.3 - 2020-09-02

- Add the weekly schedule class (#1).

## v0.0.2 - 2020-09-01

- Restrict the supported runtime to PHP 7.4.

## v0.0.1 - 2020-09-01

- Initial release.

[Unreleased]: https://github.com/puntodev/bookables/compare/v4.1.2...HEAD
