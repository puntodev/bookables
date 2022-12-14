<?php

namespace Puntodev\Bookables\Agenda;

use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Exception;
use League\Period\Period;
use Puntodev\Bookables\Contracts\Agenda;
use Puntodev\Bookables\WeeklySchedule;

class WeeklyScheduleAgenda implements Agenda
{
    private WeeklySchedule $weeklySchedule;

    public function __construct(WeeklySchedule $weeklySchedule)
    {
        $this->weeklySchedule = $weeklySchedule;
    }

    /**
     * @param Carbon $from
     * @param Carbon $to
     * @return array
     * @throws Exception
     */
    public function possibleRanges(Carbon $from, Carbon $to): array
    {
        $startOfDateFrom = $from->clone()->startOfDay();
        $endOfDateTo = $to->clone()->endOfDay();

        $period = new DatePeriod(
            $startOfDateFrom,
            new DateInterval('P1D'),
            $endOfDateTo,
        );

        $ret = [];
        foreach ($period as $date) {
            $carbon = Carbon::instance($date);
            foreach ($this->weeklySchedule->forDate($carbon) as $range) {
                $periodStart = $startOfDateFrom->max($carbon->clone()->setTimeFromTimeString($range['start']));
                $periodEnd = $endOfDateTo->min($carbon->clone()->setTimeFromTimeString($range['end']));
                if ($periodEnd->isAfter($periodStart)) {
                    $ret[] = Period::fromDate($periodStart, $periodEnd);
                }
            }
        }
        return $ret;
    }
}
