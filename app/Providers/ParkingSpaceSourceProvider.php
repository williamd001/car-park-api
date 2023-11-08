<?php

namespace App\Providers;

use App\Sources\ParkingSpaceMySQLSource;
use App\Sources\ParkingSpaceSource;
use Illuminate\Support\ServiceProvider;

class ParkingSpaceSourceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(
            ParkingSpaceSource::class,
            ParkingSpaceMySQLSource::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
