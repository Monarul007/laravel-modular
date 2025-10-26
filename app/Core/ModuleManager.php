<?php

namespace App\Core;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class ModuleManager
{
    protected array $enabledModules = [];
    protected array $moduleConfigs = [];

    public function __construct()
    {
        $this->loadEnabledModules();
    }

    public function loadEnabledModules(): void
    {
        $enabledModules = Cache::remember('enabled_modules', 3600, function () {
            $configPath = base_path('modules/enabled.json');

            if (!File::exists($configPath)) {
                File::put($configPath, json_encode([]));
                return [];
            }

            return json_decode(File::get($configPath), true) ?? [];
        });

        $this->enabledModules = $enabledModules;
    }

    public function getEnabledModules(): array
    {
        return $this->enabledModules;
    }

    public function isModuleEnabled(string $moduleName): bool
    {
        return in_array($moduleName, $this->enabledModules);
    }

    public function enableModule(string $moduleName): bool
    {
        if (!$this->moduleExists($moduleName)) {
            return false;
        }

        if (!in_array($moduleName, $this->enabledModules)) {
            $this->enabledModules[] = $moduleName;
            $this->saveEnabledModules();
            Cache::forget('enabled_modules');
        }

        return true;
    }

    public function disableModule(string $moduleName): bool
    {
        $key = array_search($moduleName, $this->enabledModules);

        if ($key !== false) {
            unset($this->enabledModules[$key]);
            $this->enabledModules = array_values($this->enabledModules);
            $this->saveEnabledModules();
            Cache::forget('enabled_modules');
        }

        return true;
    }

    public function getModuleConfig(string $moduleName): ?array
    {
        if (isset($this->moduleConfigs[$moduleName])) {
            return $this->moduleConfigs[$moduleName];
        }

        $configPath = base_path("modules/{$moduleName}/module.json");

        if (!File::exists($configPath)) {
            return null;
        }

        $config = json_decode(File::get($configPath), true);
        $this->moduleConfigs[$moduleName] = $config;

        return $config;
    }

    public function getAllModules(): array
    {
        $modulesPath = base_path('modules');

        if (!File::exists($modulesPath)) {
            return [];
        }

        $modules = [];
        $directories = File::directories($modulesPath);

        foreach ($directories as $directory) {
            $moduleName = basename($directory);
            $config = $this->getModuleConfig($moduleName);

            if ($config) {
                $modules[$moduleName] = array_merge($config, [
                    'enabled' => $this->isModuleEnabled($moduleName),
                    'path' => $directory
                ]);
            }
        }

        return $modules;
    }

    public function moduleExists(string $moduleName): bool
    {
        return File::exists(base_path("modules/{$moduleName}/module.json"));
    }

    protected function saveEnabledModules(): void
    {
        $configPath = base_path('modules/enabled.json');
        File::put($configPath, json_encode($this->enabledModules, JSON_PRETTY_PRINT));
    }

    public function bootModules(): void
    {
        foreach ($this->enabledModules as $moduleName) {
            $this->bootModule($moduleName);
        }
    }

    protected function bootModule(string $moduleName): void
    {
        $config = $this->getModuleConfig($moduleName);

        if (!$config || !isset($config['providers'])) {
            return;
        }

        foreach ($config['providers'] as $provider) {
            if (class_exists($provider)) {
                app()->register($provider);
            }
        }
    }

    public function installModuleFromZip(string $zipPath, string $moduleName = null): array
    {
        $zip = new \ZipArchive();

        if ($zip->open($zipPath) !== TRUE) {
            return ['success' => false, 'message' => 'Could not open ZIP file'];
        }

        // Extract to temporary directory first
        $tempDir = sys_get_temp_dir() . '/module_' . uniqid();

        if (!$zip->extractTo($tempDir)) {
            $zip->close();
            return ['success' => false, 'message' => 'Could not extract ZIP file'];
        }

        $zip->close();

        // Find module.json in extracted files
        $moduleJsonPath = $this->findModuleJson($tempDir);

        if (!$moduleJsonPath) {
            File::deleteDirectory($tempDir);
            return ['success' => false, 'message' => 'No module.json found in ZIP file'];
        }

        // Read module configuration
        $moduleConfig = json_decode(File::get($moduleJsonPath), true);

        if (!$moduleConfig || !isset($moduleConfig['name'])) {
            File::deleteDirectory($tempDir);
            return ['success' => false, 'message' => 'Invalid module.json format'];
        }

        $detectedModuleName = $moduleConfig['name'];
        $finalModuleName = $moduleName ?: $detectedModuleName;

        // Check if module already exists
        if ($this->moduleExists($finalModuleName)) {
            File::deleteDirectory($tempDir);
            return ['success' => false, 'message' => "Module '{$finalModuleName}' already exists"];
        }

        // Move to modules directory
        $moduleDir = base_path("modules/{$finalModuleName}");
        $extractedModuleDir = dirname($moduleJsonPath);



        // Ensure target directory doesn't exist
        if (File::exists($moduleDir)) {
            File::deleteDirectory($tempDir);
            return ['success' => false, 'message' => "Target directory already exists: {$moduleDir}"];
        }

        // Create parent directory if it doesn't exist
        File::ensureDirectoryExists(dirname($moduleDir));

        // Try copying instead of moving first
        if (!File::copyDirectory($extractedModuleDir, $moduleDir)) {
            File::deleteDirectory($tempDir);
            return ['success' => false, 'message' => "Could not copy module files from {$extractedModuleDir} to {$moduleDir}"];
        }

        // Clean up temp directory
        File::deleteDirectory($tempDir);

        // Update module name in module.json if different
        if ($finalModuleName !== $detectedModuleName) {
            $moduleConfig['name'] = $finalModuleName;
            File::put("{$moduleDir}/module.json", json_encode($moduleConfig, JSON_PRETTY_PRINT));
        }

        return [
            'success' => true,
            'message' => "Module '{$finalModuleName}' installed successfully",
            'module' => $moduleConfig
        ];
    }

    protected function findModuleJson(string $directory): ?string
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory)
        );

        foreach ($iterator as $file) {
            if ($file->getFilename() === 'module.json') {
                return $file->getPathname();
            }
        }

        return null;
    }

    public function uninstallModule(string $moduleName): bool
    {
        if (!$this->moduleExists($moduleName)) {
            return false;
        }

        // Disable module first
        $this->disableModule($moduleName);

        // Remove module directory
        $moduleDir = base_path("modules/{$moduleName}");

        return File::deleteDirectory($moduleDir);
    }

    public function createModuleZip(string $moduleName): ?string
    {
        if (!$this->moduleExists($moduleName)) {
            return null;
        }

        $moduleDir = base_path("modules/{$moduleName}");
        $zipPath = storage_path("app/modules/{$moduleName}.zip");

        // Ensure storage directory exists
        File::ensureDirectoryExists(dirname($zipPath));

        $zip = new \ZipArchive();

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
            return null;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($moduleDir),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($moduleDir) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();

        return $zipPath;
    }
}
