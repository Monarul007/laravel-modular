# Laravel Modular System

A Laravel-based modular system with **WordPress-like plug-and-play functionality**. Upload, enable, disable, and manage modules dynamically through a web interface without touching code.

## 🚀 Quick Start

```bash
# 1. Install dependencies
composer install && npm install

# 2. Setup database
php artisan migrate

# 3. Build frontend assets
npm run build

# 4. Start the server
php artisan serve

# 5. Access admin panel
# Visit: http://localhost:8000/admin
```

## ✨ Key Features

- 🔌 **Module Upload**: Drag-and-drop ZIP files to install modules
- ⚡ **Hot-swappable**: Enable/disable modules without restart
- 🎛️ **Admin Panel**: Vue.js interface for module management
- 🔧 **Settings Manager**: Dynamic configuration system
- 📡 **API-First**: RESTful endpoints for SPA frontends
- 🛠️ **CLI Tools**: Artisan commands for developers

## 📦 Module Management

### Web Interface (Recommended)
```
http://localhost:8000/admin/modules
```
- **Upload**: Drag ZIP files to install new modules
- **Enable/Disable**: One-click module activation
- **Download**: Export modules as ZIP files
- **Uninstall**: Complete module removal

### CLI Commands
```bash
php artisan module:list                    # List all modules
php artisan make:module YourModule         # Create new module
php artisan module:enable YourModule       # Enable module
php artisan module:disable YourModule      # Disable module
php artisan test:module-upload module.zip  # Test ZIP upload
```

## 🧪 Demo Module Testing

A **DemoShop** module is included for testing:

```bash
# 1. Upload the demo module
# Visit: http://localhost:8000/admin/modules
# Upload: DemoShop-v1.2.0.zip (included in project root)

# 2. Enable the module via admin panel or CLI
php artisan module:enable DemoShop

# 3. Test the APIs
curl http://localhost:8000/api/v1/shop/products
curl -X POST http://localhost:8000/api/v1/shop/cart/add \
  -H "Content-Type: application/json" \
  -d '{"product_id": 1, "quantity": 2}'
```

## 🏗️ Creating Modules

### 1. Generate Module Structure
```bash
php artisan make:module YourModule
```

### 2. Module Files Created
```
modules/YourModule/
├── module.json                 # Module configuration
├── Providers/ServiceProvider   # Laravel service provider
├── Http/Controllers/           # API controllers
├── routes/api.php             # API routes
├── routes/web.php             # Web routes
└── config/YourModule.php      # Module settings
```

### 3. Package & Distribute
```bash
# Create ZIP for distribution
zip -r YourModule.zip modules/YourModule/

# Others can install via admin panel upload
```

## 🎛️ Admin Panel Features

### Dashboard (`/admin`)
- System overview and statistics
- Module status monitoring

### Module Management (`/admin/modules`)
- Upload ZIP files (drag-and-drop)
- Enable/disable modules
- Download modules as ZIP
- Uninstall modules completely

### Settings Management (`/admin/settings`)
- Configure module settings
- Grouped configuration (General, OTP, System)
- Add new settings dynamically

## 🔧 System Testing

```bash
# Test system functionality
php artisan system:test

# Debug upload issues
php artisan test:upload-debug

# Test specific module upload
php artisan test:module-upload DemoShop-v1.2.0.zip
```

## 📡 API Endpoints

### Module Management APIs
```bash
GET    /api/v1/admin/modules           # List modules
POST   /api/v1/admin/modules/enable    # Enable module
POST   /api/v1/admin/modules/disable   # Disable module
```

### Settings APIs
```bash
GET    /api/v1/admin/settings/{group}  # Get settings group
POST   /api/v1/admin/settings/{group}  # Update settings
```

### Example Module APIs (when DemoShop is enabled)
```bash
GET    /api/v1/shop/products           # List products
POST   /api/v1/shop/cart/add           # Add to cart
GET    /api/v1/shop/cart               # Get cart
```

## 🎯 Real-World Usage

### For Business Users
1. **Install Features**: Upload module ZIP files via admin panel
2. **Manage Features**: Enable/disable functionality as needed
3. **Configure Settings**: Adjust module behavior through web interface

### For Developers
1. **Create Modules**: Use `make:module` command
2. **Test Locally**: Enable/disable via CLI
3. **Distribute**: Package as ZIP for easy installation

### For SaaS Applications
- **Subscription Features**: Enable modules based on user plans
- **Custom Deployments**: Different module combinations per client
- **Feature Rollouts**: Gradual feature deployment

## 🔒 Security & Performance

- ✅ File validation (ZIP only, 2MB max)
- ✅ Module structure validation
- ✅ Automatic cleanup on failures
- ✅ Hot-swappable without restart
- ✅ No core code modification required

## 🎉 Benefits Delivered

- **WordPress-like Experience**: Familiar plugin management
- **Zero Downtime**: Install/manage modules without restart
- **Developer Friendly**: Standard Laravel patterns
- **Business Friendly**: Non-technical users can manage features
- **Scalable Architecture**: Add unlimited modules

**Perfect for**: SaaS applications, multi-tenant systems, feature-rich platforms, and any Laravel project requiring dynamic functionality management.