<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    public const CUSTOMER_1_BOOKING_1 = 1;

    public const CUSTOMER_1_BOOKING_1_DATE_FROM = '2023-01-02';

    public const CUSTOMER_1_BOOKING_1_DATE_TO = '2023-01-06';

    public const CUSTOMER_1_BOOKING_2 = 4;

    public const CUSTOMER_2_BOOKING_1 = 2;

    public const CUSTOMER_2_BOOKING_1_DATE_FROM = '2023-01-01';

    public const NON_EXISTENT_BOOKING = 999;

    public function run(): void
    {
        DB::table('parking_space_bookings')
            ->insert(
                [
                    [
                        'id' => self::CUSTOMER_1_BOOKING_1,
                        'customer_id' => 1,
                        'parking_space_id' => 3,
                        'date_from' => self::CUSTOMER_1_BOOKING_1_DATE_FROM,
                        'date_to' => self::CUSTOMER_1_BOOKING_1_DATE_TO,
                        'price_gbp' => '80.0',
                        'created_at' => '2022-12-20 09:00:00',
                        'updated_at' => '2022-12-20 09:00:00',
                    ],
                    [
                        'id' => self::CUSTOMER_2_BOOKING_1,
                        'customer_id' => 2,
                        'parking_space_id' => 5,
                        'date_from' => self::CUSTOMER_2_BOOKING_1_DATE_FROM,
                        'date_to' => '2023-01-21',
                        'price_gbp' => '280.0',
                        'created_at' => '2022-12-20 09:00:00',
                        'updated_at' => '2022-12-20 09:00:00',
                    ],
                    [
                        'id' => 3,
                        'customer_id' => 2,
                        'parking_space_id' => 1,
                        'date_from' => '2023-02-10',
                        'date_to' => '2023-02-21',
                        'price_gbp' => '100.0',
                        'created_at' => '2022-12-20 09:00:00',
                        'updated_at' => '2022-12-20 09:00:00',
                    ],
                    [
                        'id' => self::CUSTOMER_1_BOOKING_2,
                        'customer_id' => 1,
                        'parking_space_id' => 10,
                        'date_from' => '2023-04-20',
                        'date_to' => '2023-05-05',
                        'price_gbp' => '280.0',
                        'created_at' => '2022-12-20 09:00:00',
                        'updated_at' => '2022-12-20 09:00:00',
                    ],
                ]
            );
    }
}
