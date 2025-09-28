# Pharmacy Manager API

A comprehensive RESTful API backend for the Pharmacy Manager system built with Laravel 12. This API provides complete inventory management, order processing, customer management, and user authentication services for pharmacy operations.

## Project Presentation Details

This project was presented as a **Soutenance de projet de fin d'étude** (Final Year Project Defense) at:
- **University:** Université Sidi Mohamed Ben Abdellah
- **School:** École Nationale des Sciences Appliquées

### Project Title
**Creation de Gestion de Pharmacy Laravel API + Java Android App**

### Presented by
- Yassine Faidi : [https://github.com/faididev]
- Hamza Mekouar : [https://github.com/MekouarTech]

### Supervised by
- Mr.S Jamal RIFFI
- Mr.s LAKHRISSI YOUNES

## Repository

- **Backend API**: [pharmacy-manager-api](https://github.com/faididev/pharmacy-manager-api)
- **Frontend App**: [PharmacyManager](https://github.com/faididev/PharmacyManager)

## Features

### Authentication & Authorization
- **Laravel Sanctum** for API token authentication
- **User Registration** with email validation
- **Secure Login/Logout** with token management
- **Password Hashing** using bcrypt
- **Session Management** with automatic token refresh

### Product Management
- **CRUD Operations** for products
- **SKU Generation** with automatic unique identifier creation
- **Category Association** for product organization
- **Inventory Tracking** with quantity management
- **Expiry Date Monitoring** for pharmaceutical products
- **Price Management** with decimal precision
- **Search & Filtering** by name, description, SKU, and category
- **Soft Deletes** for data integrity

### Order Management
- **Order Creation** with multiple items
- **Order Status Tracking** (pending, completed, cancelled)
- **Customer Association** for order history
- **Real-time Total Calculation** based on current prices
- **Order Item Management** with quantity and pricing
- **Date-based Filtering** for order queries

### Customer Management
- **Customer Profiles** linked to user accounts
- **Loyalty Points System** for customer retention
- **Order History Tracking** per customer
- **User Association** for multi-user pharmacy management

### Category Management
- **Category CRUD** operations
- **Product Association** for better organization
- **Search Functionality** for category management

### API Features
- **RESTful Design** following REST principles
- **JSON API Responses** with consistent structure
- **Pagination Support** for large datasets
- **Search & Filtering** across all resources
- **Relationship Loading** with include parameters
- **Error Handling** with detailed error messages

## Technology Stack

- **Framework**: Laravel 12
- **PHP Version**: 8.2+
- **Database**: MySQL (with support for SQLite, PostgreSQL, MariaDB)
- **Authentication**: Laravel Sanctum
- **API Documentation**: Swagger/OpenAPI with L5-Swagger
- **Testing**: PHPUnit with Pest
- **Code Quality**: Laravel Pint, PHP CS Fixer
- **Frontend Assets**: Vite with Tailwind CSS

## Prerequisites

- **PHP 8.2** or higher
- **Composer** (PHP dependency manager)
- **MySQL 8.0** or higher
- **Node.js 18+** and **npm** (for frontend assets)
- **Git** for version control

## Installation

### 1. Clone the Repository
```bash
git clone https://github.com/faididev/pharmacy-manager-api.git
cd pharmacy-manager-api
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE pharmacy_manager;

# Update .env file with database credentials
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pharmacy_manager
DB_USERNAME=root
DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed

# Or reset database with fresh data
php artisan migrate:fresh --seed
```

### 5. Start the Application
```bash
# Start Laravel development server
php artisan serve

# In another terminal, start Vite for frontend assets
npm run dev

# Or run both with the dev script
composer run dev
```

The API will be available at `http://localhost:8000`


### Categories (10 categories)
- Antibiotics
- Pain Relief
- Vitamins & Supplements
- Cold & Flu
- Digestive Health
- Heart & Blood Pressure
- Diabetes Care
- Skin Care
- Eye Care
- First Aid

### Products (50 sample products)
- **Realistic pharmaceutical names** (Paracetamol, Ibuprofen, etc.)
- **Auto-generated SKUs** (SKU-ABC12345 format)
- **Realistic prices** (0.50 to 150.00 range)
- **Random quantities** (0-500 units)
- **Manufacture dates** (last 2 years)
- **Expiry dates** (1-5 years from manufacture)
- **Proper category associations**

### Customers (20 sample customers)
- **Linked to user accounts**
- **Loyalty points** (0-1000 range)
- **Realistic names and contact info**

### Orders (30 sample orders)
- **Various statuses** (pending, completed, cancelled)
- **Multiple order items** per order
- **Realistic total amounts**
- **Date range** (last 6 months)
- **Customer associations**

### Order Items
- **Product associations** with current prices
- **Realistic quantities** (1-10 per item)
- **Proper total calculations**

### Running Seeders
```bash
# Seed all data
php artisan db:seed

# Seed specific seeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=ProductSeeder
php artisan db:seed --class=CustomerSeeder
php artisan db:seed --class=OrderSeeder

# Reset and seed fresh data
php artisan migrate:fresh --seed
```

## API Documentation

### Swagger Documentation
Interactive API documentation is available at:
- **Development**: `http://localhost:8000/api/documentation`
- **Production**: `https://yourdomain.com/api/documentation`

### Postman Collection
A complete Postman collection is included in the repository:
- **File**: `Pharmacy_Manager_API.postman_collection.json`
- **Environment**: Configure base URL and authentication tokens

## API Endpoints

### Authentication
```
POST   /api/auth/login          # User login
POST   /api/auth/register       # User registration
GET    /api/auth/user           # Get current user
POST   /api/auth/logout         # User logout
```

### Products
```
GET    /api/v1/products         # List products
POST   /api/v1/products         # Create product
GET    /api/v1/products/{id}    # Get product details
PUT    /api/v1/products/{id}    # Update product
DELETE /api/v1/products/{id}    # Delete product
```

### Categories
```
GET    /api/v1/categories       # List categories
POST   /api/v1/categories       # Create category
GET    /api/v1/categories/{id}  # Get category details
PUT    /api/v1/categories/{id}  # Update category
DELETE /api/v1/categories/{id}  # Delete category
```

### Orders
```
GET    /api/v1/orders           # List orders
POST   /api/v1/orders           # Create order
GET    /api/v1/orders/{id}      # Get order details
PUT    /api/v1/orders/{id}      # Update order
DELETE /api/v1/orders/{id}      # Delete order
```

### Customers
```
GET    /api/v1/customers                    # List customers
POST   /api/v1/customers                    # Create customer
GET    /api/v1/customers/{id}               # Get customer details
PUT    /api/v1/customers/{id}               # Update customer
DELETE /api/v1/customers/{id}               # Delete customer
GET    /api/v1/customers/user/{userId}      # Get customers by user
```


### Database Management
```bash

# Create seeders
php artisan make:seeder UserSeeder
php artisan make:seeder CategorySeeder
php artisan make:seeder ProductSeeder
php artisan make:seeder CustomerSeeder
php artisan make:seeder OrderSeeder

# Rollback migrations
php artisan migrate:rollback

# Reset database
php artisan migrate:fresh --seed
```

### Creating Sample Data Seeders
The project includes comprehensive seeders for realistic test data:

#### UserSeeder
- Creates 5 different user accounts with various roles
- Includes admin, manager, staff, pharmacist, and test users
- All users have the password: `password123`

#### CategorySeeder
- Creates 10 pharmaceutical categories
- Includes realistic category names and descriptions
- Covers major medicine types (Antibiotics, Pain Relief, etc.)

#### ProductSeeder
- Creates 50 sample products with realistic data
- Auto-generates unique SKUs
- Sets realistic prices, quantities, and expiry dates
- Associates products with appropriate categories

#### CustomerSeeder
- Creates 20 sample customers
- Links customers to user accounts
- Assigns random loyalty points (0-1000)

#### OrderSeeder
- Creates 30 sample orders with various statuses
- Generates realistic order items with quantities
- Calculates proper total amounts
- Distributes orders across different customers and dates

## Security Features

- **Laravel Sanctum** for API authentication
- **Input Validation** with Form Requests
- **SQL Injection Protection** via Eloquent ORM
- **Secure Headers** configuration
- **Password Hashing** with bcrypt

## Deployment

### Production Environment
1. Set `APP_ENV=production` in `.env`
2. Configure production database
3. Set up web server (Apache/Nginx)
4. Configure SSL certificates
5. Set up process manager (Supervisor/PM2)
6. Configure queue workers for background jobs


## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support and questions:
- Create an issue in the repository
- Check the API documentation at `/api/documentation`
- Review the Laravel documentation

---

**Note**: This API is designed for educational and small business use. For production deployment in large-scale environments, additional security measures, performance optimizations, and scalability considerations should be implemented.
