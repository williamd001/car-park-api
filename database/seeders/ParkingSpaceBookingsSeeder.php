<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParkingSpaceBookingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('parking_space_bookings')
            ->insert(
                [
                    [
                        'id' => 1,
                        'customer_id' => 1,
                        'parking_space_id' => 3,
                        'date_from' => '2023-01-02',
                        'date_to' => '2023-01-06',
                        'price_gbp' => '80.0',
                    ],
                    [
                        'id' => 2,
                        'customer_id' => 2,
                        'parking_space_id' => 5,
                        'date_from' => '2023-01-01',
                        'date_to' => '2023-01-21',
                        'price_gbp' => '280.0',
                    ]
                ]
            );
    }
}
