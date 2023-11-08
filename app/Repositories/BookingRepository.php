<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Sources\BookingSource;

class BookingRepository
{
    public function __construct(private BookingSource $bookingSource)
    {
    }

    public function deleteBooking(int $bookingId): void
    {
        $this->bookingSource->deleteBooking($bookingId);
    }

    public function getBooking(int $bookingId): ?Booking
    {
        return $this->bookingSource->getBooking($bookingId);
    }
}
