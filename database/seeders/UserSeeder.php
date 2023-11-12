<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class UserSeeder extends Seeder
{
    public const USER_1 = 1;

    public const USER_2 = 2;

    public const NON_EXISTENT_CUSTOMER = 999;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')
            ->insert(
                [
                    [
                        'id' => self::USER_1,
                        'name' => 'John Doe',
                        'email' => 'john.doe@example.com',
                        'email_verified_at' => new Carbon(TestCase::TEST_DATE_TIME),
                        'password' => Hash::make('user_1_password'),
                        'remember_token' => 'remember_token_for_user_1',
                        'created_at' => new Carbon(TestCase::TEST_DATE_TIME),
                        'updated_at' => new Carbon(TestCase::TEST_DATE_TIME),
                    ],
                    [
                        'id' => self::USER_2,
                        'name' => 'Jane Doe',
                        'email' => 'jane.doe@example.com',
                        'email_verified_at' => new Carbon(TestCase::TEST_DATE_TIME),
                        'password' => Hash::make('user_2_password'),
                        'remember_token' => 'remember_token_for_user_2',
                        'created_at' => new Carbon(TestCase::TEST_DATE_TIME),
                        'updated_at' => new Carbon(TestCase::TEST_DATE_TIME),
                    ],
                ]
            );

        PersonalAccessToken::create(
            [
                'tokenable_id' => self::USER_1,
                'tokenable_type' => User::class,
                'name' => 'test-token-user-1',
                'token' => hash('sha256', 'sanctum-token-user-1'),
            ]
        );

        PersonalAccessToken::create(
            [
                'tokenable_id' => self::USER_2,
                'tokenable_type' => User::class,
                'name' => 'test-token-user-2',
                'token' => hash('sha256', 'sanctum-token-user-2'),
            ]
        );
    }
}
