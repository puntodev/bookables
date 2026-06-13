<?php


namespace Puntodev\Bookables\Slots;


use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriodImmutable;
use InvalidArgumentException;
use League\Period\Period;
use Puntodev\Bookables\Contracts\Agenda;
use Puntodev\Bookables\Contracts\TimeSlotter;
use Puntodev\Bookables\Support\DateRangeGuard;

/**
 * TimeSlotter that uses an $agenda to get the date ranges for the required dates
 * and creates slots for those ranges using a particular $duration.
 * If $timeBefore is specified, it ensures that before each appointment there's at least $timeBefore minutes.
 * If $timeAfter is specified, it ensures that after each appointment there's at least $timeAfter minutes.
 *
 * $maxDays caps the size of the requested range to avoid unbounded slot
 * generation; a value of 0 or less disables the limit.
 */
readonly class AgendaSlotter implements TimeSlotter
{
    public function __construct(private Agenda $agenda, private int $duration, private int $timeAfter = 0, private int $timeBefore = 0, private int $maxDays = 366)
    {
        if ($this->duration <= 0) {
            throw new InvalidArgumentException('Duration must be a positive number of minutes.');
        }
        if ($this->timeAfter < 0) {
            throw new InvalidArgumentException('Time after must not be negative.');
        }
        if ($this->timeBefore < 0) {
            throw new InvalidArgumentException('Time before must not be negative.');
        }
    }

    public function makeSlotsForDates(CarbonInterface $startDate, CarbonInterface $endDate): array
    {
        DateRangeGuard::ensureWithinLimit($startDate, $endDate, $this->maxDays);

        $ranges = $this->agenda->possibleRanges($startDate, $endDate);

        $ret = [];

        $interval = $this->duration + max($this->timeAfter, $this->timeBefore);

        /** @var Period $range */
        foreach ($ranges as $range) {
            $dateRange = new CarbonPeriodImmutable(
                CarbonImmutable::instance($range->startDate),
                new CarbonInterval("PT{$interval}M"),
                CarbonImmutable::instance($range->endDate)->subMinutes($this->duration),
            );

            foreach ($dateRange as $slot) {
                $ret[] = Period::after($slot, ($this->duration) . 'minutes');
            }
        }

        return $ret;
    }
}
