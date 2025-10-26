<?php

use Illuminate\Support\Facades\Route;
use Modules\OTP\Http\Controllers\OTPController;

Route::prefix('api/v1/otp')->group(function () {
    Route::get('config', [OTPController::class, 'getConfig']);
    Route::post('config', [OTPController::class, 'updateConfig']);
    Route::post('generate', [OTPController::class, 'generateCode']);
});