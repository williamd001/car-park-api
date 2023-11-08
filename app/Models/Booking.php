<?php

namespace App\Models;

use Carbon\Carbon;

class Booking
{
    public function __construct(
        private int    $id,
        private int    $customerId,
        private int    $parkingSpaceId,
        private Carbon $dateFrom,
        private Carbon $dateTo,
        private float  $priceGbp
    )
    {
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }
}
