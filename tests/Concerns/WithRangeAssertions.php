<?php

namespace Tests\Concerns;

use DateTimeInterface;
use League\Period\Period;

trait WithRangeAssertions
{
    private function assertRanges(array $expected, array $actual)
    {
        $this->assertCount(count($expected), $actual);
        foreach ($actual as $index => $item) {
            $this->assertRange($expected[$index], $item);
        }
    }

    private function assertRange(string $expected, Period $actual)
    {
        $this->assertEquals($expected, $actual->toIso8601());
    }
}
