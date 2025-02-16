<?php


namespace Puntodev\Bookables\Contracts;



use Carbon\CarbonInterface;

interface TimeBookable
{
    public function available(CarbonInterface $start, CarbonInterface $end): array;

    public function unavailable(CarbonInterface $start, CarbonInterface $end): array;
}
