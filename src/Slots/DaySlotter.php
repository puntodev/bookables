<?php


namespace Puntodev\Bookables\Slots;


use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use League\Period\Period;
use Puntodev\Bookables\Contracts\TimeSlotter;

/**
 * TimeSlotter that creates slots for the whole day for each date within the requested range
 * and creates slots for those dates using a particular $duration and $step.
 */
class DaySlotter implements TimeSlotter
{
    public function __construct(private int $duration, private int $step)
    {
    }

    public function makeSlotsForDates(Carbon $startDate, Carbon $endDate): array
    {
        $startOfDateFrom = $startDate->clone()->startOfDay();
        $endOfDateTo = $endDate->clone()->endOfDay();

        $period = new CarbonPeriod(
            $startOfDateFrom,
            new CarbonInterval('P1D'),
            $endOfDateTo,
        );

        $ret = [];
        /** @var Carbon $date */
        foreach ($period as $date) {
            $dateRange = new CarbonPeriod(
                $date,
                new CarbonInterval("PT{$this->step}M"),
                $date->clone()->addDay()->subMinutes($this->duration),
            );

            foreach ($dateRange as $slot) {
                $ret[] = Period::after($slot, ($this->duration) . 'minutes');
            }
        }

        return $ret;
    }
}
