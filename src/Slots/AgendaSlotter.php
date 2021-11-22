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
 * and creates slots for those ranges using a particular $duration
 */
class AgendaSlotter implements TimeSlotter
{
    public function __construct(private Agenda $agenda, private int $duration)
    {
    }

    public function makeSlotsForDates(Carbon $startDate, Carbon $endDate): array
    {
        $ranges = $this->agenda->possibleRanges($startDate, $endDate);

        $ret = [];
        /** @var Period $range */
        foreach ($ranges as $range) {
            $dateRange = new CarbonPeriod(
                Carbon::instance($range->getStartDate()),
                new CarbonInterval("PT{$this->duration}M"),
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
