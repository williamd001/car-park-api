<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParkingSpaceSeeder extends Seeder
{
    public const NON_EXISTENT_PARKING_SPACE = 999;

    public function run(): void
    {
        DB::table('parking_spaces')
            ->insert(
                [
                    [
                        'id' => 1,
                        'location_id' => LocationSeeder::TERMINAL_1,
                    ],
                    [
                        'id' => 2,
                        'location_id' => LocationSeeder::TERMINAL_1,
                    ],
                    [
                        'id' => 3,
                        'location_id' => LocationSeeder::TERMINAL_1,
                    ],
                    [
                        'id' => 4,
                        'location_id' => LocationSeeder::TERMINAL_1,
                    ],
                    [
                        'id' => 5,
                        'location_id' => LocationSeeder::TERMINAL_1,
                    ],
                    [
                        'id' => 6,
                        'location_id' => LocationSeeder::TERMINAL_2,
                    ],
                    [
                        'id' => 7,
                        'location_id' => LocationSeeder::TERMINAL_2,
                    ],
                    [
                        'id' => 8,
                        'location_id' => LocationSeeder::TERMINAL_2,
                    ],
                    [
                        'id' => 9,
                        'location_id' => LocationSeeder::TERMINAL_2,
                    ],
                    [
                        'id' => 10,
                        'location_id' => LocationSeeder::TERMINAL_2,
                    ],
                ]
            );
    }
}
