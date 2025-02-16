<?php


namespace Puntodev\Bookables\Slots;


use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriodImmutable;
use League\Period\Period;
use Puntodev\Bookables\Contracts\TimeSlotter;

/**
 * TimeSlotter that creates slots for the whole day for each date within the requested range
 * and creates slots for those dates using a particular $duration and $step.
 */
readonly class DaySlotter implements TimeSlotter
{
    public function __construct(private int $duration, private int $step)
    {
    }

    public function makeSlotsForDates(CarbonInterface $startDate, CarbonInterface $endDate): array
    {
        $startOfDateFrom = $startDate->toImmutable()->startOfDay();
        $endOfDateTo = $endDate->toImmutable()->endOfDay();

        $period = new CarbonPeriodImmutable(
            $startOfDateFrom,
            new CarbonInterval('P1D'),
            $endOfDateTo,
        );

        $ret = [];
        /** @var CarbonInterface $date */
        foreach ($period as $date) {
            $dateRange = new CarbonPeriodImmutable(
                $date,
                new CarbonInterval("PT{$this->step}M"),
                $date->addDay()->subMinutes($this->duration),
            );

            foreach ($dateRange as $slot) {
                $ret[] = Period::after($slot, ($this->duration) . 'minutes');
            }
        }

        return $ret;
    }
}
