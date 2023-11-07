<?php

namespace Tests\Feature;

use Database\Seeders\CustomerSeeder;
use Database\Seeders\LocationSeeder;
use Database\Seeders\ParkingSpaceBookingsSeeder;
use Database\Seeders\ParkingSpaceSeeder;
use Tests\TestCase;

class ParkingSpaceAvailabilityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed( CustomerSeeder::class);
        $this->seed( LocationSeeder::class);
        $this->seed( ParkingSpaceSeeder::class);
        $this->seed( ParkingSpaceBookingsSeeder::class);
    }

    public function testParkingSpaceAvailability(): void
    {
        $this->json(
            'GET',
            '/api/parking-space/availability',
            [
                'dateFrom' => '2023-01-01',
                'dateTo' => '2023-01-08',
            ]
        )
            ->assertOk()
            ->assertExactJson($this->loadJsonFileAsArray('parking_space_availability'));
    }

    /**
     * @dataProvider validationProvider
     * */
    public function testValidation(array $queryParameters, array $expectedErrors): void
    {
        $this->json(
            'GET',
            '/api/parking-space/availability',
            $queryParameters
        )
            ->assertInvalid($expectedErrors);
    }

    public static function validationProvider(): array
    {
        return [
            'test dateFrom is a required query parameter' => [
                'params' => [
                    'dateTo' => '2022-01-01',
                ],
                'expected_errors' => ['dateFrom' => 'The date from field is required.'],
            ],
            'test dateFrom must be given in Y-m-d format.' => [
                'params' => [
                    'dateFrom' => '01-01-2023',
                    'dateTo' => '2022-01-07',
                ],
                'expected_errors' => ['dateFrom' => 'The date from field must match the format Y-m-d.'],
            ],
            'test dateTo is a required query parameter' => [
                'params' => [
                    'dateFrom' => '2022-01-01',
                ],
                'expected_errors' => ['dateTo' => 'The date to field is required.'],
            ],
            'test dateTo must be given in Y-m-d format.' => [
                'params' => [
                    'dateFrom' => '2022-01-01',
                    'dateTo' => '01-01-2023',
                ],
                'expected_errors' => ['dateTo' => 'The date to field must match the format Y-m-d.'],
            ],
        ];
    }
}
