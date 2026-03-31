<?php

namespace App\Providers;

use App\MetaData\MetaData;
use Illuminate\Support\ServiceProvider;

class FacadeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->singleton('metadata', function () {
            return new MetaData();
        });
    }
}
