<?php

namespace Modules\FreshTestModule\Providers;

use Illuminate\Support\ServiceProvider;

class FreshTestModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/FreshTestModule.php',
            'FreshTestModule'
        );
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'FreshTestModule');
        
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
        }
    }
}