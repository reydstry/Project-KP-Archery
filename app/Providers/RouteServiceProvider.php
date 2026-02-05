<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->input('email');
            $key = strtolower($email) . '|' . $request->ip();

            return Limit::perMinute(10)->by($key);
        });

        RateLimiter::for('forgot-password', function (Request $request) {
            $email = (string) $request->input('email');
            $key = strtolower($email) . '|' . $request->ip();

            return Limit::perMinute(5)->by($key);
        });
    }
}
