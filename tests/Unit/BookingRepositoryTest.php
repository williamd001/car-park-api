<?php

namespace Tests\Unit;

use App\Exceptions\BookingNotFoundException;
use App\Repositories\BookingRepository;
use Database\Seeders\BookingSeeder;
use Tests\TestCase;

/**
 * @property BookingRepository $bookingRepository
 */
class BookingRepositoryTest extends TestCase
{
   protected function setUp(): void
   {
       parent::setUp();

       $this->bookingRepository = app(BookingRepository::class);
   }

    public function testExceptionThrownIfBookingIsNotFound(): void
    {
        $this->expectException(BookingNotFoundException::class);

        $this->bookingRepository->getBooking(BookingSeeder::NON_EXISTENT_BOOKING);
   }
}
