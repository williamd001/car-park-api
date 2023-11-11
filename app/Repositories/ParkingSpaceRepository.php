<?php

namespace App\Repositories;

use App\Sources\ParkingSpaceSource;
use Carbon\Carbon;

class ParkingSpaceRepository
{
    public function __construct(private ParkingSpaceSource $parkingSpaceSource)
    {
    }

    public function getAvailableParkingSpaces(Carbon $dateFrom, Carbon $dateTo): array
    {
        return $this->parkingSpaceSource->getAvailableParkingSpaces($dateFrom, $dateTo);
    }

    public function isParkingSpaceAvailable(
        int $parkingSpaceId,
        Carbon $dateFrom,
        Carbon $dateTo,
        ?int $bookingIdToIgnore = null
    ): bool
    {
        return $this->parkingSpaceSource->isParkingSpaceAvailable(
            $parkingSpaceId,
            $dateFrom,
            $dateTo,
            $bookingIdToIgnore
        );
    }
}
