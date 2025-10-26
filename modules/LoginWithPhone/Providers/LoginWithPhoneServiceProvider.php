<?php

namespace Modules\LoginWithPhone\Providers;

use Illuminate\Support\ServiceProvider;

class LoginWithPhoneServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/LoginWithPhone.php',
            'LoginWithPhone'
        );
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'LoginWithPhone');
        
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
        }
    }
}