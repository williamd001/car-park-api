<?php

namespace App\Repositories;

use App\Exceptions\BookingNotFoundException;
use App\Models\Booking;
use App\Sources\BookingSource;
use Carbon\Carbon;

class BookingRepository
{
    public function __construct(private BookingSource $bookingSource)
    {
    }

    /**
     * @throws BookingNotFoundException
     */
    public function getBooking(int $bookingId): Booking
    {
        return $this->bookingSource->getBooking($bookingId);
    }

    public function storeBooking(
        int    $customerId,
        int    $parkingSpaceId,
        Carbon $dateFrom,
        Carbon $dateTo,
        float  $priceGbp
    ): Booking
    {
        return $this->bookingSource->storeBooking(
            $customerId,
            $parkingSpaceId,
            $dateFrom,
            $dateTo,
            $priceGbp
        );
    }

    public function deleteBooking(int $bookingId): void
    {
        $this->bookingSource->deleteBooking($bookingId);
    }
}
