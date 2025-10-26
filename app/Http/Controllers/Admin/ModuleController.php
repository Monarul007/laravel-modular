<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Core\ModuleManager;
use App\Core\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ModuleController extends Controller
{
    public function __construct(
        protected ModuleManager $moduleManager
    ) {}

    public function index(): JsonResponse
    {
        $modules = $this->moduleManager->getAllModules();
        
        return ApiResponse::success($modules, 'Modules retrieved successfully');
    }

    public function enable(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $moduleName = $request->name;

        if (!$this->moduleManager->moduleExists($moduleName)) {
            return ApiResponse::error("Module '{$moduleName}' does not exist", 404);
        }

        if ($this->moduleManager->isModuleEnabled($moduleName)) {
            return ApiResponse::error("Module '{$moduleName}' is already enabled", 400);
        }

        if ($this->moduleManager->enableModule($moduleName)) {
            return ApiResponse::success(null, "Module '{$moduleName}' enabled successfully");
        }

        return ApiResponse::error("Failed to enable module '{$moduleName}'", 500);
    }

    public function disable(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $moduleName = $request->name;

        if (!$this->moduleManager->moduleExists($moduleName)) {
            return ApiResponse::error("Module '{$moduleName}' does not exist", 404);
        }

        if (!$this->moduleManager->isModuleEnabled($moduleName)) {
            return ApiResponse::error("Module '{$moduleName}' is already disabled", 400);
        }

        if ($this->moduleManager->disableModule($moduleName)) {
            return ApiResponse::success(null, "Module '{$moduleName}' disabled successfully");
        }

        return ApiResponse::error("Failed to disable module '{$moduleName}'", 500);
    }

    public function show(string $name): JsonResponse
    {
        if (!$this->moduleManager->moduleExists($name)) {
            return ApiResponse::error("Module '{$name}' does not exist", 404);
        }

        $config = $this->moduleManager->getModuleConfig($name);
        $config['enabled'] = $this->moduleManager->isModuleEnabled($name);

        return ApiResponse::success($config, 'Module details retrieved successfully');
    }
}
