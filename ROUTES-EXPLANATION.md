# Routes Explanation

## ✅ Package Routes ARE Working!

The package routes are correctly registered. You were looking for them at the wrong path.

### Package Routes Location

The package routes are at:
```
v1/admin/modules
v1/admin/settings/{group}
etc.
```

**NOT** at `api/v1/admin/modules`

### Why?

When you use `loadRoutesFrom()` in a service provider, Laravel doesn't automatically add the `api/` prefix. That prefix is only added to routes defined in `routes/api.php` of your main application.

### Accessing Package Routes

```bash
# List package routes
php artisan route:list --path=v1/admin

# Access via HTTP
http://localhost:8000/v1/admin/modules
http://localhost:8000/v1/admin/settings/general
```

### Test the Routes

```bash
# Start server
php artisan serve

# Test in another terminal
curl http://localhost:8000/v1/admin/modules
curl -X POST http://localhost:8000/v1/admin/modules/enable \
  -H "Content-Type: application/json" \
  -d '{"name": "TestModule"}'
```

## Route Comparison

### Your Original App Routes (from routes/api.php)
```
api/api/v1/admin/modules  → Admin\ModuleController
```
These have double `api` because:
1. Laravel adds `api/` prefix automatically
2. Your routes file also has `api/v1/admin` prefix

### Package Routes (from service provider)
```
v1/admin/modules  → Monarul007\LaravelModularSystem\Http\Controllers\ModuleController
```
These are correct! No double prefix.

## Solution Options

### Option 1: Use Package Routes As-Is (Recommended)
Access at: `http://localhost:8000/v1/admin/modules`

This is the cleanest approach.

### Option 2: Add API Prefix to Package Routes

If you want package routes at `api/v1/admin/*`, update the config:

```php
// config/modular-system.php
'api_prefix' => 'api/v1/admin',
```

Then routes will be at:
```
http://localhost:8000/api/v1/admin/modules
```

### Option 3: Use API Middleware

If you want the routes to go through Laravel's API middleware, you need to modify the service provider to register routes differently.

## Current Working Routes

Run this to see all package routes:
```bash
php artisan route:list --path=v1/admin
```

You should see:
```
GET    v1/admin/modules
POST   v1/admin/modules/enable
POST   v1/admin/modules/disable
POST   v1/admin/modules/upload
POST   v1/admin/modules/uninstall
GET    v1/admin/modules/download/{name}
GET    v1/admin/modules/{name}
GET    v1/admin/settings/{group}
POST   v1/admin/settings/{group}
GET    v1/admin/setting/{key}
POST   v1/admin/setting
```

## Testing

```bash
# Start server
php artisan serve

# In another terminal, test the routes:

# List modules
curl http://localhost:8000/v1/admin/modules

# Get specific module
curl http://localhost:8000/v1/admin/modules/TestModule

# Enable module
curl -X POST http://localhost:8000/v1/admin/modules/enable \
  -H "Content-Type: application/json" \
  -d '{"name": "TestModule"}'

# Get settings
curl http://localhost:8000/v1/admin/settings/general

# Set setting
curl -X POST http://localhost:8000/v1/admin/setting \
  -H "Content-Type: application/json" \
  -d '{"key": "test_key", "value": "test_value", "group": "general"}'
```

## Summary

✅ **Routes ARE working!**  
✅ **They're at `v1/admin/*` not `api/v1/admin/*`**  
✅ **This is correct behavior for package routes**  

If you want them at `api/v1/admin/*`, change the config to:
```php
'api_prefix' => 'api/v1/admin',
```

But the current setup (`v1/admin/*`) is cleaner and avoids conflicts with your app's existing routes.
