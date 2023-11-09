<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('locations')
            ->insert(
                [
                    [
                        'id' => 1,
                        'name' => 'Terminal 1',
                        'default_price_per_day_gbp' => 12.50
                    ],
                    [
                        'id' => 2,
                        'name' => 'Terminal 2',
                        'default_price_per_day_gbp' => 20.0
                    ],
                ]
            );
    }
}
