<?php

namespace Tests\Unit;

use App\Exceptions\FailedToCalculatePrice;
use App\Repositories\ParkingSpaceRepository;
use Carbon\Carbon;
use Database\Seeders\ParkingSpaceSeeder;
use Tests\TestCase;

/**
 * @property ParkingSpaceRepository $parkingSpaceRepository
 */
class ParkingSpaceRepositoryTest extends TestCase
{
   protected function setUp(): void
   {
       parent::setUp();

       $this->parkingSpaceRepository = app(ParkingSpaceRepository::class);
   }

    public function testExceptionThrownIfPricingCalculationFails(): void
    {
        $this->expectException(FailedToCalculatePrice::class);

        $this->parkingSpaceRepository->calculatePriceGbp(
            ParkingSpaceSeeder::NON_EXISTENT_PARKING_SPACE,
            new Carbon('2023-01-01'),
            new Carbon('2023-01-06')
        );
   }
}
