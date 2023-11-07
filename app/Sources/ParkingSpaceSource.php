<?php

namespace App\Sources;

use Carbon\Carbon;

interface ParkingSpaceSource
{
    public function getAvailableParkingSpaces(Carbon $dateFrom, Carbon $dateTo): array;
}
