<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Core\ModuleManager;
use App\Core\SettingsManager;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function __construct(
        protected ModuleManager $moduleManager,
        protected SettingsManager $settings
    ) {}

    public function dashboard(): Response
    {
        $modules = $this->moduleManager->getAllModules();
        $enabledCount = collect($modules)->where('enabled', true)->count();
        
        $stats = [
            'totalModules' => count($modules),
            'enabledModules' => $enabledCount,
            'settingsGroups' => 3, // This could be dynamic based on actual settings groups
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats
        ]);
    }
}
