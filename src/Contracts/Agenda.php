<?php


namespace Puntodev\Bookables\Contracts;


use Carbon\Carbon;

interface Agenda
{
    public function possibleRanges(Carbon $from, Carbon $to): array;
}
