# API Testing Examples

Here are some curl examples to test the modular system APIs:

## 1. Start the Laravel server
```bash
php artisan serve
```

## 2. Test Module Management APIs

### List all modules
```bash
curl -X GET http://localhost:8000/api/api/v1/admin/modules
```

### Get specific module details
```bash
curl -X GET http://localhost:8000/api/api/v1/admin/modules/LoginWithPhone
```

### Enable a module
```bash
curl -X POST http://localhost:8000/api/api/v1/admin/modules/enable \
  -H "Content-Type: application/json" \
  -d '{"name": "Product"}'
```

### Disable a module
```bash
curl -X POST http://localhost:8000/api/api/v1/admin/modules/disable \
  -H "Content-Type: application/json" \
  -d '{"name": "Product"}'
```

## 3. Test Settings Management APIs

### Get settings for a group
```bash
curl -X GET http://localhost:8000/api/api/v1/admin/settings/otp
```

### Update settings for a group
```bash
curl -X POST http://localhost:8000/api/api/v1/admin/settings/otp \
  -H "Content-Type: application/json" \
  -d '{"provider": "email", "code_length": 8, "expiry_minutes": 10}'
```

### Get a single setting
```bash
curl -X GET http://localhost:8000/api/api/v1/admin/setting/otp.provider
```

### Set a single setting
```bash
curl -X POST http://localhost:8000/api/api/v1/admin/setting \
  -H "Content-Type: application/json" \
  -d '{"key": "app.name", "value": "My Modular App", "group": "general"}'
```

## 4. Test Module-Specific APIs

### LoginWithPhone Module APIs

#### Send verification code
```bash
curl -X POST http://localhost:8000/api/v1/auth/phone/send-code \
  -H "Content-Type: application/json" \
  -d '{"phone": "+1234567890"}'
```

#### Verify code
```bash
curl -X POST http://localhost:8000/api/v1/auth/phone/verify-code \
  -H "Content-Type: application/json" \
  -d '{"phone": "+1234567890", "code": "123456"}'
```

### OTP Module APIs

#### Get OTP configuration
```bash
curl -X GET http://localhost:8000/api/v1/otp/config
```

#### Update OTP configuration
```bash
curl -X POST http://localhost:8000/api/v1/otp/config \
  -H "Content-Type: application/json" \
  -d '{"provider": "sms", "code_length": 6, "expiry_minutes": 5}'
```

#### Generate OTP code
```bash
curl -X POST http://localhost:8000/api/v1/otp/generate \
  -H "Content-Type: application/json" \
  -d '{"identifier": "user@example.com"}'
```

## Expected Response Format

All APIs return responses in this standardized format:

### Success Response
```json
{
  "success": true,
  "message": "Success message",
  "data": {
    // Response data here
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    // Validation errors if any
  }
}
```

## Testing Workflow

1. **Start server**: `php artisan serve`
2. **List modules**: Check what modules are available
3. **Enable modules**: Enable the modules you want to test
4. **Test module APIs**: Use the module-specific endpoints
5. **Manage settings**: Configure module settings via API
6. **Disable modules**: Turn off modules when not needed

This demonstrates the plug-and-play nature of the system - modules can be enabled/disabled dynamically without affecting the core system.