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

    private function assertRange(array $expected, Period $actual)
    {
        $this->assertEquals($expected[0], $actual->getStartDate()->format(DateTimeInterface::ISO8601));
        $this->assertEquals($expected[1], $actual->getEndDate()->format(DateTimeInterface::ISO8601));
    }
}
