<?php


namespace Puntodev\Bookables\Contracts;


use Carbon\CarbonInterface;

interface Agenda
{
    public function possibleRanges(CarbonInterface $from, CarbonInterface $to): array;
}
