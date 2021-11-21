<?php

namespace Tests\Slots;

use Carbon\Carbon;
use League\Period\Period;
use PHPUnit\Framework\TestCase;
use Puntodev\Bookables\Slots\DurationAndStepTimeSlotter;
use Tests\Concerns\WithRangeAssertions;

class DurationAndStepTimeSlotterTest extends TestCase
{
    use WithRangeAssertions;

    /** @test */
    public function check_stepping_15_align()
    {
        $slotter = new DurationAndStepTimeSlotter(30, 15, true);

        $possibleRanges = $this->makeDateRange('2020-01-23T12:10:12Z', '2020-01-23T14:44:12Z');

        $result = $slotter->makeSlots($possibleRanges);

        $this->assertCount(8, $result);
        $this->assertRange(['2020-01-23T12:15:00+0000', '2020-01-23T12:45:00+0000'], $result[0]);
        $this->assertRange(['2020-01-23T12:30:00+0000', '2020-01-23T13:00:00+0000'], $result[1]);
        $this->assertRange(['2020-01-23T12:45:00+0000', '2020-01-23T13:15:00+0000'], $result[2]);
        $this->assertRange(['2020-01-23T13:00:00+0000', '2020-01-23T13:30:00+0000'], $result[3]);
        $this->assertRange(['2020-01-23T13:15:00+0000', '2020-01-23T13:45:00+0000'], $result[4]);
        $this->assertRange(['2020-01-23T13:30:00+0000', '2020-01-23T14:00:00+0000'], $result[5]);
        $this->assertRange(['2020-01-23T13:45:00+0000', '2020-01-23T14:15:00+0000'], $result[6]);
        $this->assertRange(['2020-01-23T14:00:00+0000', '2020-01-23T14:30:00+0000'], $result[7]);
    }

    /** @test */
    public function check_stepping_15_not_align()
    {
        $slotter = new DurationAndStepTimeSlotter(30, 15, false);

        $possibleRanges = $this->makeDateRange('2020-01-23T12:10:12Z', '2020-01-23T14:44:12Z');

        $result = $slotter->makeSlots($possibleRanges);

        $this->assertCount(9, $result);
        $this->assertRange(['2020-01-23T12:10:00+0000', '2020-01-23T12:40:00+0000'], $result[0]);
        $this->assertRange(['2020-01-23T12:25:00+0000', '2020-01-23T12:55:00+0000'], $result[1]);
        $this->assertRange(['2020-01-23T12:40:00+0000', '2020-01-23T13:10:00+0000'], $result[2]);
        $this->assertRange(['2020-01-23T12:55:00+0000', '2020-01-23T13:25:00+0000'], $result[3]);
        $this->assertRange(['2020-01-23T13:10:00+0000', '2020-01-23T13:40:00+0000'], $result[4]);
        $this->assertRange(['2020-01-23T13:25:00+0000', '2020-01-23T13:55:00+0000'], $result[5]);
        $this->assertRange(['2020-01-23T13:40:00+0000', '2020-01-23T14:10:00+0000'], $result[6]);
        $this->assertRange(['2020-01-23T13:55:00+0000', '2020-01-23T14:25:00+0000'], $result[7]);
        $this->assertRange(['2020-01-23T14:10:00+0000', '2020-01-23T14:40:00+0000'], $result[8]);
    }

    /**
     * @param string $from
     * @param string $to
     * @return array
     */
    private function makeDateRange(string $from, string $to): array
    {
        $possibleRanges = [
            Period::fromDatepoint(
                Carbon::parse($from),
                Carbon::parse($to)
            ),
        ];
        return $possibleRanges;
    }
}
