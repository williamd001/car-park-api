<?php

namespace Tests\Feature;

use Database\Seeders\BookingSeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;

class DeleteParkingSpaceBookingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function testDeleteBooking(): void
    {
        $this->assertDatabaseHas(
            'parking_space_bookings',
            [
                'id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                'customer_id' => CustomerSeeder::CUSTOMER_1,
            ]
        );

        $this->json(
            'DELETE',
            '/api/customers/' . CustomerSeeder::CUSTOMER_1 . '/bookings/' . BookingSeeder::CUSTOMER_1_BOOKING_1
        )
            ->assertNoContent();

        $this->assertDatabaseMissing(
            'parking_space_bookings',
            [
                'id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                'customer_id' => CustomerSeeder::CUSTOMER_1,
            ]
        );

        $this->assertDatabasehas(
            'parking_space_bookings',
            [
                'id' => BookingSeeder::CUSTOMER_1_BOOKING_2,
                'customer_id' => CustomerSeeder::CUSTOMER_1,
            ]
        );
    }

    public function test404ReturnedIfBookingIsNotFoundForCustomer(): void
    {
        $this->assertDatabaseHas(
            'parking_space_bookings',
            [
                'id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                'customer_id' => CustomerSeeder::CUSTOMER_1,
            ]
        );

        $this->json(
            'DELETE',
            '/api/customers/' . CustomerSeeder::CUSTOMER_2 . '/bookings/' . BookingSeeder::CUSTOMER_1_BOOKING_1
        )
            ->assertNotFound();

        $this->assertDatabaseHas(
            'parking_space_bookings',
            [
                'id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                'customer_id' => CustomerSeeder::CUSTOMER_1,
            ]
        );
    }

    public function test404ReturnedIfBookingIsNotFound(): void
    {
        $this->json(
            'DELETE',
            '/api/customers/' . CustomerSeeder::CUSTOMER_1 . '/bookings/404'
        )
            ->assertNotFound();
    }

    public function test404ReturnedIfCustomerIsNotFound(): void
    {
        $this->json(
            'DELETE',
            '/api/customers/' . CustomerSeeder::NON_EXISTENT_CUSTOMER . '/bookings/' . BookingSeeder::CUSTOMER_1_BOOKING_1
        )
            ->assertNotFound();
    }

    /**
     * @dataProvider validationProvider
     * */
    public function testValidation(string $uri, array $expectedErrors): void
    {
        $this->json(
            'DELETE',
            $uri
        )
            ->assertInvalid($expectedErrors);
    }

    public static function validationProvider(): array
    {
        return [
            'test customer id must be numeric' => [
                'uri' => '/api/customers/customer_one/bookings/' . BookingSeeder::CUSTOMER_1_BOOKING_1,
                'expected_errors' => [
                    'customerId' => [
                        'The customer id field must be an integer.',
                        'The customer id field must be a number.'
                    ]
                ]
            ],
            'test booking id must be numeric' => [
                'uri' => '/api/customers/' . CustomerSeeder::CUSTOMER_1 . '/bookings/booking_one',
                'expected_errors' => [
                    'bookingId' => [
                        'The booking id field must be an integer.',
                        'The booking id field must be a number.'
                    ]
                ]
            ]
        ];
    }
}
