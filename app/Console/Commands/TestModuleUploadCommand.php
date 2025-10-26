<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Core\ModuleManager;

class TestModuleUploadCommand extends Command
{
    protected $signature = 'test:module-upload {zipfile}';
    protected $description = 'Test module upload from ZIP file';

    public function handle(ModuleManager $moduleManager)
    {
        $zipFile = $this->argument('zipfile');
        
        if (!file_exists($zipFile)) {
            $this->error("ZIP file '{$zipFile}' does not exist");
            return 1;
        }

        $this->info("Testing module upload from: {$zipFile}");
        
        $result = $moduleManager->installModuleFromZip($zipFile);
        
        if ($result['success']) {
            $this->info("✅ " . $result['message']);
            
            if (isset($result['module'])) {
                $module = $result['module'];
                $this->info("Module Details:");
                $this->info("- Name: {$module['name']}");
                $this->info("- Version: {$module['version']}");
                $this->info("- Description: {$module['description']}");
            }
        } else {
            $this->error("❌ " . $result['message']);
            return 1;
        }

        return 0;
    }
}
