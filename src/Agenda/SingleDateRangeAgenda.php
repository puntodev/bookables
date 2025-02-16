<?php


namespace Puntodev\Bookables\Agenda;


use Carbon\CarbonInterface;
use Exception;
use League\Period\Period;
use Puntodev\Bookables\Contracts\Agenda;

class SingleDateRangeAgenda implements Agenda
{
    private CarbonInterface $start;

    private CarbonInterface $end;

    /**
     * SingleDateRangeAgenda constructor.
     *
     * @param CarbonInterface $start
     * @param CarbonInterface $end
     */
    public function __construct(CarbonInterface $start, CarbonInterface $end)
    {
        $this->start = $start->toImmutable();
        $this->end = $end->toImmutable();
    }

    /**
     * @param CarbonInterface $from
     * @param CarbonInterface $to
     * @return array|Period[]
     * @throws Exception
     */
    public function possibleRanges(CarbonInterface $from, CarbonInterface $to): array
    {
        $maxStart = $from->toImmutable()->max($this->start);
        $minEnd = $to->toImmutable()->min($this->end);
        if ($maxStart->isAfter($minEnd)) {
            return [];
        }
        return [
            Period::fromDate($maxStart, $minEnd)
        ];
    }
}
