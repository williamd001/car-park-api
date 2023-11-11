<?php

namespace App\Repositories;

use App\Exceptions\BookingNotFoundException;
use App\Exceptions\InvalidFieldForUpdate;
use App\Models\Booking;
use App\Sources\BookingSource;
use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;

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

    public function updateBooking(
        int $bookingId,
        #[ArrayShape(BookingSource::VALID_UPDATE_FIELDS)] array $updateData
    ): Booking
    {
        foreach ($updateData as $field => $value) {
            if (! isset(BookingSource::VALID_UPDATE_FIELDS[$field])) {
                throw new InvalidFieldForUpdate($field, 'booking');
            }
        }

        return $this->bookingSource->updateBooking($bookingId, $updateData);
    }

    public function deleteBooking(int $bookingId): void
    {
        $this->bookingSource->deleteBooking($bookingId);
    }
}
