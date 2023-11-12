<?php

namespace App\Sources;

use App\Exceptions\FailedToCalculatePrice;
use Carbon\Carbon;
use Illuminate\Database\DatabaseManager;

class ParkingSpaceMySQLSource implements ParkingSpaceSource
{
    public function __construct(private DatabaseManager $database)
    {
    }

    public function getAvailableParkingSpaces(Carbon $dateFrom, Carbon $dateTo): array
    {
        return $this->database
            ->select(
                '
            SELECT
                ps.id AS parking_space_id,
                l.name AS location_name,
                ROUND(:duration_in_days * l.default_price_per_day_gbp, 2) AS total_price_gbp
            FROM parking_spaces AS ps
                INNER JOIN locations AS l ON ps.location_id = l.id
                LEFT JOIN bookings AS b ON ps.id = b.parking_space_id
            WHERE
                b.date_from IS NULL
                OR b.date_to < :date_from
                OR b.date_from > :date_to
                ',
                [
                    'date_from' => $dateFrom->toDateString(),
                    'date_to' => $dateTo->toDateString(),
                    'duration_in_days' => $dateTo->diffInDays($dateFrom)
                ]
            );
    }

    public function isParkingSpaceAvailable(
        int    $parkingSpaceId,
        Carbon $dateFrom,
        Carbon $dateTo,
        ?int $bookingIdToIgnore = null
    ): bool
    {
        $query =   '
            SELECT
                b.id AS parking_space_id
            FROM bookings AS b
                INNER JOIN parking_spaces AS ps ON b.parking_space_id = ps.id
            WHERE
                ps.id = :parking_space_id
                AND b.date_from <= :date_to
                AND b.date_to >= :date_from
                ';

        $bindings = [
            'parking_space_id' => $parkingSpaceId,
            'date_from' => $dateFrom->toDateString(),
            'date_to' => $dateTo->toDateString(),
        ];

        if ($bookingIdToIgnore !== null) {
            $query .= 'AND b.id <> :booking_id_to_ignore';

            $bindings['booking_id_to_ignore'] = $bookingIdToIgnore;
        }

        $results = $this->database
            ->select(
              $query,
                $bindings
            );

        return count($results) === 0;
    }

    public function calculatePriceGbp(int $parkingSpaceId, Carbon $dateFrom, Carbon $dateTo): float
    {
        $result = $this->database
            ->select(
                '
                SELECT ROUND(:duration_in_days * l.default_price_per_day_gbp, 2) AS total_price_gbp
                From parking_spaces AS ps
                INNER JOIN locations AS l ON ps.location_id = l.id
                WHERE ps.id = :parking_space_id
                ',
                [
                    'duration_in_days' => $dateTo->clone()->addDay()->startOfDay()->diffInDays($dateFrom->clone()->startOfDay()),
                    'parking_space_id' => $parkingSpaceId
                ]
            );

        if (! isset($result[0]->total_price_gbp)) {
            throw new FailedToCalculatePrice(
                $parkingSpaceId,
                $dateFrom,
                $dateTo
            );
        }

        return $result[0]->total_price_gbp;
    }
}
