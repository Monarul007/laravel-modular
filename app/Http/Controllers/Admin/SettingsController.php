<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Core\SettingsManager;
use App\Core\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    public function __construct(
        protected SettingsManager $settings
    ) {}

    public function getGroup(string $group): JsonResponse
    {
        $settings = $this->settings->getGroup($group);
        
        return ApiResponse::success($settings, "Settings for group '{$group}' retrieved successfully");
    }

    public function updateGroup(Request $request, string $group): JsonResponse
    {
        $settings = $request->all();

        foreach ($settings as $key => $value) {
            $this->settings->set($key, $value, $group);
        }

        return ApiResponse::success(null, "Settings for group '{$group}' updated successfully");
    }

    public function get(string $key): JsonResponse
    {
        $value = $this->settings->get($key);
        
        return ApiResponse::success(['key' => $key, 'value' => $value], 'Setting retrieved successfully');
    }

    public function set(Request $request): JsonResponse
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'required',
            'group' => 'string'
        ]);

        $this->settings->set(
            $request->key,
            $request->value,
            $request->group ?? 'general'
        );

        return ApiResponse::success(null, 'Setting updated successfully');
    }
}
