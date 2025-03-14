<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Log;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {
        Log::debug("XXXXXXXXXXX");
        Route::middleware('api')
            ->prefix('/api/test')
            ->namespace('App\Http\Controllers')
            ->group(base_path('routes/test_api.php'));
    }

}
