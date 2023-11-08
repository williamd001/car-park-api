<?php

namespace App\Providers;

use App\Sources\BookingMySQLSource;
use App\Sources\BookingSource;
use Illuminate\Support\ServiceProvider;

class BookingSourceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            BookingSource::class,
            BookingMySQLSource::class
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
