<?php


namespace Puntodev\Bookables\Slots;


use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use League\Period\Period;
use Puntodev\Bookables\Contracts\Agenda;
use Puntodev\Bookables\Contracts\TimeSlotter;

/**
 * TimeSlotter that uses an $agenda to obtain the date ranges for the required dates
 * and creates slots for those ranges using a particular $duration and $step
 */
class AgendaSlotter implements TimeSlotter
{
    public function __construct(private Agenda $agenda, private int $duration, private int $step)
    {
    }

    public function makeSlotsForDates(Carbon $startDate, Carbon $endDate): array
    {
        $ranges = $this->agenda->possibleRanges($startDate, $endDate);

        $uniqueDates = array_values(array_unique(array_map(fn(Period $period) => $period->getStartDate()->getDay(), $ranges)));

        $ret = [];
        /** @var Period $range */
        foreach ($uniqueDates as $range) {
            $dateRange = new DatePeriod(
                Carbon::instance($range->getStartDate()),
                new DateInterval("PT{$this->step}M"),
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
