<?php

namespace App\Sources;

use App\Exceptions\BookingNotFoundException;
use App\Models\Booking;
use Carbon\Carbon;

interface BookingSource
{
    /**
     * @throws BookingNotFoundException
     */
    public function getBooking(int $bookingId): Booking;

    public function storeBooking(
        int $customerId,
        int $parkingSpaceId,
        Carbon $dateFrom,
        Carbon $dateTo,
        float $priceGbp
    ): Booking;

    public function deleteBooking(int $bookingId): void;
}
