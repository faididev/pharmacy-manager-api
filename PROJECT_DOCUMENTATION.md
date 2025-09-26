# Pharmacy Manager API - Complete Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [Technology Stack](#technology-stack)
3. [Project Structure](#project-structure)
4. [Database Schema](#database-schema)
5. [API Endpoints](#api-endpoints)
6. [Authentication](#authentication)
7. [Models & Relationships](#models--relationships)
8. [Business Logic](#business-logic)
9. [API Resources](#api-resources)
10. [Configuration](#configuration)
11. [Development Setup](#development-setup)
12. [Testing](#testing)
13. [Deployment](#deployment)

## Project Overview

The **Pharmacy Manager API** is a comprehensive RESTful API built with Laravel 12 for managing pharmacy operations. It provides endpoints for managing products, categories, customers, orders, and user authentication with a modern, scalable architecture.

### Key Features
- **Product Management**: CRUD operations for pharmaceutical products with inventory tracking
- **Category Management**: Organize products into categories
- **Customer Management**: Customer profiles with loyalty points system
- **Order Management**: Complete order processing with items and status tracking
- **Authentication**: Secure API authentication using Laravel Sanctum
- **API Documentation**: Auto-generated Swagger/OpenAPI documentation
- **Caching**: Intelligent caching for improved performance
- **Soft Deletes**: Safe deletion with data recovery capabilities

## Technology Stack

- **Framework**: Laravel 12.x
- **PHP Version**: 8.2+
- **Database**: MySQL/PostgreSQL/SQLite (configurable)
- **Authentication**: Laravel Sanctum
- **API Documentation**: L5-Swagger (OpenAPI 3.0)
- **Testing**: PHPUnit
- **Code Quality**: Laravel Pint
- **Development**: Laravel Sail (Docker)

### Dependencies
```json
{
  "darkaonline/l5-swagger": "^9.0",
  "laravel/framework": "^12.0",
  "laravel/sanctum": "^4.0",
  "laravel/tinker": "^2.10.1"
}
```

## Project Structure

```
pharmacy-manager-api/
├── app/
│   ├── Actions/                 # Business logic actions
│   │   ├── UpsertProductAction.php
│   │   ├── UpsertCategoryAction.php
│   │   ├── UpsertCustomerAction.php
│   │   └── UpsertOrderAction.php
│   ├── Console/                 # Artisan commands
│   ├── DTOs/                    # Data Transfer Objects
│   │   ├── Auth/
│   │   ├── UpsertProductDto.php
│   │   ├── UpsertCategoryDto.php
│   │   ├── UpsertCustomerDto.php
│   │   └── UpsertOrderDto.php
│   ├── Enums/                   # Enumerations
│   │   └── OrderStatusEnum.php
│   ├── Exceptions/               # Custom exceptions
│   ├── Http/
│   │   ├── Controllers/Api/V1/  # API controllers
│   │   ├── Filters/             # Query filters
│   │   ├── Requests/            # Form request validation
│   │   └── Resources/           # API resource transformers
│   ├── Models/                  # Eloquent models
│   ├── Providers/               # Service providers
│   └── Traits/                  # Reusable traits
├── config/                      # Configuration files
├── database/
│   ├── factories/               # Model factories
│   ├── migrations/              # Database migrations
│   └── seeders/                 # Database seeders
├── routes/                      # Route definitions
├── storage/
│   └── api-docs/                # Generated API documentation
└── tests/                       # Test suites
```

## Database Schema

### Core Tables

#### Users Table
- `id` (Primary Key)
- `uuid` (Unique identifier)
- `name` (User's full name)
- `email` (Unique email address)
- `phone` (Contact number)
- `address` (Physical address)
- `password` (Hashed password)
- `last_login_at` (Timestamp)
- `email_verified_at` (Email verification)
- `created_at`, `updated_at`, `deleted_at`

#### Categories Table
- `id` (Primary Key)
- `name` (Category name, indexed)
- `description` (Optional description)
- `created_at`, `updated_at`

#### Products Table
- `id` (Primary Key)
- `uuid` (Unique identifier, indexed)
- `sku` (Stock Keeping Unit, unique, indexed)
- `name` (Product name, indexed)
- `description` (Product description)
- `price` (Decimal price)
- `quantity` (Stock quantity)
- `total` (Total value)
- `manufacture_date` (Manufacturing date)
- `expiry_date` (Expiration date)
- `category_id` (Foreign key to categories)
- `created_at`, `updated_at`, `deleted_at`

#### Customers Table
- `id` (Primary Key)
- `user_id` (Foreign key to users)
- `loyalty_points` (Customer loyalty points)
- `created_at`, `updated_at`

#### Orders Table
- `id` (Primary Key)
- `customer_id` (Foreign key to customers)
- `order_date` (Order placement date)
- `status` (Order status: pending, completed, cancelled)
- `total_amount` (Total order amount)
- `created_at`, `updated_at`

#### Order Items Table
- `id` (Primary Key)
- `order_id` (Foreign key to orders)
- `product_id` (Foreign key to products)
- `quantity` (Item quantity)
- `price` (Item price at time of order)
- `created_at`, `updated_at`

## API Endpoints

### Authentication Endpoints

#### POST `/api/auth/login`
Authenticate user and receive access token.

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "data": {
    "token": "1|abc123..."
  },
  "message": "Authenticated",
  "status": 200
}
```

#### POST `/api/auth/register`
Register a new user account.

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john.doe@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### GET `/api/auth/user`
Get current authenticated user information.

**Headers:** `Authorization: Bearer {token}`

#### POST `/api/auth/logout`
Logout and invalidate current token.

### Product Endpoints

All product endpoints require authentication.

#### GET `/api/v1/products`
Retrieve all products with pagination and filtering.

**Query Parameters:**
- `per_page` (integer, default: 15) - Items per page
- `search` (string) - Search in name, description, or SKU
- `category_id` (integer) - Filter by category
- `include` (string) - Include relationships (e.g., "category")

**Response:**
```json
{
  "data": [
    {
      "type": "product",
      "uuid": "uuid-123",
      "attributes": {
        "name": "Paracetamol 500mg",
        "sku": "PROD-001",
        "description": "Pain relief medication",
        "price": 5.99,
        "quantity": 100,
        "manufacture_date": "2024-01-15",
        "expiry_date": "2026-01-15",
        "category_id": 1
      },
      "relationships": {
        "category": {
          "data": {
            "type": "category",
            "id": 1
          }
        }
      }
    }
  ],
  "links": {...},
  "meta": {...}
}
```

#### GET `/api/v1/products/{product}`
Get a specific product by UUID.

#### POST `/api/v1/products`
Create a new product.

**Request Body:**
```json
{
  "name": "Product Name",
  "description": "Product description",
  "price": 10.99,
  "quantity": 50,
  "manufacture_date": "2024-01-01",
  "expiry_date": "2026-01-01",
  "category_id": 1
}
```

#### PUT `/api/v1/products/{product}`
Update an existing product.

#### DELETE `/api/v1/products/{product}`
Soft delete a product.

### Category Endpoints

#### GET `/api/v1/categories`
Get all categories.

#### GET `/api/v1/categories/{category}`
Get a specific category.

#### POST `/api/v1/categories`
Create a new category.

**Request Body:**
```json
{
  "name": "Pain Relief",
  "description": "Medications for pain management"
}
```

#### PUT `/api/v1/categories/{category}`
Update a category.

#### DELETE `/api/v1/categories/{category}`
Delete a category.

### Customer Endpoints

#### GET `/api/v1/customers`
Get all customers with pagination and filtering.

**Query Parameters:**
- `per_page` (integer, default: 15)
- `search` (string) - Search in customer name or email
- `loyalty_points_min` (integer) - Minimum loyalty points
- `loyalty_points_max` (integer) - Maximum loyalty points

#### GET `/api/v1/customers/{customer}`
Get a specific customer.

#### POST `/api/v1/customers`
Create a new customer.

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "+1234567890",
  "address": "123 Main St",
  "password": "password123",
  "loyalty_points": 0
}
```

#### PUT `/api/v1/customers/{customer}`
Update a customer.

#### DELETE `/api/v1/customers/{customer}`
Delete a customer (also deletes associated user).

### Order Endpoints

#### GET `/api/v1/orders`
Get all orders with pagination and filtering.

**Query Parameters:**
- `per_page` (integer, default: 15)
- `status` (string) - Filter by order status
- `customer_id` (integer) - Filter by customer
- `date_from` (date) - Orders from date
- `date_to` (date) - Orders to date

#### GET `/api/v1/orders/{order}`
Get a specific order.

#### POST `/api/v1/orders`
Create a new order.

**Request Body:**
```json
{
  "customer_id": 1,
  "order_date": "2024-01-15",
  "status": "pending",
  "items": [
    {
      "product_id": 1,
      "quantity": 2,
      "price": 5.99
    }
  ]
}
```

#### PUT `/api/v1/orders/{order}`
Update an order.

#### DELETE `/api/v1/orders/{order}`
Delete an order.

## Authentication

The API uses **Laravel Sanctum** for authentication:

1. **Token-based Authentication**: Users receive a bearer token upon login/registration
2. **Token Expiration**: Tokens expire after 1 month
3. **Protected Routes**: All API endpoints except auth are protected
4. **Token Management**: Users can logout to invalidate tokens

### Authentication Flow
1. User registers/logs in via `/api/auth/login` or `/api/auth/register`
2. API returns a bearer token
3. Client includes token in `Authorization: Bearer {token}` header
4. API validates token for protected routes

## Models & Relationships

### User Model
- **Traits**: `HasApiTokens`, `SoftDeletes`, `HasUuid`
- **Relationships**: 
  - `hasOne(Customer::class)` - Customer profile

### Category Model
- **Relationships**: 
  - `hasMany(Product::class)` - Products in this category

### Product Model
- **Traits**: `SoftDeletes`, `HasUuid`
- **Relationships**: 
  - `belongsTo(Category::class)` - Product category
- **Auto-generation**: SKU is auto-generated if not provided

### Customer Model
- **Relationships**: 
  - `belongsTo(User::class)` - Associated user account
  - `hasMany(Order::class)` - Customer orders

### Order Model
- **Relationships**: 
  - `belongsTo(Customer::class)` - Order customer
  - `hasMany(OrderItem::class)` - Order items

### OrderItem Model
- **Relationships**: 
  - `belongsTo(Order::class)` - Parent order
  - `belongsTo(Product::class)` - Ordered product

## Business Logic

### Actions Pattern
The application uses the **Action pattern** for business logic:

#### UpsertProductAction
- Handles product creation and updates
- Auto-calculates total value if not provided
- Uses `updateOrCreate` for upsert operations

#### UpsertCustomerAction
- Manages customer creation with user account
- Uses database transactions for data consistency
- Handles password hashing

#### UpsertOrderAction
- Processes order creation and updates
- Calculates total amount from items
- Manages order items in transactions

### Data Transfer Objects (DTOs)
- **UpsertProductDto**: Product data validation and transformation
- **UpsertCustomerDto**: Customer data handling
- **UpsertOrderDto**: Order data processing
- **LoginUserDto**: Authentication data
- **RegisterUserDto**: Registration data

### Enums
- **OrderStatusEnum**: Defines order statuses (pending, completed, cancelled)

## API Resources

### JSON API Format
The API follows JSON API specification:

```json
{
  "type": "resource_type",
  "id": "resource_id",
  "attributes": {
    "field1": "value1",
    "field2": "value2"
  },
  "relationships": {
    "related_resource": {
      "data": {
        "type": "related_type",
        "id": "related_id"
      },
      "links": {
        "self": "url_to_related_resource"
      }
    }
  },
  "includes": {
    "related_resource": {...}
  }
}
```

### Resource Classes
- **ProductsResource**: Product data transformation
- **CategoryResource**: Category data formatting
- **CustomerResource**: Customer data with user relationship
- **OrderResource**: Order data with items
- **OrderItemResource**: Order item data
- **UserResource**: User data for authentication

## Configuration

### Environment Variables
```env
APP_NAME="Pharmacy Manager API"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pharmacy_manager
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost:3000
```

### Swagger Configuration
- **Documentation**: Auto-generated at `/api/documentation`
- **OpenAPI 3.0**: Compliant API documentation
- **Authentication**: Bearer token security scheme
- **Tags**: Organized by resource type

## Development Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL/PostgreSQL/SQLite

### Installation
```bash
# Clone repository
git clone <repository-url>
cd pharmacy-manager-api

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Generate API documentation
php artisan l5-swagger:generate

# Start development server
php artisan serve
```

### Docker Setup (Laravel Sail)
```bash
# Start containers
./vendor/bin/sail up -d

# Run migrations
./vendor/bin/sail artisan migrate

# Generate documentation
./vendor/bin/sail artisan l5-swagger:generate
```

## Testing

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

### Test Structure
- **Feature Tests**: API endpoint testing
- **Unit Tests**: Individual component testing
- **Database Testing**: Model and relationship testing

## Deployment

### Production Considerations
1. **Environment**: Set `APP_ENV=production`
2. **Debug**: Disable `APP_DEBUG=false`
3. **Caching**: Enable route and config caching
4. **Database**: Use production database credentials
5. **Security**: Use HTTPS in production
6. **Monitoring**: Set up logging and monitoring

### Deployment Commands
```bash
# Production optimization
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Generate API documentation
php artisan l5-swagger:generate
```

## API Documentation

The complete API documentation is available at:
- **Swagger UI**: `http://localhost:8000/api/documentation`
- **JSON Schema**: `http://localhost:8000/api-docs.json`

## Postman Collection

A Postman collection is available at `Pharmacy_Manager_API.postman_collection.json` for easy API testing and development.

## Performance Features

### Caching
- **Product Caching**: Intelligent caching with invalidation
- **Category Caching**: Cache management for categories
- **Cache Keys**: Tracked for proper invalidation

### Database Optimization
- **Indexes**: Strategic indexing on frequently queried fields
- **Soft Deletes**: Safe deletion with recovery options
- **Foreign Keys**: Proper relationships with cascade deletes

### API Optimization
- **Pagination**: Built-in pagination for large datasets
- **Filtering**: Advanced filtering capabilities
- **Search**: Full-text search across relevant fields
- **Includes**: Eager loading for related data

## Security Features

- **Authentication**: Laravel Sanctum token-based auth
- **Validation**: Comprehensive request validation
- **Authorization**: Route protection middleware
- **Data Sanitization**: Input sanitization and validation
- **Soft Deletes**: Safe data deletion
- **UUID Support**: Non-sequential identifiers

## Error Handling

The API provides consistent error responses:

```json
{
  "message": "Error description",
  "status": 400
}
```

Common HTTP status codes:
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `404` - Not Found
- `409` - Conflict
- `422` - Validation Error
- `500` - Server Error

---

This documentation provides a comprehensive overview of the Pharmacy Manager API. For specific implementation details, refer to the source code and generated API documentation.


