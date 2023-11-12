<?php

namespace Tests;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public const TEST_DATE_TIME = '2023-01-01 09:00:00';

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(self::TEST_DATE_TIME);
    }

    protected function loadJsonFileAsArray(string $jsonFileName): array
    {
        return json_decode(
            file_get_contents(__DIR__ . '/Results/' . $jsonFileName . '.json'),
            true
        );
    }
}
