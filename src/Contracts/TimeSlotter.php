<?php

namespace Puntodev\Bookables\Contracts;


use Carbon\CarbonInterface;

interface TimeSlotter
{
    /**
     * Creates time slots for dates in the range between $startDate and $endDate
     * @param CarbonInterface $startDate
     * @param CarbonInterface $endDate
     * @return array
     */
    public function makeSlotsForDates(CarbonInterface $startDate, CarbonInterface $endDate): array;
}
