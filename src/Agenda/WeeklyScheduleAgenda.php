<?php

namespace Puntodev\Bookables\Agenda;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriodImmutable;
use Exception;
use League\Period\Period;
use Puntodev\Bookables\Contracts\Agenda;
use Puntodev\Bookables\Support\DateRangeGuard;
use Puntodev\Bookables\WeeklySchedule;

class WeeklyScheduleAgenda implements Agenda
{
    private WeeklySchedule $weeklySchedule;

    private int $maxDays;

    /**
     * $maxDays caps the size of the requested range to avoid unbounded range
     * generation; a value of 0 or less disables the limit.
     */
    public function __construct(WeeklySchedule $weeklySchedule, int $maxDays = 366)
    {
        $this->weeklySchedule = $weeklySchedule;
        $this->maxDays = $maxDays;
    }

    /**
     * @param CarbonInterface $from
     * @param CarbonInterface $to
     * @return array
     * @throws Exception
     */
    public function possibleRanges(CarbonInterface $from, CarbonInterface $to): array
    {
        DateRangeGuard::ensureWithinLimit($from, $to, $this->maxDays);

        $startOfDateFrom = $from->toImmutable()->startOfDay();
        $endOfDateTo = $to->toImmutable()->endOfDay();

        $period = new CarbonPeriodImmutable(
            $startOfDateFrom,
            new CarbonInterval('P1D'),
            $endOfDateTo,
        );

        $ret = [];
        foreach ($period as $date) {
            $carbon = CarbonImmutable::instance($date);
            foreach ($this->weeklySchedule->forDate($carbon) as $range) {
                $periodStart = $startOfDateFrom->max($carbon->setTimeFromTimeString($range['start']));
                $periodEnd = $endOfDateTo->min($carbon->setTimeFromTimeString($range['end']));
                if ($periodEnd->isAfter($periodStart)) {
                    $ret[] = Period::fromDate($periodStart, $periodEnd);
                }
            }
        }
        return $ret;
    }
}
