---
inclusion: always
---

# Laravel Modular System - Inertia.js Integration

## Context

This project uses a custom Laravel Modular System package that provides WordPress-like modular functionality. The package has full Inertia.js support.

## Key Rules for Module Development

### 1. Returning Inertia Responses from Modules

When returning Inertia responses from module controllers, ALWAYS use one of these approaches:

**Preferred: Helper function**
```php
return module_inertia('ModuleName', 'ComponentPath', $props);
```

**Alternative: Extend ModuleInertiaController**
```php
use Monarul007\LaravelModularSystem\Http\Controllers\ModuleInertiaController;

class MyController extends ModuleInertiaController
{
    protected string $moduleName = 'ModuleName';
    
    public function index()
    {
        return $this->inertia('ComponentPath', $props);
    }
}
```

**Alternative: Use Facade**
```php
use Monarul007\LaravelModularSystem\Facades\ModuleInertia;

return ModuleInertia::render('ModuleName', 'ComponentPath', $props);
```

### 2. Returning Blade Views with Inertia Directives

When returning Blade views that use `@inertiaHead` or other Inertia directives, ALWAYS use:

```php
return module_view('modulename', 'viewname', $data);
```

**NEVER use:**
```php
return view('modulename::viewname', $data); // ❌ This causes "$page undefined" error
```

### 3. Module Structure

Modules should follow this structure:

```
modules/ModuleName/
├── Http/
│   └── Controllers/
│       └── MyController.php
├── resources/
│   ├── js/
│   │   └── Pages/
│   │       └── Component.vue
│   └── views/
│       └── page.blade.php
└── routes/
    └── web.php
```

### 4. Vue Components Location

Place Vue/React components in:
```
modules/ModuleName/resources/js/Pages/ComponentName.vue
```

Component will be accessible as: `ModuleName/ComponentName`

### 5. Vite Configuration

The project's `vite.config.js` should include:

```javascript
import { glob } from 'glob';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                ...glob.sync('modules/*/resources/js/Pages/**/*.vue'),
            ],
        }),
        vue(),
    ],
});
```

## Common Issues and Solutions

### Issue: "Undefined variable $page"

**Cause:** Using `view('module::name')` instead of `module_view()`

**Solution:**
```php
// ❌ Wrong
return view('blog::welcome');

// ✅ Correct
return module_view('blog', 'welcome');
```

### Issue: "Component not found"

**Cause:** Component not in correct location or Vite not configured

**Solution:**
1. Check component is at: `modules/Module/resources/js/Pages/Component.vue`
2. Verify Vite config includes module components
3. Run `npm run build`

## Code Generation

When generating module controllers that use Inertia:

1. **Always extend ModuleInertiaController** if the controller will use Inertia
2. **Set the $moduleName property** to the module name
3. **Use $this->inertia()** for Inertia responses
4. **Use $this->moduleView()** for Blade views with Inertia support

Example:
```php
<?php

namespace Modules\Blog\Http\Controllers;

use Monarul007\LaravelModularSystem\Http\Controllers\ModuleInertiaController;

class PostController extends ModuleInertiaController
{
    protected string $moduleName = 'Blog';
    
    public function index()
    {
        return $this->inertia('Posts/Index', [
            'posts' => Post::all()
        ]);
    }
    
    public function about()
    {
        return $this->moduleView('about', [
            'title' => 'About'
        ]);
    }
}
```

## Available Helper Functions

- `module_inertia($moduleName, $component, $props)` - Return Inertia response
- `module_view($moduleName, $view, $data)` - Return Blade view with Inertia support

## Available Classes

- `Monarul007\LaravelModularSystem\Http\Controllers\ModuleInertiaController` - Base controller
- `Monarul007\LaravelModularSystem\Facades\ModuleInertia` - Facade for Inertia helper
- `Monarul007\LaravelModularSystem\Core\InertiaHelper` - Core Inertia logic

## Documentation

Full documentation: `packages/laravel-modular-system/INERTIA-INTEGRATION.md`

## Important Notes

1. **Never use `view()` directly** for module views that use Inertia directives
2. **Always use proper component namespacing**: `ModuleName/ComponentPath`
3. **Component paths are case-sensitive**: Use PascalCase for component names
4. **Module names in helpers are case-sensitive**: Match the actual module folder name
5. **Build assets after changes**: Run `npm run build` after adding/modifying components

## Documentation Rules

**NEVER create more than one documentation file per feature/change.** 

- Consolidate all information into a single comprehensive document
- Update existing documentation instead of creating new files
- If multiple docs exist, merge them into one
