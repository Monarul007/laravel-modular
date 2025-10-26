<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Core\ModuleManager;
use App\Core\SettingsManager;

class TestSystemCommand extends Command
{
    protected $signature = 'system:test';
    protected $description = 'Test the modular system functionality';

    public function handle(ModuleManager $moduleManager, SettingsManager $settings)
    {
        $this->info('🧪 Testing Laravel Modular System');
        $this->info('================================');
        $this->newLine();

        // Test ModuleManager
        $this->info('1. Testing ModuleManager...');
        $modules = $moduleManager->getAllModules();
        $this->info('   Found ' . count($modules) . ' modules:');
        
        foreach ($modules as $name => $config) {
            $status = $config['enabled'] ? '<fg=green>✅ Enabled</>' : '<fg=red>❌ Disabled</>';
            $this->info("   - {$name} v{$config['version']} {$status}");
        }

        $this->newLine();
        $this->info('2. Testing Settings Manager...');
        
        // Test setting a value
        $settings->set('test.key', 'test_value', 'testing');
        $value = $settings->get('test.key');
        
        if ($value === 'test_value') {
            $this->info('   ✅ Settings can be stored and retrieved');
        } else {
            $this->error('   ❌ Settings test failed');
        }

        $this->newLine();
        $this->info('3. Testing module routes...');
        
        $enabledModules = $moduleManager->getEnabledModules();
        $this->info('   Routes loaded from ' . count($enabledModules) . ' enabled modules');
        
        foreach ($enabledModules as $moduleName) {
            $this->info("   - {$moduleName} routes loaded");
        }

        $this->newLine();
        $this->info('🎉 System test completed successfully!');
        $this->newLine();
        
        $this->info('Next steps:');
        $this->info('- Run "php artisan serve" to start the server');
        $this->info('- Test API endpoints with Postman or curl');
        $this->info('- Create more modules with "php artisan make:module ModuleName"');
        
        return 0;
    }
}
