<?php


namespace Puntodev\Bookables\Agenda;


use Carbon\Carbon;
use League\Period\Exception;
use League\Period\Period;
use Puntodev\Bookables\Contracts\Agenda;

class SingleDateRangeAgenda implements Agenda
{
    private Carbon $start;

    private Carbon $end;

    /**
     * SingleDateRangeAgenda constructor.
     *
     * @param Carbon $start
     * @param Carbon $end
     */
    public function __construct(Carbon $start, Carbon $end)
    {
        $this->start = $start->clone();
        $this->end = $end->clone();
    }

    /**
     * @param Carbon $from
     * @param Carbon $to
     * @return array|Period[]
     * @throws Exception
     */
    public function possibleRanges(Carbon $from, Carbon $to): array
    {
        $maxStart = $from->max($this->start);
        $minEnd = $to->min($this->end);
        if ($maxStart->isAfter($minEnd)) {
            return [];
        }
        return [
            new Period($maxStart, $minEnd)
        ];
    }
}
