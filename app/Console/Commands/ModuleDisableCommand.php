<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Core\ModuleManager;

class ModuleDisableCommand extends Command
{
    protected $signature = 'module:disable {name : The module name}';
    protected $description = 'Disable a module';

    public function handle(ModuleManager $moduleManager)
    {
        $moduleName = $this->argument('name');

        if (!$moduleManager->moduleExists($moduleName)) {
            $this->error("Module '{$moduleName}' does not exist.");
            return 1;
        }

        if (!$moduleManager->isModuleEnabled($moduleName)) {
            $this->info("Module '{$moduleName}' is already disabled.");
            return 0;
        }

        if ($moduleManager->disableModule($moduleName)) {
            $this->info("Module '{$moduleName}' has been disabled.");
            return 0;
        }

        $this->error("Failed to disable module '{$moduleName}'.");
        return 1;
    }
}
