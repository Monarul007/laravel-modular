<?php

use Illuminate\Support\Facades\Route;
use Modules\LoginWithPhone\Http\Controllers\PhoneAuthController;

Route::prefix('api/v1/auth/phone')->group(function () {
    Route::post('send-code', [PhoneAuthController::class, 'sendCode']);
    Route::post('verify-code', [PhoneAuthController::class, 'verifyCode']);
});