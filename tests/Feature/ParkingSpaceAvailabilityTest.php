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

        $this->seed(CustomerSeeder::class);
        $this->seed(LocationSeeder::class);
        $this->seed(ParkingSpaceSeeder::class);
        $this->seed(ParkingSpaceBookingsSeeder::class);
    }

    /**
     * @dataProvider parkingSpaceAvailabilityProvider
     */
    public function testParkingSpaceAvailability(array $inputParams, string $resultFile): void
    {
        $this->json(
            'GET',
            '/api/parking-spaces/availability',
            $inputParams
        )
            ->assertOk()
            ->assertExactJson($this->loadJsonFileAsArray($resultFile));
    }

    public static function parkingSpaceAvailabilityProvider(): array
    {
        return [
            'test all spaces available when no bookings occur during selected date range' => [
                'params' => [
                    'dateFrom' => '2023-12-05',
                    'dateTo' => '2023-12-25',
                ],
                'result_file' => 'parking_space_availability_all_spaces',
            ],
            'test spaces available when bookings occur during selected date range - 2023-12-05 to 2023-12-25' => [
                'params' => [
                    'dateFrom' => '2023-01-01',
                    'dateTo' => '2023-01-08',
                ],
                'result_file' => 'parking_space_availability_with_bookings_1',
            ],
            'test spaces available when bookings occur during selected date range - 2023-02-15 to 2023-02-24' => [
                'params' => [
                    'dateFrom' => '2023-02-15',
                    'dateTo' => '2023-02-24',
                ],
                'result_file' => 'parking_space_availability_with_bookings_2',
            ],
            'test spaces available when bookings occur during selected date range - 2023-04-15 to 2023-05-01' => [
                'params' => [
                    'dateFrom' => '2023-04-15',
                    'dateTo' => '2023-05-01',
                ],
                'result_file' => 'parking_space_availability_with_bookings_3',
            ],
            'test spaces available when bookings occur during selected date range - 2023-01-01 to 2023-12-31' => [
                'params' => [
                    'dateFrom' => '2023-01-01',
                    'dateTo' => '2023-12-31',
                ],
                'result_file' => 'parking_space_availability_with_bookings_4',
            ],
        ];
    }

    /**
     * @dataProvider validationProvider
     * */
    public function testValidation(array $queryParameters, array $expectedErrors): void
    {
        $this->json(
            'GET',
            '/api/parking-spaces/availability',
            $queryParameters
        )
            ->assertInvalid($expectedErrors);
    }

    public static function validationProvider(): array
    {
        return [
            'test dateFrom is a required query parameter' => [
                'params' => [
                    'dateTo' => '2023-01-01',
                ],
                'expected_errors' => ['dateFrom' => 'The date from field is required.'],
            ],
            'test dateFrom must be given in Y-m-d format.' => [
                'params' => [
                    'dateFrom' => '01-01-2023',
                    'dateTo' => '2023-01-07',
                ],
                'expected_errors' => ['dateFrom' => 'The date from field must match the format Y-m-d.'],
            ],
            'test dateTo is a required query parameter' => [
                'params' => [
                    'dateFrom' => '2023-01-01',
                ],
                'expected_errors' => ['dateTo' => 'The date to field is required.'],
            ],
            'test dateTo must be given in Y-m-d format.' => [
                'params' => [
                    'dateFrom' => '2023-01-01',
                    'dateTo' => '01-01-2023',
                ],
                'expected_errors' => ['dateTo' => 'The date to field must match the format Y-m-d.'],
            ],
            'test dateTo must occur after dateFrom' => [
                'params' => [
                    'dateFrom' => '2023-02-01',
                    'dateTo' => '2023-01-26',
                ],
                'expected_errors' => ['dateTo' => 'The date to field must be a date after date from.'],
            ],
        ];
    }
}
