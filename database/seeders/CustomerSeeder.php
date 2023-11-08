<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public const CUSTOMER_1 = 1;

    public const CUSTOMER_2 = 2;

    public function run(): void
    {
        DB::table('customers')
            ->insert(
                [
                    [
                        'id' => self::CUSTOMER_1,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                    ],
                    [
                        'id' => self::CUSTOMER_2,
                        'first_name' => 'Jane',
                        'last_name' => 'Doe',
                    ]
                ]
            );
    }
}
