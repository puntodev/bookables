<?php

namespace Puntodev\Bookables\Exceptions;

use InvalidArgumentException;

/**
 * Thrown when the requested date range exceeds the maximum number of days a
 * slotter or agenda is allowed to process, preventing unbounded slot
 * generation (a denial-of-service vector).
 */
class DateRangeTooLargeException extends InvalidArgumentException
{
}
