<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')
            ->insert(
                [
                    [
                        'id' => 1,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                    ],
                    [
                        'id' => 2,
                        'first_name' => 'Jane',
                        'last_name' => 'Doe',
                    ]
                ]
            );
    }
}
