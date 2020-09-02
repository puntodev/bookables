<?php

namespace Puntodev\Bookables\Agenda;

use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use League\Period\Exception;
use League\Period\Period;
use Puntodev\Bookables\Contracts\Agenda;
use Puntodev\Bookables\WeeklySchedule;

class WeeklyScheduleAgenda implements Agenda
{
    /** @var WeeklySchedule */
    private WeeklySchedule $weeklySchedule;

    /**
     * WeeklyScheduleAgenda constructor.
     *
     * @param WeeklySchedule $weeklySchedule
     */
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
        $period = new DatePeriod($from, new DateInterval('P1D'), $to);
        $ret = [];
        foreach ($period as $date) {
            foreach ($this->weeklySchedule->forDate($date) as $range) {
                $periodStart = $from->max(Carbon::instance($date)->setTimeFromTimeString($range['start']));
                $periodEnd = $to->min(Carbon::instance($date)->setTimeFromTimeString($range['end']));
                if ($periodEnd->isAfter($periodStart)) {
                    $ret[] = new Period($periodStart, $periodEnd);
                }
            }
        }
        return $ret;
    }
}
