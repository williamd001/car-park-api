<?php

namespace App\Sources;

use Carbon\Carbon;

interface ParkingSpaceSource
{
    public function getAvailableParkingSpaces(Carbon $dateFrom, Carbon $dateTo): array;

    public function isParkingSpaceAvailable(
        int    $parkingSpaceId,
        Carbon $dateFrom,
        Carbon $dateTo
    ): bool;

    public function calculatePriceGbp(
        int $parkingSpaceId,
        Carbon $dateFrom,
        Carbon $dateTo
    ): float;
}
