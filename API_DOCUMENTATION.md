# 🚗 Car Tracking System - API Documentation

## 📋 Table of Contents
- [Overview](#overview)
- [Technology Stack](#technology-stack)
- [API Base URL](#api-base-url)
- [Authentication](#authentication)
- [Security Middleware](#security-middleware)
- [API Endpoints](#api-endpoints)
  - [Vehicle Management](#vehicle-management)
  - [Location Tracking](#location-tracking)
  - [Booking Management](#booking-management)
  - [Cart System](#cart-system)
  - [Admin Management](#admin-management)
- [Security Analysis](#security-analysis)
- [Request/Response Examples](#requestresponse-examples)
- [Error Handling](#error-handling)

---

## 🎯 Overview

This is a **Laravel-based Car Tracking System** with a comprehensive REST API for managing vehicles, tracking locations, handling bookings, and administrative operations.

### 💡 Why This Project?

This project was created to **solve reservation management problems** and make vehicle booking easier. Traditional car rental and fleet management systems often struggle with:

- **Complex Reservation Processes**: Lengthy booking procedures that frustrate users
- **Poor Tracking**: Difficulty monitoring vehicle locations and availability in real-time
- **Manual Management**: Time-consuming manual processes for approvals and updates
- **Lack of Transparency**: Limited visibility into booking status and vehicle information
- **Administrative Overhead**: Inefficient tools for managing fleets and user requests

### ✨ Solution

This system provides:

✅ **Simplified Booking Flow** - Easy-to-use API for quick reservations  
✅ **Real-Time Tracking** - Live GPS location monitoring for all vehicles  
✅ **Automated Workflows** - Cart system with approval mechanisms  
✅ **Centralized Management** - Single dashboard for all administrative tasks  
✅ **Scalable Architecture** - RESTful API design for web and mobile integration  
✅ **Role-Based Access** - Secure admin and user separation

## 🛠 Technology Stack

- **Framework**: Laravel 12.0
- **PHP Version**: 8.2+
- **Authentication**: Laravel Sanctum (Token-based API authentication)
- **Database**: MySQL/PostgreSQL/SQLite (configurable)
- **API Style**: RESTful JSON API
- **Image Upload**: Supported with validation

## 🌐 API Base URL

All API endpoints are prefixed with:
```
/api/v1
```

Example: `https://yourdomain.com/api/v1/vehicles`

---

## 🔐 Authentication

### Authentication Methods

1. **Laravel Sanctum** - Token-based authentication for API requests
2. **Session-based** - For web routes

### Getting an API Token

```http
POST /api/v1/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password123"
}
```

### Using the Token

Include the token in the `Authorization` header:
```http
Authorization: Bearer {your-api-token}
```

---

## 🛡️ Security Middleware

### Available Middleware

| Middleware | Description | Usage |
|------------|-------------|-------|
| `auth:sanctum` | Requires valid API token | User authentication |
| `role:admin` | Requires admin role | Admin-only routes |
| `admin` | Alternative admin check | Admin verification |

### Middleware Registration

Located in `bootstrap/app.php`:
```php
$middleware->alias([
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
    'role' => \App\Http\Middleware\RoleMiddleware::class,
]);
```

---

## 📡 API Endpoints

### 🚙 Vehicle Management

#### List All Vehicles
```http
GET /api/v1/vehicles
```
**Security**: ⚠️ **UNPROTECTED** - No authentication required  
**Response**: Array of active vehicles with latest location

#### Create Vehicle
```http
POST /api/v1/vehicles
Content-Type: multipart/form-data

{
  "vehicle_number": "ABC-123",
  "driver_name": "John Doe",
  "vehicle_type": "Sedan",
  "phone": "+1234567890",
  "vehicle_image": <file>
}
```
**Security**: ⚠️ **UNPROTECTED** - No authentication required  
**Validation**:
- `vehicle_number`: Required, unique
- `driver_name`: Required
- `vehicle_type`: Required
- `phone`: Optional
- `vehicle_image`: Optional, max 2MB (jpeg, png, jpg, gif)

#### Get Vehicle Details
```http
GET /api/v1/vehicles/{id}
```
**Security**: ⚠️ **UNPROTECTED** - No authentication required  
**Response**: Vehicle details with location history (last 50 records)

#### Update Vehicle
```http
PUT /api/v1/vehicles/{id}
POST /api/v1/vehicles/{id}/update  (for image uploads)

{
  "vehicle_number": "ABC-123",
  "driver_name": "John Doe",
  "vehicle_type": "SUV",
  "phone": "+1234567890",
  "is_active": true,
  "vehicle_image": <file>
}
```
**Security**: ⚠️ **UNPROTECTED** - No authentication required

#### Delete Vehicle
```http
DELETE /api/v1/vehicles/{id}
```
**Security**: ⚠️ **UNPROTECTED** - No authentication required

---

### 📍 Location Tracking

#### Store Vehicle Location
```http
POST /api/v1/locations

{
  "vehicle_id": 1,
  "latitude": 40.7128,
  "longitude": -74.0060,
  "recorded_at": "2025-12-23T10:30:00Z"
}
```
**Security**: ⚠️ **UNPROTECTED** - No authentication required

#### Get All Current Locations
```http
GET /api/v1/locations/current
```
**Security**: ⚠️ **UNPROTECTED** - No authentication required  
**Response**: Latest location for all vehicles

#### Get Vehicle Location History
```http
GET /api/v1/vehicles/{vehicleId}/history
```
**Security**: ⚠️ **UNPROTECTED** - No authentication required  
**Response**: Historical location data for specific vehicle

---

### 📅 Booking Management

#### List All Bookings
```http
GET /api/v1/bookings
```
**Security**: ⚠️ **UNPROTECTED** - No authentication required

#### Create Booking
```http
POST /api/v1/bookings

{
  "vehicle_id": 1,
  "user_id": 5,
  "start_date": "2025-12-25",
  "end_date": "2025-12-30",
  "notes": "Business trip"
}
```
**Security**: ⚠️ **UNPROTECTED** - No authentication required

#### Update Booking
```http
PUT /api/v1/bookings/{booking}

{
  "start_date": "2025-12-26",
  "end_date": "2025-12-31"
}
```
**Security**: ⚠️ **UNPROTECTED** - No authentication required

#### Delete Booking
```http
DELETE /api/v1/bookings/{booking}
```
**Security**: ⚠️ **UNPROTECTED** - No authentication required

#### User Booking Statistics
```http
GET /api/v1/user-bookings-stats
```
**Security**: ⚠️ **UNPROTECTED** - No authentication required

---

### 🛒 Cart System

> ✅ **All cart routes are protected with `auth:sanctum` middleware**

#### Get User Cart
```http
GET /api/v1/cart
Authorization: Bearer {token}
```
**Security**: ✅ **PROTECTED** - Requires authentication

#### Add to Cart
```http
POST /api/v1/cart/add
Authorization: Bearer {token}

{
  "item_id": 1,
  "quantity": 2
}
```
**Security**: ✅ **PROTECTED** - Requires authentication

#### Remove from Cart
```http
POST /api/v1/cart/remove
Authorization: Bearer {token}

{
  "item_id": 1
}
```
**Security**: ✅ **PROTECTED** - Requires authentication

#### Confirm Cart Item
```http
POST /api/v1/cart/confirm
Authorization: Bearer {token}

{
  "item_id": 1
}
```
**Security**: ✅ **PROTECTED** - Requires authentication

#### Clear Cart
```http
POST /api/v1/cart/clear
Authorization: Bearer {token}
```
**Security**: ✅ **PROTECTED** - Requires authentication

#### Get Cart Statistics
```http
GET /api/v1/cart/stats
Authorization: Bearer {token}
```
**Security**: ✅ **PROTECTED** - Requires authentication

#### Checkout
```http
POST /api/v1/cart/checkout
Authorization: Bearer {token}

{
  "payment_method": "credit_card",
  "billing_address": "123 Main St"
}
```
**Security**: ✅ **PROTECTED** - Requires authentication

---

### 👨‍💼 Admin Management

> ✅ **All admin routes are protected with `auth:sanctum` + `role:admin` middleware**

#### Get Notifications
```http
GET /api/v1/admin/notifications
Authorization: Bearer {admin-token}
```
**Security**: ✅ **PROTECTED** - Requires authentication + admin role

#### Get Activities
```http
GET /api/v1/admin/activities
Authorization: Bearer {admin-token}
```
**Security**: ✅ **PROTECTED** - Requires authentication + admin role

#### Get Users
```http
GET /api/v1/admin/users
Authorization: Bearer {admin-token}
```
**Security**: ✅ **PROTECTED** - Requires authentication + admin role

#### Mark Notification as Read
```http
POST /api/v1/admin/notifications/{notificationId}/read
Authorization: Bearer {admin-token}
```
**Security**: ✅ **PROTECTED** - Requires authentication + admin role

#### Approve Action
```http
POST /api/v1/admin/approve-action
Authorization: Bearer {admin-token}

{
  "action_id": 123,
  "approved": true
}
```
**Security**: ✅ **PROTECTED** - Requires authentication + admin role

#### Approve Activity
```http
POST /api/v1/admin/approve-activity
Authorization: Bearer {admin-token}

{
  "activity_id": 456,
  "status": "approved"
}
```
**Security**: ✅ **PROTECTED** - Requires authentication + admin role

#### Get Fleet Analytics
```http
GET /api/v1/admin/fleet-analytics
Authorization: Bearer {admin-token}
```
**Security**: ✅ **PROTECTED** - Requires authentication + admin role

#### Export Data
```http
GET /api/v1/admin/export-data?format=csv&type=bookings
Authorization: Bearer {admin-token}
```
**Security**: ✅ **PROTECTED** - Requires authentication + admin role

#### Clear All Notifications
```http
POST /api/v1/admin/clear-notifications
Authorization: Bearer {admin-token}
```
**Security**: ✅ **PROTECTED** - Requires authentication + admin role

#### Notify Admin
```http
POST /api/v1/admin/notify
Authorization: Bearer {admin-token}

{
  "message": "Critical system alert",
  "priority": "high"
}
```
**Security**: ✅ **PROTECTED** - Requires authentication + admin role

---

## 🚨 Security Analysis

### Critical Security Issues

> ⚠️ **WARNING**: The following endpoints have SEVERE security vulnerabilities

#### 🔴 UNPROTECTED Endpoints (High Risk)

| Category | Endpoints | Risk Level | Impact |
|----------|-----------|------------|--------|
| **Vehicle CRUD** | All vehicle operations | 🔴 CRITICAL | Anyone can create/modify/delete vehicles |
| **Location Tracking** | All location operations | 🔴 CRITICAL | Fake location data can be injected |
| **Booking Management** | All booking operations | 🔴 CRITICAL | Unauthorized access to all bookings |

### Specific Vulnerabilities

#### 1. Vehicle Management (Lines 10-11 in `routes/api.php`)
```php
// ❌ NO AUTHENTICATION OR AUTHORIZATION
Route::apiResource('vehicles', VehicleController::class);
Route::post('vehicles/{id}/update', [VehicleController::class, 'update']);
```

**Risks**:
- ❌ Anyone can view all vehicles
- ❌ Anyone can create fake vehicles
- ❌ Anyone can modify vehicle information
- ❌ Anyone can delete vehicles
- ❌ No admin verification

#### 2. Location Tracking (Lines 14-16 in `routes/api.php`)
```php
// ❌ NO AUTHENTICATION
Route::post('locations', [VehicleLocationController::class, 'store']);
Route::get('locations/current', [VehicleLocationController::class, 'getAllCurrentLocations']);
Route::get('vehicles/{vehicleId}/history', [VehicleLocationController::class, 'getVehicleHistory']);
```

**Risks**:
- ❌ Anyone can submit fake GPS coordinates
- ❌ Location data can be manipulated
- ❌ Privacy breach - all vehicle locations are public
- ❌ No device/user verification

#### 3. Booking Management (Lines 19-25 in `routes/api.php`)
```php
// ❌ NO AUTHENTICATION OR OWNERSHIP VALIDATION
Route::get('bookings', [BookingController::class, 'apiIndex']);
Route::post('bookings', [BookingController::class, 'apiStore']);
Route::put('bookings/{booking}', [BookingController::class, 'apiUpdate']);
Route::delete('bookings/{booking}', [BookingController::class, 'apiDestroy']);
Route::get('user-bookings-stats', [BookingController::class, 'userBookingStats']);
```

**Risks**:
- ❌ Anyone can view all user bookings (privacy violation)
- ❌ Anyone can create bookings for other users
- ❌ Anyone can modify/cancel other users' bookings
- ❌ No ownership validation
- ❌ Business logic can be bypassed

### ✅ Properly Secured Endpoints

#### Cart System (Lines 28-36)
```php
// ✅ PROTECTED with auth:sanctum
Route::middleware('auth:sanctum')->group(function () {
    // All cart operations require authentication
});
```

#### Admin Routes (Lines 39-50)
```php
// ✅ PROTECTED with auth:sanctum + role:admin
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // All admin operations require authentication AND admin role
});
```

---

## 🔧 Recommended Security Fixes

### 1. Protect Vehicle Routes
```php
// Add authentication and admin role
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('vehicles', VehicleController::class);
    Route::post('vehicles/{id}/update', [VehicleController::class, 'update']);
});

// Or allow read-only access for authenticated users
Route::middleware('auth:sanctum')->group(function () {
    Route::get('vehicles', [VehicleController::class, 'index']);
    Route::get('vehicles/{id}', [VehicleController::class, 'show']);
});
```

### 2. Protect Location Routes
```php
// Require authentication for location operations
Route::middleware('auth:sanctum')->group(function () {
    Route::post('locations', [VehicleLocationController::class, 'store']);
    Route::get('locations/current', [VehicleLocationController::class, 'getAllCurrentLocations']);
    Route::get('vehicles/{vehicleId}/history', [VehicleLocationController::class, 'getVehicleHistory']);
});
```

### 3. Protect Booking Routes
```php
// Require authentication for all booking operations
Route::middleware('auth:sanctum')->group(function () {
    Route::get('bookings', [BookingController::class, 'apiIndex']);
    Route::post('bookings', [BookingController::class, 'apiStore']);
    Route::put('bookings/{booking}', [BookingController::class, 'apiUpdate']);
    Route::delete('bookings/{booking}', [BookingController::class, 'apiDestroy']);
    Route::get('user-bookings-stats', [BookingController::class, 'userBookingStats']);
});
```

### 4. Add Ownership Validation in Controllers
```php
// Example: BookingController
public function apiUpdate(Request $request, Booking $booking)
{
    // Verify user owns this booking or is admin
    if ($booking->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
        abort(403, 'Unauthorized to modify this booking');
    }
    
    // Continue with update logic...
}
```

---

## 📝 Request/Response Examples

### Success Response
```json
{
  "success": true,
  "data": {
    "id": 1,
    "vehicle_number": "ABC-123",
    "driver_name": "John Doe",
    "vehicle_type": "Sedan",
    "is_active": true,
    "image_url": "/storage/vehicle-images/vehicle_1234567890.jpg",
    "latest_location": {
      "latitude": 40.7128,
      "longitude": -74.0060,
      "recorded_at": "2025-12-23T10:30:00Z"
    }
  },
  "message": "Vehicle created successfully"
}
```

### Error Response (Validation)
```json
{
  "success": false,
  "errors": {
    "vehicle_number": [
      "The vehicle number has already been taken."
    ],
    "driver_name": [
      "The driver name field is required."
    ]
  }
}
```

### Error Response (Not Found)
```json
{
  "success": false,
  "message": "Vehicle not found"
}
```

### Error Response (Unauthorized)
```json
{
  "message": "Unauthenticated."
}
```

### Error Response (Forbidden)
```json
{
  "message": "Access denied. Admin privileges required."
}
```

---

## 🐛 Error Handling

### HTTP Status Codes

| Code | Meaning | Usage |
|------|---------|-------|
| 200 | OK | Successful GET, PUT, PATCH requests |
| 201 | Created | Successful POST request |
| 204 | No Content | Successful DELETE request |
| 400 | Bad Request | Invalid request format |
| 401 | Unauthorized | Missing or invalid authentication |
| 403 | Forbidden | Insufficient permissions |
| 404 | Not Found | Resource doesn't exist |
| 422 | Unprocessable Entity | Validation errors |
| 500 | Internal Server Error | Server-side error |

---

## 📦 Installation & Setup

### System Requirements

Before installing, ensure your system meets these requirements:

| Requirement | Minimum Version | Recommended |
|-------------|----------------|-------------|
| **PHP** | 8.2 | 8.3+ |
| **Composer** | 2.0 | Latest |
| **Database** | MySQL 8.0 / PostgreSQL 13 / SQLite 3 | MySQL 8.0+ |
| **Node.js** | 18.x | 20.x LTS |
| **NPM** | 9.x | Latest |
| **Web Server** | Apache 2.4 / Nginx 1.18 | Nginx 1.24+ |

### Required PHP Extensions

Ensure these PHP extensions are enabled:
- `openssl`
- `pdo`
- `mbstring`
- `tokenizer`
- `xml`
- `ctype`
- `json`
- `bcmath`
- `fileinfo`
- `gd` (for image processing)

### Installation Steps

#### 1️⃣ Clone the Repository

```bash
git clone https://github.com/yourusername/car-tracking.git
cd car-tracking
```

#### 2️⃣ Install PHP Dependencies

```bash
composer install
```

**Note**: If you encounter memory issues, run:
```bash
php -d memory_limit=-1 /path/to/composer install
```

#### 3️⃣ Install Node Dependencies

```bash
npm install
```

Or using Yarn:
```bash
yarn install
```

#### 4️⃣ Environment Configuration

Copy the example environment file:
```bash
cp .env.example .env
```

Generate application key:
```bash
php artisan key:generate
```

#### 5️⃣ Configure Database

Edit the `.env` file with your database credentials:

**For MySQL:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=car_tracking
DB_USERNAME=root
DB_PASSWORD=your_password
```

**For PostgreSQL:**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=car_tracking
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

**For SQLite (Development):**
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

#### 6️⃣ Create Database

**MySQL:**
```bash
mysql -u root -p
CREATE DATABASE car_tracking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

**PostgreSQL:**
```bash
psql -U postgres
CREATE DATABASE car_tracking;
\q
```

**SQLite:**
```bash
touch database/database.sqlite
```

#### 7️⃣ Run Database Migrations

```bash
php artisan migrate
```

To run migrations with sample data (seeders):
```bash
php artisan migrate --seed
```

#### 8️⃣ Create Storage Symbolic Link

This links the public storage directory for uploaded images:
```bash
php artisan storage:link
```

#### 9️⃣ Set Proper Permissions

**Linux/Mac:**
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

**Windows (PowerShell as Administrator):**
```powershell
icacls storage /grant "IIS_IUSRS:(OI)(CI)F" /T
icacls bootstrap\cache /grant "IIS_IUSRS:(OI)(CI)F" /T
```

#### 🔟 Build Frontend Assets

**For Development:**
```bash
npm run dev
```

**For Production:**
```bash
npm run build
```

#### 1️⃣1️⃣ Start Development Server

```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

**To specify a custom port:**
```bash
php artisan serve --port=8080
```

### 🚀 Quick Setup (One Command)

For a fresh installation, you can use the setup script:

```bash
composer run setup
```

This will:
1. Install Composer dependencies
2. Copy `.env.example` to `.env`
3. Generate application key
4. Run migrations
5. Install NPM dependencies
6. Build assets

### 🔧 Additional Configuration

#### Configure Sanctum for API Authentication

In `.env`, set your application URL:
```env
APP_URL=http://localhost:8000
SANCTUM_STATEFUL_DOMAINS=localhost:8000
SESSION_DOMAIN=localhost
```

For production:
```env
APP_URL=https://yourdomain.com
SANCTUM_STATEFUL_DOMAINS=yourdomain.com,www.yourdomain.com
SESSION_DOMAIN=.yourdomain.com
```

#### Configure Mail Settings (Optional)

For email notifications:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@cartracking.com
MAIL_FROM_NAME="${APP_NAME}"
```

#### Configure Queue Worker (Optional)

For background jobs:
```env
QUEUE_CONNECTION=database
```

Run the queue worker:
```bash
php artisan queue:work
```

### 🐳 Docker Installation (Alternative)

If you prefer Docker:

```bash
# Using Laravel Sail
composer require laravel/sail --dev
php artisan sail:install
./vendor/bin/sail up
```

### ✅ Verify Installation

Check if everything is working:

```bash
# Run tests
php artisan test

# Check routes
php artisan route:list

# Check database connection
php artisan migrate:status
```

### 🔍 Troubleshooting

#### Issue: "Class not found" errors
**Solution:**
```bash
composer dump-autoload
php artisan clear-compiled
php artisan config:clear
php artisan cache:clear
```

#### Issue: Permission denied on storage
**Solution:**
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:www-data storage bootstrap/cache
```

#### Issue: NPM build fails
**Solution:**
```bash
rm -rf node_modules package-lock.json
npm cache clean --force
npm install
```

#### Issue: Database connection refused
**Solution:**
- Verify database service is running
- Check credentials in `.env`
- Ensure database exists
- Test connection: `php artisan tinker` then `DB::connection()->getPdo();`

### 🌐 Production Deployment

For production environments:

1. **Optimize the application:**
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

2. **Set environment to production:**
```env
APP_ENV=production
APP_DEBUG=false
```

3. **Enable HTTPS:**
```env
APP_URL=https://yourdomain.com
SESSION_SECURE_COOKIE=true
```

4. **Set up a process manager** (e.g., Supervisor) for queue workers

5. **Configure your web server** (Apache/Nginx) to point to the `public` directory

---

## 🧪 Testing

### Run Tests
```bash
php artisan test
```

### API Testing Tools
- **Postman**: Import the API collection
- **Insomnia**: REST client
- **cURL**: Command-line testing

### Example cURL Request
```bash
curl -X GET http://localhost:8000/api/v1/vehicles \
  -H "Accept: application/json" \
  -H "Authorization: Bearer {your-token}"
```

---

## 📄 License

This project is licensed under the MIT License.

---

## 🤝 Contributing

Contributions are welcome! Please:
1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Open a Pull Request

---

**Last Updated**: December 23, 2025  
**API Version**: v1  
**Laravel Version**: 12.0

