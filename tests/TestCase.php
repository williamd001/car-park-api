<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected function loadJsonFileAsArray(string $jsonFileName): array
    {
        return json_decode(
            file_get_contents(__DIR__ . '/Results/' . $jsonFileName . '.json'),
            true
        );
    }
}
