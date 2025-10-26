<?php

namespace Modules\OTP\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Core\ApiResponse;
use App\Core\SettingsManager;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OTPController extends Controller
{
    public function __construct(
        protected SettingsManager $settings
    ) {}

    public function getConfig(): JsonResponse
    {
        $config = [
            'provider' => $this->settings->get('otp.provider', 'sms'),
            'length' => $this->settings->get('otp.code_length', 6),
            'expiry' => $this->settings->get('otp.expiry_minutes', 5),
            'enabled' => $this->settings->get('otp.enabled', true)
        ];

        return ApiResponse::success($config, 'OTP configuration retrieved');
    }

    public function updateConfig(Request $request): JsonResponse
    {
        $request->validate([
            'provider' => 'required|in:sms,email',
            'code_length' => 'required|integer|min:4|max:8',
            'expiry_minutes' => 'required|integer|min:1|max:60'
        ]);

        $this->settings->set('otp.provider', $request->provider, 'otp');
        $this->settings->set('otp.code_length', $request->code_length, 'otp');
        $this->settings->set('otp.expiry_minutes', $request->expiry_minutes, 'otp');

        return ApiResponse::success(null, 'OTP configuration updated successfully');
    }

    public function generateCode(Request $request): JsonResponse
    {
        $request->validate([
            'identifier' => 'required|string' // phone or email
        ]);

        $length = $this->settings->get('otp.code_length', 6);
        $code = str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);

        // In real implementation:
        // 1. Store code with expiry in cache/database
        // 2. Send via configured provider (SMS/Email)

        return ApiResponse::success([
            'identifier' => $request->identifier,
            'code_sent' => true,
            'expires_in' => $this->settings->get('otp.expiry_minutes', 5) * 60
        ], 'OTP code generated and sent');
    }
}