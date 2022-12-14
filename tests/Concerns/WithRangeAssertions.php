<?php

namespace Tests\Concerns;

use League\Period\Period;

trait WithRangeAssertions
{
    private function assertRanges(array $expected, array $actual): void
    {
        $this->assertEquals($expected, array_map(fn(Period $period) => $period->toIso8601(), $actual));
    }

    private function assertRange(string $expected, Period $actual): void
    {
        $this->assertEquals($expected, $actual->toIso8601());
    }
}
