<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class BookingNotFoundException extends Exception
{
    public function __construct(
        int        $bookingId,
        int        $code = 0,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            'Could not retrieve booking with id: ' . $bookingId,
            $code,
            $previous
        );
    }
}
