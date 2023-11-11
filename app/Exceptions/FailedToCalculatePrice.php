<?php

namespace App\Exceptions;

use Carbon\Carbon;
use Exception;
use Throwable;

class FailedToCalculatePrice extends Exception
{
    public function __construct(
        int        $parkingSpaceId,
        Carbon     $dateFrom,
        Carbon     $dateTo,
        int        $code = 0,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            sprintf(
                "Could not calculate price for parking space %d. date_from %s. date_to %s",
                $parkingSpaceId,
                $dateFrom->toDateString(),
                $dateTo->toDateString()
            ),
            $code,
            $previous
        );
    }
}
