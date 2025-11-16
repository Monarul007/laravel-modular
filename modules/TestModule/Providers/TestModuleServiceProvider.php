<?php

namespace Modules\TestModule\Providers;

use Illuminate\Support\ServiceProvider;

class TestModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/TestModule.php',
            'TestModule'
        );
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'TestModule');
        
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
        }
    }
}