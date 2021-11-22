<?php


namespace Puntodev\Bookables\Slots;


use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use League\Period\Period;
use Puntodev\Bookables\Contracts\Agenda;
use Puntodev\Bookables\Contracts\TimeSlotter;

/**
 * TimeSlotter that uses an $agenda to obtain the date ranges for the required dates
 * and creates slots for those ranges using a particular $duration.
 * If $timeBefore is specified it ensures that before each appointment there's at least $timeBefore minutes.
 * If $timeAfter is specified it ensures that after each appointment there's at least $timeAfter minutes.
 */
class AgendaSlotter implements TimeSlotter
{
    public function __construct(private Agenda $agenda, private int $duration, private int $timeAfter = 0, private int $timeBefore = 0)
    {
    }

    public function makeSlotsForDates(Carbon $startDate, Carbon $endDate): array
    {
        $ranges = $this->agenda->possibleRanges($startDate, $endDate);

        $ret = [];
        /** @var Period $range */
        $interval = $this->duration + max($this->timeAfter, $this->timeBefore);

        foreach ($ranges as $range) {
            $dateRange = new CarbonPeriod(
                Carbon::instance($range->getStartDate()),
                new CarbonInterval("PT{$interval}M"),
                Carbon::instance($range->getEndDate())->subMinutes($this->duration),
            );

            foreach ($dateRange as $slot) {
                $period = Period::after($slot, ($this->duration) . 'minutes');
                foreach ($ranges as $range) {
                    if ($range->contains($period)) {
                        $ret[] = $period;
                        break;
                    }
                }
            }
        }
        return $ret;
    }
}
