<?php

namespace App\Sources;

use App\Exceptions\BookingNotFoundException;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Database\DatabaseManager;
use JetBrains\PhpStorm\ArrayShape;

class BookingMySQLSource implements BookingSource
{
    public function __construct(private DatabaseManager $database)
    {
    }

    /**
     * @throws BookingNotFoundException
     */
    public function getBooking(int $bookingId): Booking
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
                    'created_at',
                    'updated_at'
                ]
            );

        if (empty($booking)) {
            throw new BookingNotFoundException($bookingId);
        }

        return new Booking(
            $booking->id,
            $booking->customer_id,
            $booking->parking_space_id,
            new Carbon($booking->date_from),
            new Carbon($booking->date_to),
            $booking->price_gbp,
            new Carbon($booking->created_at),
            new Carbon($booking->updated_at),
        );
    }

    public function storeBooking(
        int    $customerId,
        int    $parkingSpaceId,
        Carbon $dateFrom,
        Carbon $dateTo,
        float  $priceGbp
    ): Booking
    {
        $currentDate = new Carbon();

        $bookingId = $this->database
            ->table('parking_space_bookings')
            ->insertGetId(
                [
                    'customer_id' => $customerId,
                    'parking_space_id' => $parkingSpaceId,
                    'date_from' => $dateFrom->toDateString(),
                    'date_to' => $dateTo->toDateString(),
                    'price_gbp' => $priceGbp,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ]
            );

        return $this->getBooking($bookingId);
    }

    public function deleteBooking(int $bookingId): void
    {
        $this->database
            ->table('parking_space_bookings')
            ->delete($bookingId);
    }

    public function updateBooking(
        int $bookingId,
        #[ArrayShape(self::VALID_UPDATE_FIELDS)] array $updateData): Booking
    {
        $updateData['updated_at'] = new Carbon();

        $this->database
            ->table('parking_space_bookings')
            ->where('id', '=', $bookingId)
            ->update($updateData);

        return $this->getBooking($bookingId);
    }
}
