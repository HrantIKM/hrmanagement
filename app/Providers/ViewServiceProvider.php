<?php

namespace App\Providers;

use App\Facades\MetaData;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
        // MetaData
        view()->share('meta', MetaData::getDefaultData());
    }
}
