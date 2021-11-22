<?php


namespace Puntodev\Bookables\Contracts;


use Carbon\Carbon;

interface TimeBookable
{
    public function available(Carbon $start, Carbon $end): array;

    public function unavailable(Carbon $start, Carbon $end): array;
}
