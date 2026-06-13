<?php


namespace Puntodev\Bookables\Slots;


use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriodImmutable;
use InvalidArgumentException;
use League\Period\Period;
use Puntodev\Bookables\Contracts\TimeSlotter;
use Puntodev\Bookables\Support\DateRangeGuard;

/**
 * TimeSlotter that creates slots for the whole day for each date within the requested range
 * and creates slots for those dates using a particular $duration and $step.
 *
 * $maxDays caps the size of the requested range to avoid unbounded slot
 * generation; a value of 0 or less disables the limit.
 */
readonly class DaySlotter implements TimeSlotter
{
    public function __construct(private int $duration, private int $step, private int $maxDays = 366)
    {
        if ($this->duration <= 0) {
            throw new InvalidArgumentException('Duration must be a positive number of minutes.');
        }
        if ($this->step <= 0) {
            throw new InvalidArgumentException('Step must be a positive number of minutes.');
        }
    }

    public function makeSlotsForDates(CarbonInterface $startDate, CarbonInterface $endDate): array
    {
        DateRangeGuard::ensureWithinLimit($startDate, $endDate, $this->maxDays);

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
