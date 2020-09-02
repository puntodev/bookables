<?php

namespace Puntodev\Bookables\Contracts;

use Exception;

interface TimeSlotter
{
    /**
     * @param array $ranges
     * @return array
     * @throws Exception
     */
    public function makeSlots(array $ranges): array;
}
