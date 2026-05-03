<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Allow recovery routes to bypass maintenance mode
        if (request()->is('force-up') || request()->is('fix-storage')) {
            config(['app.maintenance' => null]);
        }
    }
}
