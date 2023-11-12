<?php


use App\Exceptions\InvalidFieldForUpdate;
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

    public function testExceptionThrownWhenUpdatingAnInvalidField(): void
    {
        $this->expectException(InvalidFieldForUpdate::class);

        $this->bookingRepository->updateBooking(
            BookingSeeder::USER_1_BOOKING_1,
            [
                'created_at' => '2023-01-01',
            ]
        );
    }
}
