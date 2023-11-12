<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\BookingSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\ParkingSpaceSeeder;
use Database\Seeders\UserSeeder;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreBookingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function testUnauthorisedIfAuthTokenIsNotProvided(): void
    {
        $this->json(
            'POST',
            'api/users/' . UserSeeder::USER_1 . '/bookings',
            [
                'parking_space_id' => 1,
                'date_from' => '2023-04-01',
                'date_to' => '2023-04-05',
                'price_gbp' => 62.50,
            ]
        )
            ->assertUnauthorized();
    }

    public function testStoreBooking(): void
    {
        Sanctum::actingAs(User::find(UserSeeder::USER_1));

        $this->assertDatabaseMissing(
            'parking_space_bookings',
            [
                'user_id' => UserSeeder::USER_1,
                'parking_space_id' => 1,
                'date_from' => '2023-04-20',
                'date_to' => '2023-05-02',
                'price_gbp' => 100,
            ]
        );

        $response = $this->json(
            'POST',
            'api/users/' . UserSeeder::USER_1 . '/bookings',
            [
                'parking_space_id' => 1,
                'date_from' => '2023-04-01',
                'date_to' => '2023-04-05',
                'price_gbp' => 62.50,
            ]
        )
            ->assertCreated();

        $response
            ->assertJson(
                [
                    'id' => $response->json('id'),
                    'user_id' => UserSeeder::USER_1,
                    'parking_space_id' => 1,
                    'date_from' => '2023-04-01',
                    'date_to' => '2023-04-05',
                    'price_gbp' => 62.50,
                    'created_at' => self::TEST_DATE_TIME,
                    'updated_at' => self::TEST_DATE_TIME
                ]
            );

        $this->assertDatabaseHas(
            'parking_space_bookings',
            [
                'user_id' => UserSeeder::USER_1,
                'parking_space_id' => 1,
                'date_from' => '2023-04-01',
                'date_to' => '2023-04-05',
                'price_gbp' => 62.50,
            ]
        );
    }

    public function testBookingCannotOverlapWithAnotherBooking(): void
    {
        Sanctum::actingAs(User::find(UserSeeder::USER_2));

        $this->json(
            'POST',
            'api/users/' . UserSeeder::USER_2 . '/bookings',
            [
                'parking_space_id' => 3,
                'date_from' => BookingSeeder::USER_1_BOOKING_1_DATE_FROM,
                'date_to' => BookingSeeder::USER_1_BOOKING_1_DATE_TO,
                'price_gbp' => 100,
            ]
        )
        ->assertInvalid(
            [
                'parking_space_id' => 'Parking space 3 is not available during the selected date range'
            ]
        );

        $this->assertDatabaseMissing(
            'parking_space_bookings',
            [
                'user_id' => UserSeeder::USER_2,
                'parking_space_id' => 3,
                'date_from' => BookingSeeder::USER_1_BOOKING_1_DATE_FROM,
                'date_to' => BookingSeeder::USER_1_BOOKING_1_DATE_TO,
                'price_gbp' => 100,
            ]
        );
    }

    public function testPriceMustBeValid(): void
    {
        Sanctum::actingAs(new User(['id' => UserSeeder::USER_2]));

        $error = 'Invalid price for Parking space 1. From 2023-04-20 to 2023-05-02. Price in GBP should be 162.50';

        $this->json(
            'POST',
            'api/users/' . UserSeeder::USER_2 . '/bookings',
            [
                'parking_space_id' => 1,
                'date_from' => '2023-04-20',
                'date_to' => '2023-05-02',
                'price_gbp' => 10.00,
            ]
        )
            ->assertInvalid(
                [
                    'price_gbp' => $error
                ]
            );

        $this->assertDatabaseMissing(
            'parking_space_bookings',
            [
                'user_id' => UserSeeder::USER_2,
                'parking_space_id' => 1,
                'date_from' => '2023-04-20',
                'date_to' => '2023-05-02',
                'price_gbp' => 10.00,
            ]
        );
    }

    /**
     * @dataProvider validationProvider
     * */
    public function testValidation(array $postParams, $userId, array $expectedErrors): void
    {
        Sanctum::actingAs(new User(['id' => $userId]));

        $this->json(
            'POST',
            'api/users/' . $userId . '/bookings',
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
                'user_id' => UserSeeder::USER_1,
                'expected_errors' => ['date_from' => 'The date from field is required.'],
            ],
            'test date_from must be given in Y-m-d format.' => [
                'params' => [
                    'date_from' => '01-01-2023',
                    'date_to' => '2023-01-07',
                    'parking_space_id' => 1,
                    'price_gbp' => 100,
                ],
                'user_id' => UserSeeder::USER_1,
                'expected_errors' => ['date_from' => 'The date from field must match the format Y-m-d.'],
            ],
            'test date_to is a required query parameter' => [
                'params' => [
                    'date_from' => '2023-01-01',
                    'parking_space_id' => 1,
                    'price_gbp' => 100,
                ],
                'user_id' => UserSeeder::USER_1,
                'expected_errors' => ['date_to' => 'The date to field is required.'],
            ],
            'test date_to must be given in Y-m-d format.' => [
                'params' => [
                    'date_from' => '2023-01-01',
                    'date_to' => '01-01-2023',
                    'parking_space_id' => 1,
                    'price_gbp' => 100,
                ],
                'user_id' => UserSeeder::USER_1,
                'expected_errors' => ['date_to' => 'The date to field must match the format Y-m-d.'],
            ],
            'test date_to must occur after date_from' => [
                'params' => [
                    'date_from' => '2023-02-01',
                    'date_to' => '2023-01-26',
                    'parking_space_id' => 1,
                    'price_gbp' => 100,
                ],
                'user_id' => UserSeeder::USER_1,
                'expected_errors' => ['date_to' => 'The date to field must be a date after date from.'],
            ],
            'test price_gbp is a required field' => [
                'params' => [
                    'date_from' => '2023-02-01',
                    'date_to' => '2023-02-06',
                    'parking_space_id' => 1,
                ],
                'user_id' => UserSeeder::USER_1,
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
                'user_id' => UserSeeder::USER_1,
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
                'user_id' => UserSeeder::USER_1,
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
                'user_id' => UserSeeder::USER_1,
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
                'user_id' => UserSeeder::USER_1,
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
                'user_id' => UserSeeder::USER_1,
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
                'user_id' => UserSeeder::USER_1,
                'expected_errors' => [
                    'parking_space_id' => [
                        'The selected parking space id is invalid.',
                    ],
                ]
            ],
        ];
    }
}
