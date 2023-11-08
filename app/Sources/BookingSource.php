<?php

namespace App\Sources;

use App\Models\Booking;

interface BookingSource
{
    public function deleteBooking(int $bookingId): void;

    public function getBooking(int $bookingId): ?Booking;
}
