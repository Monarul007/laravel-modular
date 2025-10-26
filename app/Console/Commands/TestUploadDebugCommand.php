<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestUploadDebugCommand extends Command
{
    protected $signature = 'test:upload-debug';
    protected $description = 'Debug upload functionality';

    public function handle()
    {
        $this->info('🔍 Debugging Upload Functionality');
        $this->info('================================');
        $this->newLine();

        // Check storage directory
        $storageDir = storage_path('app/temp_modules');
        $this->info("1. Storage Directory: {$storageDir}");
        
        if (file_exists($storageDir)) {
            $this->info('   ✅ Directory exists');
        } else {
            $this->error('   ❌ Directory does not exist');
            $this->info('   Creating directory...');
            if (mkdir($storageDir, 0755, true)) {
                $this->info('   ✅ Directory created successfully');
            } else {
                $this->error('   ❌ Failed to create directory');
                return 1;
            }
        }

        // Check if writable
        if (is_writable($storageDir)) {
            $this->info('   ✅ Directory is writable');
        } else {
            $this->error('   ❌ Directory is not writable');
        }

        $this->newLine();

        // Test file creation
        $testFile = $storageDir . DIRECTORY_SEPARATOR . 'test_' . time() . '.txt';
        $this->info("2. Testing file creation: {$testFile}");
        
        if (file_put_contents($testFile, 'test content')) {
            $this->info('   ✅ File creation successful');
            
            if (file_exists($testFile)) {
                $this->info('   ✅ File exists after creation');
                unlink($testFile);
                $this->info('   ✅ File cleanup successful');
            } else {
                $this->error('   ❌ File does not exist after creation');
            }
        } else {
            $this->error('   ❌ File creation failed');
        }

        $this->newLine();

        // Check ZIP file
        $zipFile = 'DemoShop-v1.2.0.zip';
        $this->info("3. Testing ZIP file: {$zipFile}");
        
        if (file_exists($zipFile)) {
            $this->info('   ✅ ZIP file exists');
            
            $zip = new \ZipArchive();
            if ($zip->open($zipFile) === TRUE) {
                $this->info('   ✅ ZIP file is valid');
                $this->info("   📦 Contains {$zip->numFiles} files");
                $zip->close();
            } else {
                $this->error('   ❌ ZIP file is invalid');
            }
        } else {
            $this->error('   ❌ ZIP file not found');
            $this->info('   💡 Make sure DemoShop-v1.2.0.zip exists in the project root');
        }

        $this->newLine();

        // Check PHP upload settings
        $this->info('4. PHP Upload Settings:');
        $this->info('   file_uploads: ' . (ini_get('file_uploads') ? 'On' : 'Off'));
        $this->info('   upload_max_filesize: ' . ini_get('upload_max_filesize'));
        $this->info('   post_max_size: ' . ini_get('post_max_size'));
        $this->info('   max_execution_time: ' . ini_get('max_execution_time'));

        $this->newLine();
        $this->info('🎉 Debug completed!');

        return 0;
    }
}
