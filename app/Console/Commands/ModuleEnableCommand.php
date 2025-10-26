<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Core\ModuleManager;

class ModuleEnableCommand extends Command
{
    protected $signature = 'module:enable {name : The module name}';
    protected $description = 'Enable a module';

    public function handle(ModuleManager $moduleManager)
    {
        $moduleName = $this->argument('name');

        if (!$moduleManager->moduleExists($moduleName)) {
            $this->error("Module '{$moduleName}' does not exist.");
            return 1;
        }

        if ($moduleManager->isModuleEnabled($moduleName)) {
            $this->info("Module '{$moduleName}' is already enabled.");
            return 0;
        }

        if ($moduleManager->enableModule($moduleName)) {
            $this->info("Module '{$moduleName}' has been enabled.");
            return 0;
        }

        $this->error("Failed to enable module '{$moduleName}'.");
        return 1;
    }
}
