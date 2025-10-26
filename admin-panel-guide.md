# Admin Panel Guide

The Laravel Modular System includes a complete admin panel built with Inertia.js + Vue 3 for managing modules and settings.

## 🚀 Accessing the Admin Panel

1. **Start the Laravel server:**
   ```bash
   php artisan serve
   ```

2. **Visit the admin panel:**
   ```
   http://localhost:8000/admin
   ```

## 📊 Dashboard

The dashboard provides an overview of your modular system:

- **Total Modules**: Shows count of all available modules
- **Enabled Modules**: Shows count of currently active modules  
- **Settings Groups**: Shows number of configuration groups
- **Recent Activity**: System status information

**URL:** `/admin`

## 🧩 Module Management

### Features:
- **View all modules** with their status, version, and dependencies
- **Enable/Disable modules** with one click
- **Real-time status updates** using Inertia.js
- **Dependency tracking** shows module relationships

### Actions:
- **Enable Module**: Click "Enable" button next to disabled modules
- **Disable Module**: Click "Disable" button next to enabled modules
- **Refresh**: Updates the module list from the filesystem

**URL:** `/admin/modules`

### Module Information Displayed:
- Module name and description
- Version number
- Current status (Enabled/Disabled)
- Dependencies (if any)
- Quick action buttons

## ⚙️ Settings Management

### Features:
- **Grouped settings** organized by functionality
- **Dynamic form generation** based on setting types
- **Add new settings** on the fly
- **Type-aware inputs** (text, number, boolean, textarea)

### Settings Groups:
- **General**: Core application settings
- **OTP**: One-time password configuration
- **System**: System-level configuration

### Actions:
- **Switch Groups**: Click tabs to switch between setting groups
- **Edit Settings**: Modify values directly in the form
- **Add New Setting**: Use the "Add New Setting" section
- **Save Changes**: Click "Save Settings" to persist changes

**URL:** `/admin/settings`

## 🎨 UI Features

### Design:
- **Responsive design** works on desktop and mobile
- **Tailwind CSS** for consistent styling
- **Clean navigation** with active state indicators
- **Status badges** for quick visual feedback

### User Experience:
- **Real-time updates** without page refreshes
- **Loading states** for better feedback
- **Error handling** with user-friendly messages
- **Success notifications** for completed actions

## 🔧 Technical Details

### Frontend Stack:
- **Vue 3** with Composition API
- **Inertia.js** for SPA-like experience
- **Tailwind CSS** for styling
- **Vite** for fast builds

### Backend Integration:
- **Laravel controllers** handle business logic
- **Inertia responses** return data to Vue components
- **Form validation** with Laravel validation rules
- **Flash messages** for user feedback

## 🚦 Getting Started

1. **Build frontend assets:**
   ```bash
   npm run build
   # or for development
   npm run dev
   ```

2. **Ensure modules are created:**
   ```bash
   php artisan make:module YourModule
   ```

3. **Access admin panel:**
   ```
   http://localhost:8000/admin
   ```

4. **Manage modules:**
   - Go to Modules section
   - Enable/disable as needed
   - Check dependencies

5. **Configure settings:**
   - Go to Settings section
   - Modify configuration values
   - Add new settings if needed

## 🔄 Development Workflow

### Adding New Admin Features:

1. **Create Controller:**
   ```bash
   php artisan make:controller Admin/YourController
   ```

2. **Add Routes:**
   ```php
   // routes/web.php
   Route::get('/admin/your-feature', [YourController::class, 'index']);
   ```

3. **Create Vue Component:**
   ```vue
   <!-- resources/js/Pages/Admin/YourFeature.vue -->
   <template>
     <Layout>
       <!-- Your admin interface -->
     </Layout>
   </template>
   ```

4. **Update Navigation:**
   ```vue
   <!-- resources/js/Pages/Admin/Layout.vue -->
   <!-- Add new navigation link -->
   ```

### Customizing the Admin Panel:

- **Styling**: Modify Tailwind classes in Vue components
- **Layout**: Update `resources/js/Pages/Admin/Layout.vue`
- **Navigation**: Add new menu items in the layout component
- **Components**: Create reusable Vue components in `resources/js/Components/`

## 🎯 Benefits

- **WordPress-like experience**: Familiar module management interface
- **Real-time updates**: No page refreshes needed
- **Type safety**: Vue 3 with proper prop validation
- **Responsive**: Works on all device sizes
- **Extensible**: Easy to add new admin features

The admin panel provides a complete interface for managing your modular Laravel application, making it easy to enable/disable features and configure settings without touching code.