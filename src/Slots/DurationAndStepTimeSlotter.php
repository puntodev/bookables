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
    /**
     * TimeSlotter constructor.
     * @param int $duration Slot duration (in minutes)
     * @param int $step Frequency of slot starts (in minutes)
     * @param bool $alignToStepping Whether to align to minutes multiple of stepping or not
     */
    public function __construct(private int $duration, private int $step, private bool $alignToStepping = true)
    {
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

            // Find the start of the first slot, aligned (or not) based on the stepping
            $rangeStart = Carbon::instance($range->getStartDate())->startOfMinute();
            while ($this->alignToStepping && $rangeStart->minute % $this->step !== 0)
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
