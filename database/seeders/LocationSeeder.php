<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public const TERMINAL_1 = 1;
    public const TERMINAL_2 = 2;

    public function run(): void
    {
        DB::table('locations')
            ->insert(
                [
                    [
                        'id' => self::TERMINAL_1,
                        'name' => 'Terminal 1',
                        'default_price_per_day_gbp' => 12.50
                    ],
                    [
                        'id' => self::TERMINAL_2,
                        'name' => 'Terminal 2',
                        'default_price_per_day_gbp' => 20.0
                    ],
                ]
            );
    }
}
