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
}
