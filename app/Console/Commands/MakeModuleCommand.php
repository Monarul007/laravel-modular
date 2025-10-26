<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModuleCommand extends Command
{
    protected $signature = 'make:module {name : The module name}';
    protected $description = 'Create a new module';

    public function handle()
    {
        $moduleName = $this->argument('name');
        $modulePath = base_path("modules/{$moduleName}");

        if (File::exists($modulePath)) {
            $this->error("Module '{$moduleName}' already exists.");
            return 1;
        }

        $this->createModuleStructure($moduleName, $modulePath);
        $this->info("Module '{$moduleName}' created successfully.");

        return 0;
    }

    protected function createModuleStructure(string $moduleName, string $modulePath): void
    {
        // Create directories
        $directories = [
            'Providers',
            'Http/Controllers',
            'Http/Middleware',
            'Database/migrations',
            'routes',
            'config',
            'resources/views',
            'resources/js'
        ];

        foreach ($directories as $directory) {
            File::makeDirectory("{$modulePath}/{$directory}", 0755, true);
        }

        // Create module.json
        $moduleConfig = [
            'name' => $moduleName,
            'description' => "The {$moduleName} module",
            'version' => '1.0.0',
            'namespace' => "Modules\\{$moduleName}",
            'providers' => ["Modules\\{$moduleName}\\Providers\\{$moduleName}ServiceProvider"],
            'dependencies' => []
        ];

        File::put(
            "{$modulePath}/module.json",
            json_encode($moduleConfig, JSON_PRETTY_PRINT)
        );

        // Create ServiceProvider
        $providerContent = $this->getServiceProviderStub($moduleName);
        File::put(
            "{$modulePath}/Providers/{$moduleName}ServiceProvider.php",
            $providerContent
        );

        // Create routes
        File::put("{$modulePath}/routes/api.php", $this->getApiRoutesStub($moduleName));
        File::put("{$modulePath}/routes/web.php", $this->getWebRoutesStub($moduleName));

        // Create config
        File::put("{$modulePath}/config/{$moduleName}.php", $this->getConfigStub($moduleName));
    }

    protected function getServiceProviderStub(string $moduleName): string
    {
        return "<?php

namespace Modules\\{$moduleName}\\Providers;

use Illuminate\Support\ServiceProvider;

class {$moduleName}ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        \$this->mergeConfigFrom(
            __DIR__ . '/../config/{$moduleName}.php',
            '{$moduleName}'
        );
    }

    public function boot(): void
    {
        \$this->loadViewsFrom(__DIR__ . '/../resources/views', '{$moduleName}');
        
        if (\$this->app->runningInConsole()) {
            \$this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
        }
    }
}";
    }

    protected function getApiRoutesStub(string $moduleName): string
    {
        $lowerName = strtolower($moduleName);
        return "<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/{$lowerName}')->group(function () {
    // Add your API routes here
});";
    }

    protected function getWebRoutesStub(string $moduleName): string
    {
        $lowerName = strtolower($moduleName);
        return "<?php

use Illuminate\Support\Facades\Route;

Route::prefix('{$lowerName}')->group(function () {
    // Add your web routes here
});";
    }

    protected function getConfigStub(string $moduleName): string
    {
        return "<?php

return [
    'name' => '{$moduleName}',
    'enabled' => true,
    
    // Add your module configuration here
];";
    }
}
