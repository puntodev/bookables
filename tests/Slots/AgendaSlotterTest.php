<?php

namespace Tests\Slots;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Puntodev\Bookables\Agenda\WeeklyScheduleAgenda;
use Puntodev\Bookables\Contracts\Agenda;
use Puntodev\Bookables\Slots\AgendaSlotter;
use Puntodev\Bookables\WeeklySchedule;
use Tests\Concerns\WithRangeAssertions;

class AgendaSlotterTest extends TestCase
{
    use WithRangeAssertions;

    private Agenda $agenda;

    protected function setUp(): void
    {
        parent::setUp();
        $weeklySchedule = WeeklySchedule::fromArray(WeeklySchedule::defaultWorkingHours());
        $this->agenda = new WeeklyScheduleAgenda($weeklySchedule);
    }

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function checkForDurationAndStepping($duration, $timeAfter, $timeBefore, $expected): void
    {
        $slotter = new AgendaSlotter($this->agenda, $duration, $timeAfter, $timeBefore);

        $result = $slotter->makeSlotsForDates(
            Carbon::parse('2020-01-23'),
            Carbon::parse('2020-01-25'),
        );

        $this->assertRanges($expected, $result);
    }

    public function dataProvider(): array
    {
        return [
            'duration 30 minutes' => [
                30,
                0,
                0,
                [
                    "2020-01-23T08:00:00.000000Z/2020-01-23T08:30:00.000000Z",
                    "2020-01-23T08:30:00.000000Z/2020-01-23T09:00:00.000000Z",
                    "2020-01-23T09:00:00.000000Z/2020-01-23T09:30:00.000000Z",
                    "2020-01-23T09:30:00.000000Z/2020-01-23T10:00:00.000000Z",
                    "2020-01-23T10:00:00.000000Z/2020-01-23T10:30:00.000000Z",
                    "2020-01-23T10:30:00.000000Z/2020-01-23T11:00:00.000000Z",
                    "2020-01-23T11:00:00.000000Z/2020-01-23T11:30:00.000000Z",
                    "2020-01-23T11:30:00.000000Z/2020-01-23T12:00:00.000000Z",
                    "2020-01-23T14:00:00.000000Z/2020-01-23T14:30:00.000000Z",
                    "2020-01-23T14:30:00.000000Z/2020-01-23T15:00:00.000000Z",
                    "2020-01-23T15:00:00.000000Z/2020-01-23T15:30:00.000000Z",
                    "2020-01-23T15:30:00.000000Z/2020-01-23T16:00:00.000000Z",
                    "2020-01-23T16:00:00.000000Z/2020-01-23T16:30:00.000000Z",
                    "2020-01-23T16:30:00.000000Z/2020-01-23T17:00:00.000000Z",
                    "2020-01-23T17:00:00.000000Z/2020-01-23T17:30:00.000000Z",
                    "2020-01-23T17:30:00.000000Z/2020-01-23T18:00:00.000000Z",

                    "2020-01-24T08:00:00.000000Z/2020-01-24T08:30:00.000000Z",
                    "2020-01-24T08:30:00.000000Z/2020-01-24T09:00:00.000000Z",
                    "2020-01-24T09:00:00.000000Z/2020-01-24T09:30:00.000000Z",
                    "2020-01-24T09:30:00.000000Z/2020-01-24T10:00:00.000000Z",
                    "2020-01-24T10:00:00.000000Z/2020-01-24T10:30:00.000000Z",
                    "2020-01-24T10:30:00.000000Z/2020-01-24T11:00:00.000000Z",
                    "2020-01-24T11:00:00.000000Z/2020-01-24T11:30:00.000000Z",
                    "2020-01-24T11:30:00.000000Z/2020-01-24T12:00:00.000000Z",
                    "2020-01-24T14:00:00.000000Z/2020-01-24T14:30:00.000000Z",
                    "2020-01-24T14:30:00.000000Z/2020-01-24T15:00:00.000000Z",
                    "2020-01-24T15:00:00.000000Z/2020-01-24T15:30:00.000000Z",
                    "2020-01-24T15:30:00.000000Z/2020-01-24T16:00:00.000000Z",
                    "2020-01-24T16:00:00.000000Z/2020-01-24T16:30:00.000000Z",
                    "2020-01-24T16:30:00.000000Z/2020-01-24T17:00:00.000000Z",
                    "2020-01-24T17:00:00.000000Z/2020-01-24T17:30:00.000000Z",
                    "2020-01-24T17:30:00.000000Z/2020-01-24T18:00:00.000000Z",

                    "2020-01-25T10:00:00.000000Z/2020-01-25T10:30:00.000000Z",
                    "2020-01-25T10:30:00.000000Z/2020-01-25T11:00:00.000000Z",
                    "2020-01-25T11:00:00.000000Z/2020-01-25T11:30:00.000000Z",
                    "2020-01-25T11:30:00.000000Z/2020-01-25T12:00:00.000000Z",
                ],
            ],
            'duration 45 minutes' => [
                45,
                0,
                0,
                [
                    "2020-01-23T08:00:00.000000Z/2020-01-23T08:45:00.000000Z",
                    "2020-01-23T08:45:00.000000Z/2020-01-23T09:30:00.000000Z",
                    "2020-01-23T09:30:00.000000Z/2020-01-23T10:15:00.000000Z",
                    "2020-01-23T10:15:00.000000Z/2020-01-23T11:00:00.000000Z",
                    "2020-01-23T11:00:00.000000Z/2020-01-23T11:45:00.000000Z",
                    "2020-01-23T14:00:00.000000Z/2020-01-23T14:45:00.000000Z",
                    "2020-01-23T14:45:00.000000Z/2020-01-23T15:30:00.000000Z",
                    "2020-01-23T15:30:00.000000Z/2020-01-23T16:15:00.000000Z",
                    "2020-01-23T16:15:00.000000Z/2020-01-23T17:00:00.000000Z",
                    "2020-01-23T17:00:00.000000Z/2020-01-23T17:45:00.000000Z",

                    "2020-01-24T08:00:00.000000Z/2020-01-24T08:45:00.000000Z",
                    "2020-01-24T08:45:00.000000Z/2020-01-24T09:30:00.000000Z",
                    "2020-01-24T09:30:00.000000Z/2020-01-24T10:15:00.000000Z",
                    "2020-01-24T10:15:00.000000Z/2020-01-24T11:00:00.000000Z",
                    "2020-01-24T11:00:00.000000Z/2020-01-24T11:45:00.000000Z",
                    "2020-01-24T14:00:00.000000Z/2020-01-24T14:45:00.000000Z",
                    "2020-01-24T14:45:00.000000Z/2020-01-24T15:30:00.000000Z",
                    "2020-01-24T15:30:00.000000Z/2020-01-24T16:15:00.000000Z",
                    "2020-01-24T16:15:00.000000Z/2020-01-24T17:00:00.000000Z",
                    "2020-01-24T17:00:00.000000Z/2020-01-24T17:45:00.000000Z",

                    "2020-01-25T10:00:00.000000Z/2020-01-25T10:45:00.000000Z",
                    "2020-01-25T10:45:00.000000Z/2020-01-25T11:30:00.000000Z",
                ],
            ],
            'duration 50 minutes' => [
                50,
                0,
                0,
                [
                    "2020-01-23T08:00:00.000000Z/2020-01-23T08:50:00.000000Z",
                    "2020-01-23T08:50:00.000000Z/2020-01-23T09:40:00.000000Z",
                    "2020-01-23T09:40:00.000000Z/2020-01-23T10:30:00.000000Z",
                    "2020-01-23T10:30:00.000000Z/2020-01-23T11:20:00.000000Z",
                    "2020-01-23T14:00:00.000000Z/2020-01-23T14:50:00.000000Z",
                    "2020-01-23T14:50:00.000000Z/2020-01-23T15:40:00.000000Z",
                    "2020-01-23T15:40:00.000000Z/2020-01-23T16:30:00.000000Z",
                    "2020-01-23T16:30:00.000000Z/2020-01-23T17:20:00.000000Z",

                    "2020-01-24T08:00:00.000000Z/2020-01-24T08:50:00.000000Z",
                    "2020-01-24T08:50:00.000000Z/2020-01-24T09:40:00.000000Z",
                    "2020-01-24T09:40:00.000000Z/2020-01-24T10:30:00.000000Z",
                    "2020-01-24T10:30:00.000000Z/2020-01-24T11:20:00.000000Z",
                    "2020-01-24T14:00:00.000000Z/2020-01-24T14:50:00.000000Z",
                    "2020-01-24T14:50:00.000000Z/2020-01-24T15:40:00.000000Z",
                    "2020-01-24T15:40:00.000000Z/2020-01-24T16:30:00.000000Z",
                    "2020-01-24T16:30:00.000000Z/2020-01-24T17:20:00.000000Z",

                    "2020-01-25T10:00:00.000000Z/2020-01-25T10:50:00.000000Z",
                    "2020-01-25T10:50:00.000000Z/2020-01-25T11:40:00.000000Z",
                ],
            ],
            'duration 50 minutes with time after 10 minutes' => [
                50,
                10,
                0,
                [
                    "2020-01-23T08:00:00.000000Z/2020-01-23T08:50:00.000000Z",
                    "2020-01-23T09:00:00.000000Z/2020-01-23T09:50:00.000000Z",
                    "2020-01-23T10:00:00.000000Z/2020-01-23T10:50:00.000000Z",
                    "2020-01-23T11:00:00.000000Z/2020-01-23T11:50:00.000000Z",
                    "2020-01-23T14:00:00.000000Z/2020-01-23T14:50:00.000000Z",
                    "2020-01-23T15:00:00.000000Z/2020-01-23T15:50:00.000000Z",
                    "2020-01-23T16:00:00.000000Z/2020-01-23T16:50:00.000000Z",
                    "2020-01-23T17:00:00.000000Z/2020-01-23T17:50:00.000000Z",

                    "2020-01-24T08:00:00.000000Z/2020-01-24T08:50:00.000000Z",
                    "2020-01-24T09:00:00.000000Z/2020-01-24T09:50:00.000000Z",
                    "2020-01-24T10:00:00.000000Z/2020-01-24T10:50:00.000000Z",
                    "2020-01-24T11:00:00.000000Z/2020-01-24T11:50:00.000000Z",
                    "2020-01-24T14:00:00.000000Z/2020-01-24T14:50:00.000000Z",
                    "2020-01-24T15:00:00.000000Z/2020-01-24T15:50:00.000000Z",
                    "2020-01-24T16:00:00.000000Z/2020-01-24T16:50:00.000000Z",
                    "2020-01-24T17:00:00.000000Z/2020-01-24T17:50:00.000000Z",

                    "2020-01-25T10:00:00.000000Z/2020-01-25T10:50:00.000000Z",
                    "2020-01-25T11:00:00.000000Z/2020-01-25T11:50:00.000000Z",
                ],
            ],
            'duration 50 minutes with time before 5 minutes and time after 5 minutes' => [
                50,
                10,
                5,
                [
                    "2020-01-23T08:00:00.000000Z/2020-01-23T08:50:00.000000Z",
                    "2020-01-23T09:00:00.000000Z/2020-01-23T09:50:00.000000Z",
                    "2020-01-23T10:00:00.000000Z/2020-01-23T10:50:00.000000Z",
                    "2020-01-23T11:00:00.000000Z/2020-01-23T11:50:00.000000Z",
                    "2020-01-23T14:00:00.000000Z/2020-01-23T14:50:00.000000Z",
                    "2020-01-23T15:00:00.000000Z/2020-01-23T15:50:00.000000Z",
                    "2020-01-23T16:00:00.000000Z/2020-01-23T16:50:00.000000Z",
                    "2020-01-23T17:00:00.000000Z/2020-01-23T17:50:00.000000Z",

                    "2020-01-24T08:00:00.000000Z/2020-01-24T08:50:00.000000Z",
                    "2020-01-24T09:00:00.000000Z/2020-01-24T09:50:00.000000Z",
                    "2020-01-24T10:00:00.000000Z/2020-01-24T10:50:00.000000Z",
                    "2020-01-24T11:00:00.000000Z/2020-01-24T11:50:00.000000Z",
                    "2020-01-24T14:00:00.000000Z/2020-01-24T14:50:00.000000Z",
                    "2020-01-24T15:00:00.000000Z/2020-01-24T15:50:00.000000Z",
                    "2020-01-24T16:00:00.000000Z/2020-01-24T16:50:00.000000Z",
                    "2020-01-24T17:00:00.000000Z/2020-01-24T17:50:00.000000Z",

                    "2020-01-25T10:00:00.000000Z/2020-01-25T10:50:00.000000Z",
                    "2020-01-25T11:00:00.000000Z/2020-01-25T11:50:00.000000Z",
                ],
            ],
            'duration 60 minutes' => [
                60,
                0,
                0,
                [
                    "2020-01-23T08:00:00.000000Z/2020-01-23T09:00:00.000000Z",
                    "2020-01-23T09:00:00.000000Z/2020-01-23T10:00:00.000000Z",
                    "2020-01-23T10:00:00.000000Z/2020-01-23T11:00:00.000000Z",
                    "2020-01-23T11:00:00.000000Z/2020-01-23T12:00:00.000000Z",
                    "2020-01-23T14:00:00.000000Z/2020-01-23T15:00:00.000000Z",
                    "2020-01-23T15:00:00.000000Z/2020-01-23T16:00:00.000000Z",
                    "2020-01-23T16:00:00.000000Z/2020-01-23T17:00:00.000000Z",
                    "2020-01-23T17:00:00.000000Z/2020-01-23T18:00:00.000000Z",

                    "2020-01-24T08:00:00.000000Z/2020-01-24T09:00:00.000000Z",
                    "2020-01-24T09:00:00.000000Z/2020-01-24T10:00:00.000000Z",
                    "2020-01-24T10:00:00.000000Z/2020-01-24T11:00:00.000000Z",
                    "2020-01-24T11:00:00.000000Z/2020-01-24T12:00:00.000000Z",
                    "2020-01-24T14:00:00.000000Z/2020-01-24T15:00:00.000000Z",
                    "2020-01-24T15:00:00.000000Z/2020-01-24T16:00:00.000000Z",
                    "2020-01-24T16:00:00.000000Z/2020-01-24T17:00:00.000000Z",
                    "2020-01-24T17:00:00.000000Z/2020-01-24T18:00:00.000000Z",

                    "2020-01-25T10:00:00.000000Z/2020-01-25T11:00:00.000000Z",
                    "2020-01-25T11:00:00.000000Z/2020-01-25T12:00:00.000000Z",
                ],
            ],
        ];
    }
}
