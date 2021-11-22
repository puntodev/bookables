<?php

namespace Puntodev\Bookables\Contracts;

use Carbon\Carbon;

interface TimeSlotter
{
    /**
     * Creates time slots for dates in the range between $startDate and $endDate
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function makeSlotsForDates(Carbon $startDate, Carbon $endDate): array;
}
