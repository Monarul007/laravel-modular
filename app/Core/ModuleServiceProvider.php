<?php

namespace App\Core;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ModuleManager::class);
    }

    public function boot(): void
    {
        $this->ensureModulesDirectory();
        
        $moduleManager = $this->app->make(ModuleManager::class);
        $moduleManager->bootModules();
        
        $this->loadModuleRoutes($moduleManager);
    }

    protected function ensureModulesDirectory(): void
    {
        $modulesPath = base_path('modules');
        
        if (!File::exists($modulesPath)) {
            File::makeDirectory($modulesPath, 0755, true);
        }
    }

    protected function loadModuleRoutes(ModuleManager $moduleManager): void
    {
        foreach ($moduleManager->getEnabledModules() as $moduleName) {
            $this->loadModuleApiRoutes($moduleName);
            $this->loadModuleWebRoutes($moduleName);
        }
    }

    protected function loadModuleApiRoutes(string $moduleName): void
    {
        $routePath = base_path("modules/{$moduleName}/routes/api.php");
        
        if (File::exists($routePath)) {
            $this->loadRoutesFrom($routePath);
        }
    }

    protected function loadModuleWebRoutes(string $moduleName): void
    {
        $routePath = base_path("modules/{$moduleName}/routes/web.php");
        
        if (File::exists($routePath)) {
            $this->loadRoutesFrom($routePath);
        }
    }
}