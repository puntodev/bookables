<?php

namespace Puntodev\Bookables\Support;

use Carbon\CarbonInterface;
use Puntodev\Bookables\Exceptions\DateRangeTooLargeException;

/**
 * Guards slot/range generation against unbounded date ranges, which would
 * otherwise let a caller exhaust memory and CPU (a denial-of-service vector).
 */
final class DateRangeGuard
{
    /**
     * @throws DateRangeTooLargeException when the span between $from and $to
     *         exceeds $maxDays. A $maxDays of 0 or less disables the check.
     */
    public static function ensureWithinLimit(CarbonInterface $from, CarbonInterface $to, int $maxDays): void
    {
        if ($maxDays <= 0) {
            return;
        }

        $days = $from->toImmutable()->startOfDay()
            ->diffInDays($to->toImmutable()->startOfDay(), true);

        if ($days > $maxDays) {
            throw new DateRangeTooLargeException(sprintf(
                'Requested date range spans %d days, which exceeds the maximum of %d days.',
                (int)ceil($days),
                $maxDays,
            ));
        }
    }
}
