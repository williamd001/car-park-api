<?php

namespace App\Sources;

use App\Exceptions\BookingNotFoundException;
use App\Models\Booking;
use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;

interface BookingSource
{
    public const VALID_UPDATE_FIELDS = [
        'parking_space_id' => 'int',
        'date_from' => 'string',
        'date_to' => 'string',
        'price_gbp' => 'float',
    ];

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

    public function updateBooking(
        int $bookingId,
        #[ArrayShape(self::VALID_UPDATE_FIELDS)] array $updateData
    ): Booking;

    public function deleteBooking(int $bookingId): void;
}
