<?php

namespace Tests\Feature;

use Database\Seeders\BookingSeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\ParkingSpaceSeeder;
use Tests\TestCase;

class UpdateBookingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function testUpdateBooking(): void
    {
        $this->assertDatabaseHas(
            'parking_space_bookings',
            [
                'id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                'customer_id' => 1,
                'parking_space_id' => 3,
                'date_from' => '2023-01-02',
                'date_to' => '2023-01-06',
                'price_gbp' => '80.0',
            ]
        );

        $this->json(
            'PUT',
            '/api/customers/' . CustomerSeeder::CUSTOMER_1 . '/bookings/' . BookingSeeder::CUSTOMER_1_BOOKING_1,
            [
                'parking_space_id' => 3,
                'date_from' => '2023-02-02',
                'date_to' => '2023-02-06',
                'price_gbp' => '80.0',
            ]
        )
            ->assertOk()
            ->assertExactJson(
                [
                    'id' => 1,
                    'customer_id' => 1,
                    'parking_space_id' => 3,
                    'date_from' => '2023-02-02',
                    'date_to' => '2023-02-06',
                    'price_gbp' => 80,
                    'created_at' => '2022-12-20 09:00:00',
                    'updated_at' => '2023-01-01 09:00:00',
                ]
            );

        $this->assertDatabaseHas(
            'parking_space_bookings',
            [
                'id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                'customer_id' => 1,
                'parking_space_id' => 3,
                'date_from' => '2023-02-02',
                'date_to' => '2023-02-06',
                'price_gbp' => '80.0',
            ]
        );
    }

    public function testBookingUpdateDatesCantConflictWithOtherBookings(): void
    {
        $this->json(
            'PUT',
            '/api/customers/' . CustomerSeeder::CUSTOMER_2 . '/bookings/' . BookingSeeder::CUSTOMER_2_BOOKING_1,
            [
                'parking_space_id' => 3,
                'date_from' => BookingSeeder::CUSTOMER_1_BOOKING_1_DATE_FROM,
                'date_to' => BookingSeeder::CUSTOMER_1_BOOKING_1_DATE_TO,
                'price_gbp' => '100.0',
            ]
        )
            ->assertInvalid(
                [
                    'parking_space_id' => [
                        'Parking space 3 is not available during the selected date range'
                    ]
                ]
            );

        $this->assertDatabaseMissing(
            'parking_space_bookings',
            [
                'id' => BookingSeeder::CUSTOMER_2_BOOKING_1,
                'customer_id' => CustomerSeeder::CUSTOMER_2,
                'parking_space_id' => 3,
                'date_from' => '2023-01-02',
                'date_to' => '2023-01-07',
                'price_gbp' => '100.0',
            ]
        );
    }

    // testing parking space availability check ignores current booking id
    public function testUserCanUpdateBookingDates(): void
    {
        $this->json(
            'PUT',
            '/api/customers/' . CustomerSeeder::CUSTOMER_2 . '/bookings/' . BookingSeeder::CUSTOMER_2_BOOKING_1,
            [
                'parking_space_id' => 5,
                'date_from' => BookingSeeder::CUSTOMER_2_BOOKING_1_DATE_FROM,
                'date_to' => '2023-01-23',
                'price_gbp' => '320',
            ]
        )
            ->assertOk();

        $this->assertDatabaseHas(
            'parking_space_bookings',
            [
                'id' => BookingSeeder::CUSTOMER_2_BOOKING_1,
                'customer_id' => CustomerSeeder::CUSTOMER_2,
                'parking_space_id' => 5,
                'date_from' => BookingSeeder::CUSTOMER_2_BOOKING_1_DATE_FROM,
                'date_to' => '2023-01-23',
                'price_gbp' => '320',
            ]
        );
    }

    /**
     * @dataProvider validationProvider
     */
    public function testValidation(
        array $postParams,
              $customerId,
              $bookingId,
        array $expectedErrors
    ): void
    {
        $this->json(
            'PUT',
            "/api/customers/{$customerId}/bookings/{$bookingId}",
            $postParams
        )
            ->assertInvalid($expectedErrors);
    }

    public static function validationProvider(): array
    {
        return [
            'test date_from is a required query parameter' => [
                'params' => [
                    'date_to' => '2023-05-02',
                    'parking_space_id' => 1,
                    'price_gbp' => 100,
                ],
                'customer_id' => CustomerSeeder::CUSTOMER_1,
                'booking_id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                'expected_errors' => ['date_from' => 'The date from field is required.'],
            ],
            'test date_from must be given in Y-m-d format.' => [
                'params' => [
                    'date_from' => '01-01-2023',
                    'date_to' => '2023-01-07',
                    'parking_space_id' => 1,
                    'price_gbp' => 100,
                ],
                'customer_id' => CustomerSeeder::CUSTOMER_1,
                'booking_id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                'expected_errors' => ['date_from' => 'The date from field must match the format Y-m-d.'],
            ],
            'test date_to is a required query parameter' => [
                'params' => [
                    'date_from' => '2023-01-01',
                    'parking_space_id' => 1,
                    'price_gbp' => 100,
                ],
                'customer_id' => CustomerSeeder::CUSTOMER_1,
                'booking_id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                'expected_errors' => ['date_to' => 'The date to field is required.'],
            ],
            'test date_to must be given in Y-m-d format.' => [
                'params' => [
                    'date_from' => '2023-01-01',
                    'date_to' => '01-01-2023',
                    'parking_space_id' => 1,
                    'price_gbp' => 100,
                ],
                'customer_id' => CustomerSeeder::CUSTOMER_1,
                'booking_id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                'expected_errors' => ['date_to' => 'The date to field must match the format Y-m-d.'],
            ],
            'test date_to must occur after date_from' => [
                'params' => [
                    'date_from' => '2023-02-01',
                    'date_to' => '2023-01-26',
                    'parking_space_id' => 1,
                    'price_gbp' => 100,
                ],
                'customer_id' => CustomerSeeder::CUSTOMER_1,
                'booking_id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                'expected_errors' => ['date_to' => 'The date to field must be a date after date from.'],
            ],
            'test customer id must exist in db table' => [
                'params' => [
                    'date_from' => '2023-02-01',
                    'date_to' => '2023-02-06',
                    'parking_space_id' => 1,
                    'price_gbp' => 100,
                ],
                'customer_id' => CustomerSeeder::NON_EXISTENT_CUSTOMER,
                'booking_id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                'expected_errors' => [
                    'customer_id' => 'The selected customer id is invalid.',
                ],
                'test customer id must be numeric' => [
                    'params' => [
                        'date_from' => '2023-02-01',
                        'date_to' => '2023-02-06',
                        'parking_space_id' => 1,
                        'price_gbp' => 100,
                    ],
                    'customer_id' => 'customer_one',
                    'booking_id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                    'expected_errors' => [
                        'customer_id' => CustomerSeeder::CUSTOMER_1,
                        'booking_id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                        'The customer id field must be an integer.',
                        'The customer id field must be a number.',
                    ],
                ]
            ],
            'test price_gbp is a required field' => [
                'params' => [
                    'date_from' => '2023-02-01',
                    'date_to' => '2023-02-06',
                    'parking_space_id' => 1,
                ],
                'customer_id' => CustomerSeeder::CUSTOMER_1,
                'booking_id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                'expected_errors' => [
                    'price_gbp' => [
                        'The price gbp field is required.',
                    ],
                ]
            ],
            'test price_gbp minimum value is 0' => [
                'params' => [
                    'date_from' => '2023-02-01',
                    'date_to' => '2023-02-06',
                    'parking_space_id' => 1,
                    'price_gbp' => -100,
                ],
                'customer_id' => CustomerSeeder::CUSTOMER_1,
                'booking_id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                'expected_errors' => [
                    'price_gbp' => [
                        'The price gbp field must be at least 0.'
                    ],
                ]
            ],
            'test price_gbp maximum value is 99999' => [
                'params' => [
                    'date_from' => '2023-02-01',
                    'date_to' => '2023-02-06',
                    'parking_space_id' => 1,
                    'price_gbp' => 999999,
                ],
                'customer_id' => CustomerSeeder::CUSTOMER_1,
                'booking_id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                'expected_errors' => [
                    'price_gbp' => [
                        'The price gbp field must not be greater than 99999.'
                    ],
                ]
            ],
            'test price_gbp must be numeric' => [
                'params' => [
                    'date_from' => '2023-02-01',
                    'date_to' => '2023-02-06',
                    'parking_space_id' => 1,
                    'price_gbp' => 'fifty pounds',
                ],
                'customer_id' => CustomerSeeder::CUSTOMER_1,
                'booking_id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                'expected_errors' => [
                    'price_gbp' => [
                        'The price gbp field must be an integer.',
                        'The price gbp field must be a number.',
                    ],
                ]
            ],
            'test parking_space_id is a required field' => [
                'params' => [
                    'date_from' => '2023-02-01',
                    'date_to' => '2023-02-06',
                    'price_gbp' => 100,
                ],
                'customer_id' => CustomerSeeder::CUSTOMER_1,
                'booking_id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                'expected_errors' => [
                    'parking_space_id' => [
                        'The parking space id field is required.',
                    ],
                ]
            ],
            'test parking_space_id must be numeric' => [
                'params' => [
                    'date_from' => '2023-02-01',
                    'date_to' => '2023-02-06',
                    'parking_space_id' => 'one',
                    'price_gbp' => 100,
                ],
                'customer_id' => CustomerSeeder::CUSTOMER_1,
                'booking_id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                'expected_errors' => [
                    'parking_space_id' => [
                        'The parking space id field must be an integer.',
                        'The parking space id field must be a number.',
                    ],
                ]
            ],
            'test parking_space_id must exist in db table' => [
                'params' => [
                    'date_from' => '2023-02-01',
                    'date_to' => '2023-02-06',
                    'parking_space_id' => ParkingSpaceSeeder::NON_EXISTENT_PARKING_SPACE,
                    'price_gbp' => 100,
                ],
                'customer_id' => CustomerSeeder::CUSTOMER_1,
                'booking_id' => BookingSeeder::CUSTOMER_1_BOOKING_1,
                'expected_errors' => [
                    'parking_space_id' => [
                        'The selected parking space id is invalid.',
                    ],
                ]
            ],
            'test booking_id must exist in db table' => [
                'params' => [
                    'date_from' => '2023-02-01',
                    'date_to' => '2023-02-06',
                    'parking_space_id' => 1,
                    'price_gbp' => 100,
                ],
                'customer_id' => CustomerSeeder::CUSTOMER_1,
                'booking_id' => BookingSeeder::NON_EXISTENT_BOOKING,
                'expected_errors' => [
                    'booking_id' => [
                        'The selected booking id is invalid.',
                    ],
                ]
            ],
            'test booking_id must be numeric' => [
                'params' => [
                    'date_from' => '2023-02-01',
                    'date_to' => '2023-02-06',
                    'parking_space_id' => 1,
                    'price_gbp' => 100,
                ],
                'customer_id' => CustomerSeeder::CUSTOMER_1,
                'booking_id' => 'one',
                'expected_errors' => [
                    'booking_id' => [
                        'The booking id field must be an integer.',
                        'The booking id field must be a number.',
                    ],
                ],
            ],
        ];
    }
}
