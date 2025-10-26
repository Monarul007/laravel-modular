<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminModuleController;
use App\Http\Controllers\Admin\AdminSettingsController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Panel Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Module Management
    Route::prefix('modules')->name('modules.')->group(function () {
        Route::get('/', [AdminModuleController::class, 'index'])->name('index');
        Route::post('/enable', [AdminModuleController::class, 'enable'])->name('enable');
        Route::post('/disable', [AdminModuleController::class, 'disable'])->name('disable');
        Route::post('/upload', [AdminModuleController::class, 'upload'])->name('upload');
        Route::post('/uninstall', [AdminModuleController::class, 'uninstall'])->name('uninstall');
        Route::get('/download/{name}', [AdminModuleController::class, 'download'])->name('download');
    });
    
    // Settings Management
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [AdminSettingsController::class, 'index'])->name('index');
        Route::post('/{group}', [AdminSettingsController::class, 'update'])->name('update');
    });
});
