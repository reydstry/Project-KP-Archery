<?php

namespace App\Providers;

use App\Contracts\WhatsAppGatewayInterface;
use App\Gateways\DummyGateway;
use App\Gateways\WablasGateway;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(WhatsAppGatewayInterface::class, function ($app) {
            $stored = Cache::get('settings:whatsapp', []);
            $driver = $stored['driver'] ?? config('whatsapp.driver', 'dummy');

            return match ($driver) {
                'wablas' => $app->make(WablasGateway::class),
                default => $app->make(DummyGateway::class),
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
