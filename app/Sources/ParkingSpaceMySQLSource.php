<?php

namespace App\Sources;

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
                LEFT JOIN parking_space_bookings AS psb ON ps.id = psb.parking_space_id
            WHERE
                psb.date_from IS NULL
                OR psb.date_to < :date_from
                OR psb.date_from > :date_to
                ',
                [
                    'date_from' => $dateFrom->toDateString(),
                    'date_to' => $dateTo->toDateString(),
                    'duration_in_days' => $dateTo->diffInDays($dateFrom)
                ]
            );
    }
}
