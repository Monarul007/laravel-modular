<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\SettingsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Admin API Routes
Route::prefix('api/v1/admin')->group(function () {
    // Module Management
    Route::get('modules', [ModuleController::class, 'index']);
    Route::get('modules/{name}', [ModuleController::class, 'show']);
    Route::post('modules/enable', [ModuleController::class, 'enable']);
    Route::post('modules/disable', [ModuleController::class, 'disable']);
    
    // Settings Management
    Route::get('settings/{group}', [SettingsController::class, 'getGroup']);
    Route::post('settings/{group}', [SettingsController::class, 'updateGroup']);
    Route::get('setting/{key}', [SettingsController::class, 'get']);
    Route::post('setting', [SettingsController::class, 'set']);
});