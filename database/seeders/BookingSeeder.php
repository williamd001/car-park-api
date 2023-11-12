<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    public const USER_1_BOOKING_1 = 1;

    public const USER_1_BOOKING_1_DATE_FROM = '2023-01-02';

    public const USER_1_BOOKING_1_DATE_TO = '2023-01-06';

    public const USER_1_BOOKING_2 = 4;

    public const USER_2_BOOKING_1 = 2;

    public const USER_2_BOOKING_1_DATE_FROM = '2023-01-01';

    public const NON_EXISTENT_BOOKING = 999;

    public function run(): void
    {
        DB::table('bookings')
            ->insert(
                [
                    [
                        'id' => self::USER_1_BOOKING_1,
                        'user_id' => 1,
                        'parking_space_id' => 3,
                        'date_from' => self::USER_1_BOOKING_1_DATE_FROM,
                        'date_to' => self::USER_1_BOOKING_1_DATE_TO,
                        'price_gbp' => 62.50,
                        'created_at' => '2022-12-20 09:00:00',
                        'updated_at' => '2022-12-20 09:00:00',
                    ],
                    [
                        'id' => self::USER_2_BOOKING_1,
                        'user_id' => 2,
                        'parking_space_id' => 5,
                        'date_from' => self::USER_2_BOOKING_1_DATE_FROM,
                        'date_to' => '2023-01-21',
                        'price_gbp' => 262.50,
                        'created_at' => '2022-12-20 09:00:00',
                        'updated_at' => '2022-12-20 09:00:00',
                    ],
                    [
                        'id' => 3,
                        'user_id' => 2,
                        'parking_space_id' => 1,
                        'date_from' => '2023-02-10',
                        'date_to' => '2023-02-21',
                        'price_gbp' => 150.0,
                        'created_at' => '2022-12-20 09:00:00',
                        'updated_at' => '2022-12-20 09:00:00',
                    ],
                    [
                        'id' => self::USER_1_BOOKING_2,
                        'user_id' => 1,
                        'parking_space_id' => 10,
                        'date_from' => '2023-04-20',
                        'date_to' => '2023-05-05',
                        'price_gbp' => 320.0,
                        'created_at' => '2022-12-20 09:00:00',
                        'updated_at' => '2022-12-20 09:00:00',
                    ],
                ]
            );
    }
}
