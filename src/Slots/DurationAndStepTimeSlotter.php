<?php


namespace Puntodev\Bookables\Slots;


use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateInterval;
use Exception;
use League\Period\Period;
use Puntodev\Bookables\Contracts\TimeSlotter;

class DurationAndStepTimeSlotter implements TimeSlotter
{
    /** @var int */
    private int $duration;

    /** @var int */
    private int $step;

    /**
     * TimeSlotter constructor.
     * @param $duration
     * @param $step
     */
    public function __construct($duration, $step)
    {
        $this->duration = $duration;
        $this->step = $step;
    }

    /**
     * @param array $ranges
     * @return array
     * @throws Exception
     */
    public function makeSlots(array $ranges): array
    {
        $ret = [];
        /** @var Period $range */
        foreach ($ranges as $range) {

            // Find the start of the first slot, aligned based on the stepping
            $rangeStart = Carbon::instance($range->getStartDate())->startOfMinute();
            while ($rangeStart->minute % $this->step !== 0)
                $rangeStart->addMinute();

            // Now create the slots by iteration over the period
            $dateRange = new CarbonPeriod(
                $rangeStart,
                new DateInterval('PT' . ($this->step) . 'M'),
                Carbon::instance($range->getEndDate())->subMinutes($this->duration),
            );
            $dateRange->excludeStartDate(false);
            foreach ($dateRange as $slot) {
                $ret[] = Period::after($slot, ($this->duration) . 'minutes');
            }
        }
        return $ret;
    }
}
