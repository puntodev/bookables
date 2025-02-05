<?php

namespace Tests\Slots;

use Carbon\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Puntodev\Bookables\Slots\DaySlotter;
use Tests\Concerns\WithRangeAssertions;

class DaySlotterTest extends TestCase
{
    use WithRangeAssertions;

    #[Test]
    #[DataProvider('dataProvider')]
    public function checkForDurationAndStepping($duration, $stepping, $expected): void
    {
        $slotter = new DaySlotter($duration, $stepping);

        $result = $slotter->makeSlotsForDates(
            Carbon::parse('2020-01-23'),
            Carbon::parse('2020-01-23'),
        );

        $this->assertRanges($expected, $result);
    }

    public static function dataProvider(): array
    {
        return [
            'duration 30 minutes and stepping every 15 minutes' => [
                30,
                15,
                [
                    "2020-01-23T00:00:00.000000Z/2020-01-23T00:30:00.000000Z",
                    "2020-01-23T00:15:00.000000Z/2020-01-23T00:45:00.000000Z",
                    "2020-01-23T00:30:00.000000Z/2020-01-23T01:00:00.000000Z",
                    "2020-01-23T00:45:00.000000Z/2020-01-23T01:15:00.000000Z",
                    "2020-01-23T01:00:00.000000Z/2020-01-23T01:30:00.000000Z",
                    "2020-01-23T01:15:00.000000Z/2020-01-23T01:45:00.000000Z",
                    "2020-01-23T01:30:00.000000Z/2020-01-23T02:00:00.000000Z",
                    "2020-01-23T01:45:00.000000Z/2020-01-23T02:15:00.000000Z",
                    "2020-01-23T02:00:00.000000Z/2020-01-23T02:30:00.000000Z",
                    "2020-01-23T02:15:00.000000Z/2020-01-23T02:45:00.000000Z",
                    "2020-01-23T02:30:00.000000Z/2020-01-23T03:00:00.000000Z",
                    "2020-01-23T02:45:00.000000Z/2020-01-23T03:15:00.000000Z",
                    "2020-01-23T03:00:00.000000Z/2020-01-23T03:30:00.000000Z",
                    "2020-01-23T03:15:00.000000Z/2020-01-23T03:45:00.000000Z",
                    "2020-01-23T03:30:00.000000Z/2020-01-23T04:00:00.000000Z",
                    "2020-01-23T03:45:00.000000Z/2020-01-23T04:15:00.000000Z",
                    "2020-01-23T04:00:00.000000Z/2020-01-23T04:30:00.000000Z",
                    "2020-01-23T04:15:00.000000Z/2020-01-23T04:45:00.000000Z",
                    "2020-01-23T04:30:00.000000Z/2020-01-23T05:00:00.000000Z",
                    "2020-01-23T04:45:00.000000Z/2020-01-23T05:15:00.000000Z",
                    "2020-01-23T05:00:00.000000Z/2020-01-23T05:30:00.000000Z",
                    "2020-01-23T05:15:00.000000Z/2020-01-23T05:45:00.000000Z",
                    "2020-01-23T05:30:00.000000Z/2020-01-23T06:00:00.000000Z",
                    "2020-01-23T05:45:00.000000Z/2020-01-23T06:15:00.000000Z",
                    "2020-01-23T06:00:00.000000Z/2020-01-23T06:30:00.000000Z",
                    "2020-01-23T06:15:00.000000Z/2020-01-23T06:45:00.000000Z",
                    "2020-01-23T06:30:00.000000Z/2020-01-23T07:00:00.000000Z",
                    "2020-01-23T06:45:00.000000Z/2020-01-23T07:15:00.000000Z",
                    "2020-01-23T07:00:00.000000Z/2020-01-23T07:30:00.000000Z",
                    "2020-01-23T07:15:00.000000Z/2020-01-23T07:45:00.000000Z",
                    "2020-01-23T07:30:00.000000Z/2020-01-23T08:00:00.000000Z",
                    "2020-01-23T07:45:00.000000Z/2020-01-23T08:15:00.000000Z",
                    "2020-01-23T08:00:00.000000Z/2020-01-23T08:30:00.000000Z",
                    "2020-01-23T08:15:00.000000Z/2020-01-23T08:45:00.000000Z",
                    "2020-01-23T08:30:00.000000Z/2020-01-23T09:00:00.000000Z",
                    "2020-01-23T08:45:00.000000Z/2020-01-23T09:15:00.000000Z",
                    "2020-01-23T09:00:00.000000Z/2020-01-23T09:30:00.000000Z",
                    "2020-01-23T09:15:00.000000Z/2020-01-23T09:45:00.000000Z",
                    "2020-01-23T09:30:00.000000Z/2020-01-23T10:00:00.000000Z",
                    "2020-01-23T09:45:00.000000Z/2020-01-23T10:15:00.000000Z",
                    "2020-01-23T10:00:00.000000Z/2020-01-23T10:30:00.000000Z",
                    "2020-01-23T10:15:00.000000Z/2020-01-23T10:45:00.000000Z",
                    "2020-01-23T10:30:00.000000Z/2020-01-23T11:00:00.000000Z",
                    "2020-01-23T10:45:00.000000Z/2020-01-23T11:15:00.000000Z",
                    "2020-01-23T11:00:00.000000Z/2020-01-23T11:30:00.000000Z",
                    "2020-01-23T11:15:00.000000Z/2020-01-23T11:45:00.000000Z",
                    "2020-01-23T11:30:00.000000Z/2020-01-23T12:00:00.000000Z",
                    "2020-01-23T11:45:00.000000Z/2020-01-23T12:15:00.000000Z",
                    "2020-01-23T12:00:00.000000Z/2020-01-23T12:30:00.000000Z",
                    "2020-01-23T12:15:00.000000Z/2020-01-23T12:45:00.000000Z",
                    "2020-01-23T12:30:00.000000Z/2020-01-23T13:00:00.000000Z",
                    "2020-01-23T12:45:00.000000Z/2020-01-23T13:15:00.000000Z",
                    "2020-01-23T13:00:00.000000Z/2020-01-23T13:30:00.000000Z",
                    "2020-01-23T13:15:00.000000Z/2020-01-23T13:45:00.000000Z",
                    "2020-01-23T13:30:00.000000Z/2020-01-23T14:00:00.000000Z",
                    "2020-01-23T13:45:00.000000Z/2020-01-23T14:15:00.000000Z",
                    "2020-01-23T14:00:00.000000Z/2020-01-23T14:30:00.000000Z",
                    "2020-01-23T14:15:00.000000Z/2020-01-23T14:45:00.000000Z",
                    "2020-01-23T14:30:00.000000Z/2020-01-23T15:00:00.000000Z",
                    "2020-01-23T14:45:00.000000Z/2020-01-23T15:15:00.000000Z",
                    "2020-01-23T15:00:00.000000Z/2020-01-23T15:30:00.000000Z",
                    "2020-01-23T15:15:00.000000Z/2020-01-23T15:45:00.000000Z",
                    "2020-01-23T15:30:00.000000Z/2020-01-23T16:00:00.000000Z",
                    "2020-01-23T15:45:00.000000Z/2020-01-23T16:15:00.000000Z",
                    "2020-01-23T16:00:00.000000Z/2020-01-23T16:30:00.000000Z",
                    "2020-01-23T16:15:00.000000Z/2020-01-23T16:45:00.000000Z",
                    "2020-01-23T16:30:00.000000Z/2020-01-23T17:00:00.000000Z",
                    "2020-01-23T16:45:00.000000Z/2020-01-23T17:15:00.000000Z",
                    "2020-01-23T17:00:00.000000Z/2020-01-23T17:30:00.000000Z",
                    "2020-01-23T17:15:00.000000Z/2020-01-23T17:45:00.000000Z",
                    "2020-01-23T17:30:00.000000Z/2020-01-23T18:00:00.000000Z",
                    "2020-01-23T17:45:00.000000Z/2020-01-23T18:15:00.000000Z",
                    "2020-01-23T18:00:00.000000Z/2020-01-23T18:30:00.000000Z",
                    "2020-01-23T18:15:00.000000Z/2020-01-23T18:45:00.000000Z",
                    "2020-01-23T18:30:00.000000Z/2020-01-23T19:00:00.000000Z",
                    "2020-01-23T18:45:00.000000Z/2020-01-23T19:15:00.000000Z",
                    "2020-01-23T19:00:00.000000Z/2020-01-23T19:30:00.000000Z",
                    "2020-01-23T19:15:00.000000Z/2020-01-23T19:45:00.000000Z",
                    "2020-01-23T19:30:00.000000Z/2020-01-23T20:00:00.000000Z",
                    "2020-01-23T19:45:00.000000Z/2020-01-23T20:15:00.000000Z",
                    "2020-01-23T20:00:00.000000Z/2020-01-23T20:30:00.000000Z",
                    "2020-01-23T20:15:00.000000Z/2020-01-23T20:45:00.000000Z",
                    "2020-01-23T20:30:00.000000Z/2020-01-23T21:00:00.000000Z",
                    "2020-01-23T20:45:00.000000Z/2020-01-23T21:15:00.000000Z",
                    "2020-01-23T21:00:00.000000Z/2020-01-23T21:30:00.000000Z",
                    "2020-01-23T21:15:00.000000Z/2020-01-23T21:45:00.000000Z",
                    "2020-01-23T21:30:00.000000Z/2020-01-23T22:00:00.000000Z",
                    "2020-01-23T21:45:00.000000Z/2020-01-23T22:15:00.000000Z",
                    "2020-01-23T22:00:00.000000Z/2020-01-23T22:30:00.000000Z",
                    "2020-01-23T22:15:00.000000Z/2020-01-23T22:45:00.000000Z",
                    "2020-01-23T22:30:00.000000Z/2020-01-23T23:00:00.000000Z",
                    "2020-01-23T22:45:00.000000Z/2020-01-23T23:15:00.000000Z",
                    "2020-01-23T23:00:00.000000Z/2020-01-23T23:30:00.000000Z",
                    "2020-01-23T23:15:00.000000Z/2020-01-23T23:45:00.000000Z",
                    "2020-01-23T23:30:00.000000Z/2020-01-24T00:00:00.000000Z",
                ],
            ],
            'duration 45 minutes and stepping every 15 minutes' => [
                45,
                15,
                [
                    "2020-01-23T00:00:00.000000Z/2020-01-23T00:45:00.000000Z",
                    "2020-01-23T00:15:00.000000Z/2020-01-23T01:00:00.000000Z",
                    "2020-01-23T00:30:00.000000Z/2020-01-23T01:15:00.000000Z",
                    "2020-01-23T00:45:00.000000Z/2020-01-23T01:30:00.000000Z",
                    "2020-01-23T01:00:00.000000Z/2020-01-23T01:45:00.000000Z",
                    "2020-01-23T01:15:00.000000Z/2020-01-23T02:00:00.000000Z",
                    "2020-01-23T01:30:00.000000Z/2020-01-23T02:15:00.000000Z",
                    "2020-01-23T01:45:00.000000Z/2020-01-23T02:30:00.000000Z",
                    "2020-01-23T02:00:00.000000Z/2020-01-23T02:45:00.000000Z",
                    "2020-01-23T02:15:00.000000Z/2020-01-23T03:00:00.000000Z",
                    "2020-01-23T02:30:00.000000Z/2020-01-23T03:15:00.000000Z",
                    "2020-01-23T02:45:00.000000Z/2020-01-23T03:30:00.000000Z",
                    "2020-01-23T03:00:00.000000Z/2020-01-23T03:45:00.000000Z",
                    "2020-01-23T03:15:00.000000Z/2020-01-23T04:00:00.000000Z",
                    "2020-01-23T03:30:00.000000Z/2020-01-23T04:15:00.000000Z",
                    "2020-01-23T03:45:00.000000Z/2020-01-23T04:30:00.000000Z",
                    "2020-01-23T04:00:00.000000Z/2020-01-23T04:45:00.000000Z",
                    "2020-01-23T04:15:00.000000Z/2020-01-23T05:00:00.000000Z",
                    "2020-01-23T04:30:00.000000Z/2020-01-23T05:15:00.000000Z",
                    "2020-01-23T04:45:00.000000Z/2020-01-23T05:30:00.000000Z",
                    "2020-01-23T05:00:00.000000Z/2020-01-23T05:45:00.000000Z",
                    "2020-01-23T05:15:00.000000Z/2020-01-23T06:00:00.000000Z",
                    "2020-01-23T05:30:00.000000Z/2020-01-23T06:15:00.000000Z",
                    "2020-01-23T05:45:00.000000Z/2020-01-23T06:30:00.000000Z",
                    "2020-01-23T06:00:00.000000Z/2020-01-23T06:45:00.000000Z",
                    "2020-01-23T06:15:00.000000Z/2020-01-23T07:00:00.000000Z",
                    "2020-01-23T06:30:00.000000Z/2020-01-23T07:15:00.000000Z",
                    "2020-01-23T06:45:00.000000Z/2020-01-23T07:30:00.000000Z",
                    "2020-01-23T07:00:00.000000Z/2020-01-23T07:45:00.000000Z",
                    "2020-01-23T07:15:00.000000Z/2020-01-23T08:00:00.000000Z",
                    "2020-01-23T07:30:00.000000Z/2020-01-23T08:15:00.000000Z",
                    "2020-01-23T07:45:00.000000Z/2020-01-23T08:30:00.000000Z",
                    "2020-01-23T08:00:00.000000Z/2020-01-23T08:45:00.000000Z",
                    "2020-01-23T08:15:00.000000Z/2020-01-23T09:00:00.000000Z",
                    "2020-01-23T08:30:00.000000Z/2020-01-23T09:15:00.000000Z",
                    "2020-01-23T08:45:00.000000Z/2020-01-23T09:30:00.000000Z",
                    "2020-01-23T09:00:00.000000Z/2020-01-23T09:45:00.000000Z",
                    "2020-01-23T09:15:00.000000Z/2020-01-23T10:00:00.000000Z",
                    "2020-01-23T09:30:00.000000Z/2020-01-23T10:15:00.000000Z",
                    "2020-01-23T09:45:00.000000Z/2020-01-23T10:30:00.000000Z",
                    "2020-01-23T10:00:00.000000Z/2020-01-23T10:45:00.000000Z",
                    "2020-01-23T10:15:00.000000Z/2020-01-23T11:00:00.000000Z",
                    "2020-01-23T10:30:00.000000Z/2020-01-23T11:15:00.000000Z",
                    "2020-01-23T10:45:00.000000Z/2020-01-23T11:30:00.000000Z",
                    "2020-01-23T11:00:00.000000Z/2020-01-23T11:45:00.000000Z",
                    "2020-01-23T11:15:00.000000Z/2020-01-23T12:00:00.000000Z",
                    "2020-01-23T11:30:00.000000Z/2020-01-23T12:15:00.000000Z",
                    "2020-01-23T11:45:00.000000Z/2020-01-23T12:30:00.000000Z",
                    "2020-01-23T12:00:00.000000Z/2020-01-23T12:45:00.000000Z",
                    "2020-01-23T12:15:00.000000Z/2020-01-23T13:00:00.000000Z",
                    "2020-01-23T12:30:00.000000Z/2020-01-23T13:15:00.000000Z",
                    "2020-01-23T12:45:00.000000Z/2020-01-23T13:30:00.000000Z",
                    "2020-01-23T13:00:00.000000Z/2020-01-23T13:45:00.000000Z",
                    "2020-01-23T13:15:00.000000Z/2020-01-23T14:00:00.000000Z",
                    "2020-01-23T13:30:00.000000Z/2020-01-23T14:15:00.000000Z",
                    "2020-01-23T13:45:00.000000Z/2020-01-23T14:30:00.000000Z",
                    "2020-01-23T14:00:00.000000Z/2020-01-23T14:45:00.000000Z",
                    "2020-01-23T14:15:00.000000Z/2020-01-23T15:00:00.000000Z",
                    "2020-01-23T14:30:00.000000Z/2020-01-23T15:15:00.000000Z",
                    "2020-01-23T14:45:00.000000Z/2020-01-23T15:30:00.000000Z",
                    "2020-01-23T15:00:00.000000Z/2020-01-23T15:45:00.000000Z",
                    "2020-01-23T15:15:00.000000Z/2020-01-23T16:00:00.000000Z",
                    "2020-01-23T15:30:00.000000Z/2020-01-23T16:15:00.000000Z",
                    "2020-01-23T15:45:00.000000Z/2020-01-23T16:30:00.000000Z",
                    "2020-01-23T16:00:00.000000Z/2020-01-23T16:45:00.000000Z",
                    "2020-01-23T16:15:00.000000Z/2020-01-23T17:00:00.000000Z",
                    "2020-01-23T16:30:00.000000Z/2020-01-23T17:15:00.000000Z",
                    "2020-01-23T16:45:00.000000Z/2020-01-23T17:30:00.000000Z",
                    "2020-01-23T17:00:00.000000Z/2020-01-23T17:45:00.000000Z",
                    "2020-01-23T17:15:00.000000Z/2020-01-23T18:00:00.000000Z",
                    "2020-01-23T17:30:00.000000Z/2020-01-23T18:15:00.000000Z",
                    "2020-01-23T17:45:00.000000Z/2020-01-23T18:30:00.000000Z",
                    "2020-01-23T18:00:00.000000Z/2020-01-23T18:45:00.000000Z",
                    "2020-01-23T18:15:00.000000Z/2020-01-23T19:00:00.000000Z",
                    "2020-01-23T18:30:00.000000Z/2020-01-23T19:15:00.000000Z",
                    "2020-01-23T18:45:00.000000Z/2020-01-23T19:30:00.000000Z",
                    "2020-01-23T19:00:00.000000Z/2020-01-23T19:45:00.000000Z",
                    "2020-01-23T19:15:00.000000Z/2020-01-23T20:00:00.000000Z",
                    "2020-01-23T19:30:00.000000Z/2020-01-23T20:15:00.000000Z",
                    "2020-01-23T19:45:00.000000Z/2020-01-23T20:30:00.000000Z",
                    "2020-01-23T20:00:00.000000Z/2020-01-23T20:45:00.000000Z",
                    "2020-01-23T20:15:00.000000Z/2020-01-23T21:00:00.000000Z",
                    "2020-01-23T20:30:00.000000Z/2020-01-23T21:15:00.000000Z",
                    "2020-01-23T20:45:00.000000Z/2020-01-23T21:30:00.000000Z",
                    "2020-01-23T21:00:00.000000Z/2020-01-23T21:45:00.000000Z",
                    "2020-01-23T21:15:00.000000Z/2020-01-23T22:00:00.000000Z",
                    "2020-01-23T21:30:00.000000Z/2020-01-23T22:15:00.000000Z",
                    "2020-01-23T21:45:00.000000Z/2020-01-23T22:30:00.000000Z",
                    "2020-01-23T22:00:00.000000Z/2020-01-23T22:45:00.000000Z",
                    "2020-01-23T22:15:00.000000Z/2020-01-23T23:00:00.000000Z",
                    "2020-01-23T22:30:00.000000Z/2020-01-23T23:15:00.000000Z",
                    "2020-01-23T22:45:00.000000Z/2020-01-23T23:30:00.000000Z",
                    "2020-01-23T23:00:00.000000Z/2020-01-23T23:45:00.000000Z",
                    "2020-01-23T23:15:00.000000Z/2020-01-24T00:00:00.000000Z",
                ],
            ],
            'duration 60 minutes and stepping every 15 minutes' => [
                60,
                15,
                [
                    "2020-01-23T00:00:00.000000Z/2020-01-23T01:00:00.000000Z",
                    "2020-01-23T00:15:00.000000Z/2020-01-23T01:15:00.000000Z",
                    "2020-01-23T00:30:00.000000Z/2020-01-23T01:30:00.000000Z",
                    "2020-01-23T00:45:00.000000Z/2020-01-23T01:45:00.000000Z",
                    "2020-01-23T01:00:00.000000Z/2020-01-23T02:00:00.000000Z",
                    "2020-01-23T01:15:00.000000Z/2020-01-23T02:15:00.000000Z",
                    "2020-01-23T01:30:00.000000Z/2020-01-23T02:30:00.000000Z",
                    "2020-01-23T01:45:00.000000Z/2020-01-23T02:45:00.000000Z",
                    "2020-01-23T02:00:00.000000Z/2020-01-23T03:00:00.000000Z",
                    "2020-01-23T02:15:00.000000Z/2020-01-23T03:15:00.000000Z",
                    "2020-01-23T02:30:00.000000Z/2020-01-23T03:30:00.000000Z",
                    "2020-01-23T02:45:00.000000Z/2020-01-23T03:45:00.000000Z",
                    "2020-01-23T03:00:00.000000Z/2020-01-23T04:00:00.000000Z",
                    "2020-01-23T03:15:00.000000Z/2020-01-23T04:15:00.000000Z",
                    "2020-01-23T03:30:00.000000Z/2020-01-23T04:30:00.000000Z",
                    "2020-01-23T03:45:00.000000Z/2020-01-23T04:45:00.000000Z",
                    "2020-01-23T04:00:00.000000Z/2020-01-23T05:00:00.000000Z",
                    "2020-01-23T04:15:00.000000Z/2020-01-23T05:15:00.000000Z",
                    "2020-01-23T04:30:00.000000Z/2020-01-23T05:30:00.000000Z",
                    "2020-01-23T04:45:00.000000Z/2020-01-23T05:45:00.000000Z",
                    "2020-01-23T05:00:00.000000Z/2020-01-23T06:00:00.000000Z",
                    "2020-01-23T05:15:00.000000Z/2020-01-23T06:15:00.000000Z",
                    "2020-01-23T05:30:00.000000Z/2020-01-23T06:30:00.000000Z",
                    "2020-01-23T05:45:00.000000Z/2020-01-23T06:45:00.000000Z",
                    "2020-01-23T06:00:00.000000Z/2020-01-23T07:00:00.000000Z",
                    "2020-01-23T06:15:00.000000Z/2020-01-23T07:15:00.000000Z",
                    "2020-01-23T06:30:00.000000Z/2020-01-23T07:30:00.000000Z",
                    "2020-01-23T06:45:00.000000Z/2020-01-23T07:45:00.000000Z",
                    "2020-01-23T07:00:00.000000Z/2020-01-23T08:00:00.000000Z",
                    "2020-01-23T07:15:00.000000Z/2020-01-23T08:15:00.000000Z",
                    "2020-01-23T07:30:00.000000Z/2020-01-23T08:30:00.000000Z",
                    "2020-01-23T07:45:00.000000Z/2020-01-23T08:45:00.000000Z",
                    "2020-01-23T08:00:00.000000Z/2020-01-23T09:00:00.000000Z",
                    "2020-01-23T08:15:00.000000Z/2020-01-23T09:15:00.000000Z",
                    "2020-01-23T08:30:00.000000Z/2020-01-23T09:30:00.000000Z",
                    "2020-01-23T08:45:00.000000Z/2020-01-23T09:45:00.000000Z",
                    "2020-01-23T09:00:00.000000Z/2020-01-23T10:00:00.000000Z",
                    "2020-01-23T09:15:00.000000Z/2020-01-23T10:15:00.000000Z",
                    "2020-01-23T09:30:00.000000Z/2020-01-23T10:30:00.000000Z",
                    "2020-01-23T09:45:00.000000Z/2020-01-23T10:45:00.000000Z",
                    "2020-01-23T10:00:00.000000Z/2020-01-23T11:00:00.000000Z",
                    "2020-01-23T10:15:00.000000Z/2020-01-23T11:15:00.000000Z",
                    "2020-01-23T10:30:00.000000Z/2020-01-23T11:30:00.000000Z",
                    "2020-01-23T10:45:00.000000Z/2020-01-23T11:45:00.000000Z",
                    "2020-01-23T11:00:00.000000Z/2020-01-23T12:00:00.000000Z",
                    "2020-01-23T11:15:00.000000Z/2020-01-23T12:15:00.000000Z",
                    "2020-01-23T11:30:00.000000Z/2020-01-23T12:30:00.000000Z",
                    "2020-01-23T11:45:00.000000Z/2020-01-23T12:45:00.000000Z",
                    "2020-01-23T12:00:00.000000Z/2020-01-23T13:00:00.000000Z",
                    "2020-01-23T12:15:00.000000Z/2020-01-23T13:15:00.000000Z",
                    "2020-01-23T12:30:00.000000Z/2020-01-23T13:30:00.000000Z",
                    "2020-01-23T12:45:00.000000Z/2020-01-23T13:45:00.000000Z",
                    "2020-01-23T13:00:00.000000Z/2020-01-23T14:00:00.000000Z",
                    "2020-01-23T13:15:00.000000Z/2020-01-23T14:15:00.000000Z",
                    "2020-01-23T13:30:00.000000Z/2020-01-23T14:30:00.000000Z",
                    "2020-01-23T13:45:00.000000Z/2020-01-23T14:45:00.000000Z",
                    "2020-01-23T14:00:00.000000Z/2020-01-23T15:00:00.000000Z",
                    "2020-01-23T14:15:00.000000Z/2020-01-23T15:15:00.000000Z",
                    "2020-01-23T14:30:00.000000Z/2020-01-23T15:30:00.000000Z",
                    "2020-01-23T14:45:00.000000Z/2020-01-23T15:45:00.000000Z",
                    "2020-01-23T15:00:00.000000Z/2020-01-23T16:00:00.000000Z",
                    "2020-01-23T15:15:00.000000Z/2020-01-23T16:15:00.000000Z",
                    "2020-01-23T15:30:00.000000Z/2020-01-23T16:30:00.000000Z",
                    "2020-01-23T15:45:00.000000Z/2020-01-23T16:45:00.000000Z",
                    "2020-01-23T16:00:00.000000Z/2020-01-23T17:00:00.000000Z",
                    "2020-01-23T16:15:00.000000Z/2020-01-23T17:15:00.000000Z",
                    "2020-01-23T16:30:00.000000Z/2020-01-23T17:30:00.000000Z",
                    "2020-01-23T16:45:00.000000Z/2020-01-23T17:45:00.000000Z",
                    "2020-01-23T17:00:00.000000Z/2020-01-23T18:00:00.000000Z",
                    "2020-01-23T17:15:00.000000Z/2020-01-23T18:15:00.000000Z",
                    "2020-01-23T17:30:00.000000Z/2020-01-23T18:30:00.000000Z",
                    "2020-01-23T17:45:00.000000Z/2020-01-23T18:45:00.000000Z",
                    "2020-01-23T18:00:00.000000Z/2020-01-23T19:00:00.000000Z",
                    "2020-01-23T18:15:00.000000Z/2020-01-23T19:15:00.000000Z",
                    "2020-01-23T18:30:00.000000Z/2020-01-23T19:30:00.000000Z",
                    "2020-01-23T18:45:00.000000Z/2020-01-23T19:45:00.000000Z",
                    "2020-01-23T19:00:00.000000Z/2020-01-23T20:00:00.000000Z",
                    "2020-01-23T19:15:00.000000Z/2020-01-23T20:15:00.000000Z",
                    "2020-01-23T19:30:00.000000Z/2020-01-23T20:30:00.000000Z",
                    "2020-01-23T19:45:00.000000Z/2020-01-23T20:45:00.000000Z",
                    "2020-01-23T20:00:00.000000Z/2020-01-23T21:00:00.000000Z",
                    "2020-01-23T20:15:00.000000Z/2020-01-23T21:15:00.000000Z",
                    "2020-01-23T20:30:00.000000Z/2020-01-23T21:30:00.000000Z",
                    "2020-01-23T20:45:00.000000Z/2020-01-23T21:45:00.000000Z",
                    "2020-01-23T21:00:00.000000Z/2020-01-23T22:00:00.000000Z",
                    "2020-01-23T21:15:00.000000Z/2020-01-23T22:15:00.000000Z",
                    "2020-01-23T21:30:00.000000Z/2020-01-23T22:30:00.000000Z",
                    "2020-01-23T21:45:00.000000Z/2020-01-23T22:45:00.000000Z",
                    "2020-01-23T22:00:00.000000Z/2020-01-23T23:00:00.000000Z",
                    "2020-01-23T22:15:00.000000Z/2020-01-23T23:15:00.000000Z",
                    "2020-01-23T22:30:00.000000Z/2020-01-23T23:30:00.000000Z",
                    "2020-01-23T22:45:00.000000Z/2020-01-23T23:45:00.000000Z",
                    "2020-01-23T23:00:00.000000Z/2020-01-24T00:00:00.000000Z",
                ],
            ],
            'duration 30 minutes and stepping every 30 minutes' => [
                30,
                30,
                [
                    "2020-01-23T00:00:00.000000Z/2020-01-23T00:30:00.000000Z",
                    "2020-01-23T00:30:00.000000Z/2020-01-23T01:00:00.000000Z",
                    "2020-01-23T01:00:00.000000Z/2020-01-23T01:30:00.000000Z",
                    "2020-01-23T01:30:00.000000Z/2020-01-23T02:00:00.000000Z",
                    "2020-01-23T02:00:00.000000Z/2020-01-23T02:30:00.000000Z",
                    "2020-01-23T02:30:00.000000Z/2020-01-23T03:00:00.000000Z",
                    "2020-01-23T03:00:00.000000Z/2020-01-23T03:30:00.000000Z",
                    "2020-01-23T03:30:00.000000Z/2020-01-23T04:00:00.000000Z",
                    "2020-01-23T04:00:00.000000Z/2020-01-23T04:30:00.000000Z",
                    "2020-01-23T04:30:00.000000Z/2020-01-23T05:00:00.000000Z",
                    "2020-01-23T05:00:00.000000Z/2020-01-23T05:30:00.000000Z",
                    "2020-01-23T05:30:00.000000Z/2020-01-23T06:00:00.000000Z",
                    "2020-01-23T06:00:00.000000Z/2020-01-23T06:30:00.000000Z",
                    "2020-01-23T06:30:00.000000Z/2020-01-23T07:00:00.000000Z",
                    "2020-01-23T07:00:00.000000Z/2020-01-23T07:30:00.000000Z",
                    "2020-01-23T07:30:00.000000Z/2020-01-23T08:00:00.000000Z",
                    "2020-01-23T08:00:00.000000Z/2020-01-23T08:30:00.000000Z",
                    "2020-01-23T08:30:00.000000Z/2020-01-23T09:00:00.000000Z",
                    "2020-01-23T09:00:00.000000Z/2020-01-23T09:30:00.000000Z",
                    "2020-01-23T09:30:00.000000Z/2020-01-23T10:00:00.000000Z",
                    "2020-01-23T10:00:00.000000Z/2020-01-23T10:30:00.000000Z",
                    "2020-01-23T10:30:00.000000Z/2020-01-23T11:00:00.000000Z",
                    "2020-01-23T11:00:00.000000Z/2020-01-23T11:30:00.000000Z",
                    "2020-01-23T11:30:00.000000Z/2020-01-23T12:00:00.000000Z",
                    "2020-01-23T12:00:00.000000Z/2020-01-23T12:30:00.000000Z",
                    "2020-01-23T12:30:00.000000Z/2020-01-23T13:00:00.000000Z",
                    "2020-01-23T13:00:00.000000Z/2020-01-23T13:30:00.000000Z",
                    "2020-01-23T13:30:00.000000Z/2020-01-23T14:00:00.000000Z",
                    "2020-01-23T14:00:00.000000Z/2020-01-23T14:30:00.000000Z",
                    "2020-01-23T14:30:00.000000Z/2020-01-23T15:00:00.000000Z",
                    "2020-01-23T15:00:00.000000Z/2020-01-23T15:30:00.000000Z",
                    "2020-01-23T15:30:00.000000Z/2020-01-23T16:00:00.000000Z",
                    "2020-01-23T16:00:00.000000Z/2020-01-23T16:30:00.000000Z",
                    "2020-01-23T16:30:00.000000Z/2020-01-23T17:00:00.000000Z",
                    "2020-01-23T17:00:00.000000Z/2020-01-23T17:30:00.000000Z",
                    "2020-01-23T17:30:00.000000Z/2020-01-23T18:00:00.000000Z",
                    "2020-01-23T18:00:00.000000Z/2020-01-23T18:30:00.000000Z",
                    "2020-01-23T18:30:00.000000Z/2020-01-23T19:00:00.000000Z",
                    "2020-01-23T19:00:00.000000Z/2020-01-23T19:30:00.000000Z",
                    "2020-01-23T19:30:00.000000Z/2020-01-23T20:00:00.000000Z",
                    "2020-01-23T20:00:00.000000Z/2020-01-23T20:30:00.000000Z",
                    "2020-01-23T20:30:00.000000Z/2020-01-23T21:00:00.000000Z",
                    "2020-01-23T21:00:00.000000Z/2020-01-23T21:30:00.000000Z",
                    "2020-01-23T21:30:00.000000Z/2020-01-23T22:00:00.000000Z",
                    "2020-01-23T22:00:00.000000Z/2020-01-23T22:30:00.000000Z",
                    "2020-01-23T22:30:00.000000Z/2020-01-23T23:00:00.000000Z",
                    "2020-01-23T23:00:00.000000Z/2020-01-23T23:30:00.000000Z",
                    "2020-01-23T23:30:00.000000Z/2020-01-24T00:00:00.000000Z",
                ],
            ],
            'duration 60 minutes and stepping every 30 minutes' => [
                60,
                30,
                [
                    "2020-01-23T00:00:00.000000Z/2020-01-23T01:00:00.000000Z",
                    "2020-01-23T00:30:00.000000Z/2020-01-23T01:30:00.000000Z",
                    "2020-01-23T01:00:00.000000Z/2020-01-23T02:00:00.000000Z",
                    "2020-01-23T01:30:00.000000Z/2020-01-23T02:30:00.000000Z",
                    "2020-01-23T02:00:00.000000Z/2020-01-23T03:00:00.000000Z",
                    "2020-01-23T02:30:00.000000Z/2020-01-23T03:30:00.000000Z",
                    "2020-01-23T03:00:00.000000Z/2020-01-23T04:00:00.000000Z",
                    "2020-01-23T03:30:00.000000Z/2020-01-23T04:30:00.000000Z",
                    "2020-01-23T04:00:00.000000Z/2020-01-23T05:00:00.000000Z",
                    "2020-01-23T04:30:00.000000Z/2020-01-23T05:30:00.000000Z",
                    "2020-01-23T05:00:00.000000Z/2020-01-23T06:00:00.000000Z",
                    "2020-01-23T05:30:00.000000Z/2020-01-23T06:30:00.000000Z",
                    "2020-01-23T06:00:00.000000Z/2020-01-23T07:00:00.000000Z",
                    "2020-01-23T06:30:00.000000Z/2020-01-23T07:30:00.000000Z",
                    "2020-01-23T07:00:00.000000Z/2020-01-23T08:00:00.000000Z",
                    "2020-01-23T07:30:00.000000Z/2020-01-23T08:30:00.000000Z",
                    "2020-01-23T08:00:00.000000Z/2020-01-23T09:00:00.000000Z",
                    "2020-01-23T08:30:00.000000Z/2020-01-23T09:30:00.000000Z",
                    "2020-01-23T09:00:00.000000Z/2020-01-23T10:00:00.000000Z",
                    "2020-01-23T09:30:00.000000Z/2020-01-23T10:30:00.000000Z",
                    "2020-01-23T10:00:00.000000Z/2020-01-23T11:00:00.000000Z",
                    "2020-01-23T10:30:00.000000Z/2020-01-23T11:30:00.000000Z",
                    "2020-01-23T11:00:00.000000Z/2020-01-23T12:00:00.000000Z",
                    "2020-01-23T11:30:00.000000Z/2020-01-23T12:30:00.000000Z",
                    "2020-01-23T12:00:00.000000Z/2020-01-23T13:00:00.000000Z",
                    "2020-01-23T12:30:00.000000Z/2020-01-23T13:30:00.000000Z",
                    "2020-01-23T13:00:00.000000Z/2020-01-23T14:00:00.000000Z",
                    "2020-01-23T13:30:00.000000Z/2020-01-23T14:30:00.000000Z",
                    "2020-01-23T14:00:00.000000Z/2020-01-23T15:00:00.000000Z",
                    "2020-01-23T14:30:00.000000Z/2020-01-23T15:30:00.000000Z",
                    "2020-01-23T15:00:00.000000Z/2020-01-23T16:00:00.000000Z",
                    "2020-01-23T15:30:00.000000Z/2020-01-23T16:30:00.000000Z",
                    "2020-01-23T16:00:00.000000Z/2020-01-23T17:00:00.000000Z",
                    "2020-01-23T16:30:00.000000Z/2020-01-23T17:30:00.000000Z",
                    "2020-01-23T17:00:00.000000Z/2020-01-23T18:00:00.000000Z",
                    "2020-01-23T17:30:00.000000Z/2020-01-23T18:30:00.000000Z",
                    "2020-01-23T18:00:00.000000Z/2020-01-23T19:00:00.000000Z",
                    "2020-01-23T18:30:00.000000Z/2020-01-23T19:30:00.000000Z",
                    "2020-01-23T19:00:00.000000Z/2020-01-23T20:00:00.000000Z",
                    "2020-01-23T19:30:00.000000Z/2020-01-23T20:30:00.000000Z",
                    "2020-01-23T20:00:00.000000Z/2020-01-23T21:00:00.000000Z",
                    "2020-01-23T20:30:00.000000Z/2020-01-23T21:30:00.000000Z",
                    "2020-01-23T21:00:00.000000Z/2020-01-23T22:00:00.000000Z",
                    "2020-01-23T21:30:00.000000Z/2020-01-23T22:30:00.000000Z",
                    "2020-01-23T22:00:00.000000Z/2020-01-23T23:00:00.000000Z",
                    "2020-01-23T22:30:00.000000Z/2020-01-23T23:30:00.000000Z",
                    "2020-01-23T23:00:00.000000Z/2020-01-24T00:00:00.000000Z",
                ],
            ],
            'duration 30 minutes and stepping every 60 minutes' => [
                30,
                60,
                [
                    "2020-01-23T00:00:00.000000Z/2020-01-23T00:30:00.000000Z",
                    "2020-01-23T01:00:00.000000Z/2020-01-23T01:30:00.000000Z",
                    "2020-01-23T02:00:00.000000Z/2020-01-23T02:30:00.000000Z",
                    "2020-01-23T03:00:00.000000Z/2020-01-23T03:30:00.000000Z",
                    "2020-01-23T04:00:00.000000Z/2020-01-23T04:30:00.000000Z",
                    "2020-01-23T05:00:00.000000Z/2020-01-23T05:30:00.000000Z",
                    "2020-01-23T06:00:00.000000Z/2020-01-23T06:30:00.000000Z",
                    "2020-01-23T07:00:00.000000Z/2020-01-23T07:30:00.000000Z",
                    "2020-01-23T08:00:00.000000Z/2020-01-23T08:30:00.000000Z",
                    "2020-01-23T09:00:00.000000Z/2020-01-23T09:30:00.000000Z",
                    "2020-01-23T10:00:00.000000Z/2020-01-23T10:30:00.000000Z",
                    "2020-01-23T11:00:00.000000Z/2020-01-23T11:30:00.000000Z",
                    "2020-01-23T12:00:00.000000Z/2020-01-23T12:30:00.000000Z",
                    "2020-01-23T13:00:00.000000Z/2020-01-23T13:30:00.000000Z",
                    "2020-01-23T14:00:00.000000Z/2020-01-23T14:30:00.000000Z",
                    "2020-01-23T15:00:00.000000Z/2020-01-23T15:30:00.000000Z",
                    "2020-01-23T16:00:00.000000Z/2020-01-23T16:30:00.000000Z",
                    "2020-01-23T17:00:00.000000Z/2020-01-23T17:30:00.000000Z",
                    "2020-01-23T18:00:00.000000Z/2020-01-23T18:30:00.000000Z",
                    "2020-01-23T19:00:00.000000Z/2020-01-23T19:30:00.000000Z",
                    "2020-01-23T20:00:00.000000Z/2020-01-23T20:30:00.000000Z",
                    "2020-01-23T21:00:00.000000Z/2020-01-23T21:30:00.000000Z",
                    "2020-01-23T22:00:00.000000Z/2020-01-23T22:30:00.000000Z",
                    "2020-01-23T23:00:00.000000Z/2020-01-23T23:30:00.000000Z",
                ],
            ],
            'duration 45 minutes and stepping every 60 minutes' => [
                45,
                60,
                [
                    "2020-01-23T00:00:00.000000Z/2020-01-23T00:45:00.000000Z",
                    "2020-01-23T01:00:00.000000Z/2020-01-23T01:45:00.000000Z",
                    "2020-01-23T02:00:00.000000Z/2020-01-23T02:45:00.000000Z",
                    "2020-01-23T03:00:00.000000Z/2020-01-23T03:45:00.000000Z",
                    "2020-01-23T04:00:00.000000Z/2020-01-23T04:45:00.000000Z",
                    "2020-01-23T05:00:00.000000Z/2020-01-23T05:45:00.000000Z",
                    "2020-01-23T06:00:00.000000Z/2020-01-23T06:45:00.000000Z",
                    "2020-01-23T07:00:00.000000Z/2020-01-23T07:45:00.000000Z",
                    "2020-01-23T08:00:00.000000Z/2020-01-23T08:45:00.000000Z",
                    "2020-01-23T09:00:00.000000Z/2020-01-23T09:45:00.000000Z",
                    "2020-01-23T10:00:00.000000Z/2020-01-23T10:45:00.000000Z",
                    "2020-01-23T11:00:00.000000Z/2020-01-23T11:45:00.000000Z",
                    "2020-01-23T12:00:00.000000Z/2020-01-23T12:45:00.000000Z",
                    "2020-01-23T13:00:00.000000Z/2020-01-23T13:45:00.000000Z",
                    "2020-01-23T14:00:00.000000Z/2020-01-23T14:45:00.000000Z",
                    "2020-01-23T15:00:00.000000Z/2020-01-23T15:45:00.000000Z",
                    "2020-01-23T16:00:00.000000Z/2020-01-23T16:45:00.000000Z",
                    "2020-01-23T17:00:00.000000Z/2020-01-23T17:45:00.000000Z",
                    "2020-01-23T18:00:00.000000Z/2020-01-23T18:45:00.000000Z",
                    "2020-01-23T19:00:00.000000Z/2020-01-23T19:45:00.000000Z",
                    "2020-01-23T20:00:00.000000Z/2020-01-23T20:45:00.000000Z",
                    "2020-01-23T21:00:00.000000Z/2020-01-23T21:45:00.000000Z",
                    "2020-01-23T22:00:00.000000Z/2020-01-23T22:45:00.000000Z",
                    "2020-01-23T23:00:00.000000Z/2020-01-23T23:45:00.000000Z",
                ],
            ],
            'duration 50 minutes and stepping every 60 minutes' => [
                50,
                60,
                [
                    "2020-01-23T00:00:00.000000Z/2020-01-23T00:50:00.000000Z",
                    "2020-01-23T01:00:00.000000Z/2020-01-23T01:50:00.000000Z",
                    "2020-01-23T02:00:00.000000Z/2020-01-23T02:50:00.000000Z",
                    "2020-01-23T03:00:00.000000Z/2020-01-23T03:50:00.000000Z",
                    "2020-01-23T04:00:00.000000Z/2020-01-23T04:50:00.000000Z",
                    "2020-01-23T05:00:00.000000Z/2020-01-23T05:50:00.000000Z",
                    "2020-01-23T06:00:00.000000Z/2020-01-23T06:50:00.000000Z",
                    "2020-01-23T07:00:00.000000Z/2020-01-23T07:50:00.000000Z",
                    "2020-01-23T08:00:00.000000Z/2020-01-23T08:50:00.000000Z",
                    "2020-01-23T09:00:00.000000Z/2020-01-23T09:50:00.000000Z",
                    "2020-01-23T10:00:00.000000Z/2020-01-23T10:50:00.000000Z",
                    "2020-01-23T11:00:00.000000Z/2020-01-23T11:50:00.000000Z",
                    "2020-01-23T12:00:00.000000Z/2020-01-23T12:50:00.000000Z",
                    "2020-01-23T13:00:00.000000Z/2020-01-23T13:50:00.000000Z",
                    "2020-01-23T14:00:00.000000Z/2020-01-23T14:50:00.000000Z",
                    "2020-01-23T15:00:00.000000Z/2020-01-23T15:50:00.000000Z",
                    "2020-01-23T16:00:00.000000Z/2020-01-23T16:50:00.000000Z",
                    "2020-01-23T17:00:00.000000Z/2020-01-23T17:50:00.000000Z",
                    "2020-01-23T18:00:00.000000Z/2020-01-23T18:50:00.000000Z",
                    "2020-01-23T19:00:00.000000Z/2020-01-23T19:50:00.000000Z",
                    "2020-01-23T20:00:00.000000Z/2020-01-23T20:50:00.000000Z",
                    "2020-01-23T21:00:00.000000Z/2020-01-23T21:50:00.000000Z",
                    "2020-01-23T22:00:00.000000Z/2020-01-23T22:50:00.000000Z",
                    "2020-01-23T23:00:00.000000Z/2020-01-23T23:50:00.000000Z",
                ],
            ],
            'duration 60 minutes and stepping every 60 minutes' => [
                60,
                60,
                [
                    "2020-01-23T00:00:00.000000Z/2020-01-23T01:00:00.000000Z",
                    "2020-01-23T01:00:00.000000Z/2020-01-23T02:00:00.000000Z",
                    "2020-01-23T02:00:00.000000Z/2020-01-23T03:00:00.000000Z",
                    "2020-01-23T03:00:00.000000Z/2020-01-23T04:00:00.000000Z",
                    "2020-01-23T04:00:00.000000Z/2020-01-23T05:00:00.000000Z",
                    "2020-01-23T05:00:00.000000Z/2020-01-23T06:00:00.000000Z",
                    "2020-01-23T06:00:00.000000Z/2020-01-23T07:00:00.000000Z",
                    "2020-01-23T07:00:00.000000Z/2020-01-23T08:00:00.000000Z",
                    "2020-01-23T08:00:00.000000Z/2020-01-23T09:00:00.000000Z",
                    "2020-01-23T09:00:00.000000Z/2020-01-23T10:00:00.000000Z",
                    "2020-01-23T10:00:00.000000Z/2020-01-23T11:00:00.000000Z",
                    "2020-01-23T11:00:00.000000Z/2020-01-23T12:00:00.000000Z",
                    "2020-01-23T12:00:00.000000Z/2020-01-23T13:00:00.000000Z",
                    "2020-01-23T13:00:00.000000Z/2020-01-23T14:00:00.000000Z",
                    "2020-01-23T14:00:00.000000Z/2020-01-23T15:00:00.000000Z",
                    "2020-01-23T15:00:00.000000Z/2020-01-23T16:00:00.000000Z",
                    "2020-01-23T16:00:00.000000Z/2020-01-23T17:00:00.000000Z",
                    "2020-01-23T17:00:00.000000Z/2020-01-23T18:00:00.000000Z",
                    "2020-01-23T18:00:00.000000Z/2020-01-23T19:00:00.000000Z",
                    "2020-01-23T19:00:00.000000Z/2020-01-23T20:00:00.000000Z",
                    "2020-01-23T20:00:00.000000Z/2020-01-23T21:00:00.000000Z",
                    "2020-01-23T21:00:00.000000Z/2020-01-23T22:00:00.000000Z",
                    "2020-01-23T22:00:00.000000Z/2020-01-23T23:00:00.000000Z",
                    "2020-01-23T23:00:00.000000Z/2020-01-24T00:00:00.000000Z",
                ],
            ],
        ];
    }
}
