<?php

namespace Modules\LoginWithPhone\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Core\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PhoneAuthController extends Controller
{
    public function sendCode(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string|min:10|max:15'
        ]);

        // In a real implementation, you would:
        // 1. Generate and store a verification code
        // 2. Send SMS via a service like Twilio
        // 3. Return success response

        return ApiResponse::success([
            'phone' => $request->phone,
            'message' => 'Verification code sent successfully'
        ], 'Code sent to your phone');
    }

    public function verifyCode(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string',
            'code' => 'required|string|size:6'
        ]);

        // In a real implementation, you would:
        // 1. Verify the code against stored value
        // 2. Create or find user by phone
        // 3. Generate authentication token

        return ApiResponse::success([
            'phone' => $request->phone,
            'token' => 'sample-jwt-token',
            'user' => [
                'id' => 1,
                'phone' => $request->phone,
                'verified' => true
            ]
        ], 'Phone verified successfully');
    }
}