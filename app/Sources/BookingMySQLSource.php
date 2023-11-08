<?php

namespace App\Sources;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Database\DatabaseManager;

class BookingMySQLSource implements BookingSource
{
    public function __construct(private DatabaseManager $database)
    {
    }

    public function deleteBooking(int $bookingId): void
    {
        $this->database
            ->table('parking_space_bookings')
            ->delete($bookingId);
    }

    public function getBooking(int $bookingId): ?Booking
    {
        $booking = $this->database
            ->table('parking_space_bookings')
            ->where('id', '=', $bookingId)
            ->first(
                [
                    'id',
                    'customer_id',
                    'parking_space_id',
                    'date_from',
                    'date_to',
                    'price_gbp',
                ]
            );

        if (empty($booking)) {
            return null;
        }

        return new Booking(
            $booking->id,
            $booking->customer_id,
            $booking->parking_space_id,
            new Carbon($booking->date_from),
            new Carbon($booking->date_to),
            $booking->price_gbp
        );
    }
}
