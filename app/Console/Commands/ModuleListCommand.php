<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Core\ModuleManager;

class ModuleListCommand extends Command
{
    protected $signature = 'module:list';
    protected $description = 'List all available modules';

    public function handle(ModuleManager $moduleManager)
    {
        $modules = $moduleManager->getAllModules();

        if (empty($modules)) {
            $this->info('No modules found.');
            return;
        }

        $this->table(
            ['Name', 'Version', 'Status', 'Description'],
            collect($modules)->map(function ($config, $name) {
                return [
                    $name,
                    $config['version'] ?? 'N/A',
                    $config['enabled'] ? '<fg=green>Enabled</>' : '<fg=red>Disabled</>',
                    $config['description'] ?? 'N/A'
                ];
            })->toArray()
        );
    }
}
