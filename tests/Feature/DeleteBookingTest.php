<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\BookingSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\UserSeeder;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteBookingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function testForbiddenIfAuthTokenIsNotProvided(): void
    {
        $this->json(
            'DELETE',
            '/api/users/' . UserSeeder::USER_1 . '/bookings/' . BookingSeeder::USER_1_BOOKING_1
        )
            ->assertUnauthorized();
    }

    public function testDeleteBooking(): void
    {
        Sanctum::actingAs(User::find(UserSeeder::USER_1));

        $this->assertDatabaseHas(
            'parking_space_bookings',
            [
                'id' => BookingSeeder::USER_1_BOOKING_1,
                'user_id' => UserSeeder::USER_1,
            ]
        );

        $this->json(
            'DELETE',
            '/api/users/' . UserSeeder::USER_1 . '/bookings/' . BookingSeeder::USER_1_BOOKING_1
        )
            ->assertNoContent();

        $this->assertDatabaseMissing(
            'parking_space_bookings',
            [
                'id' => BookingSeeder::USER_1_BOOKING_1,
                'user_id' => UserSeeder::USER_1,
            ]
        );

        $this->assertDatabasehas(
            'parking_space_bookings',
            [
                'id' => BookingSeeder::USER_1_BOOKING_2,
                'user_id' => UserSeeder::USER_1,
            ]
        );
    }

    public function testUsersCannotDeleteOtherUsersBookings(): void
    {
        Sanctum::actingAs(User::find(UserSeeder::USER_2));

        $this->assertDatabaseHas(
            'parking_space_bookings',
            [
                'id' => BookingSeeder::USER_1_BOOKING_1,
                'user_id' => UserSeeder::USER_1,
            ]
        );

        $this->json(
            'DELETE',
            '/api/users/' . UserSeeder::USER_2 . '/bookings/' . BookingSeeder::USER_1_BOOKING_1
        )
            ->assertForbidden();

        $this->assertDatabaseHas(
            'parking_space_bookings',
            [
                'id' => BookingSeeder::USER_1_BOOKING_1,
                'user_id' => UserSeeder::USER_1,
            ]
        );
    }

    /**
     * @dataProvider validationProvider
     * */
    public function testValidation(int $userId, $bookingId, array $expectedErrors): void
    {
        Sanctum::actingAs(new User(['id' => $userId]));

        $this->json(
            'DELETE',
            "/api/users/{$userId}/bookings/{$bookingId}"
        )
            ->assertInvalid($expectedErrors);
    }

    public static function validationProvider(): array
    {
        return [
            'test booking id must be numeric' => [
                'user_id' => UserSeeder::USER_1,
                'booking_id' => 'booking_one',
                'expected_errors' => [
                    'booking_id' => [
                        'The booking id field must be an integer.',
                        'The booking id field must be a number.'
                    ]
                ]
            ],
            'test booking id must exist in db' => [
                'user_id' => UserSeeder::USER_1,
                'booking_id' => BookingSeeder::NON_EXISTENT_BOOKING,
                'expected_errors' => [
                    'booking_id' => [
                        'The selected booking id is invalid.'
                    ]
                ]
            ],
        ];
    }
}
