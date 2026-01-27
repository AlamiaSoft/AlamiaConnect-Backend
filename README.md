# Alamia Connect - Backend API

This is the backend API service for Alamia Connect, built on the Laravel framework with a modular architecture.

## Tech Stack
-   **Framework**: Laravel 11.x
-   **Architecture**: Modular (Alamia packages under `packages/Alamia`)
-   **Standards**: JSON:API for RESTful services
-   **Authentication**: Laravel Sanctum
-   **API Documentation**: Swagger/OpenAPI 3.0

## Project Structure
-   `app/`: Core Laravel application
-   `packages/Alamia/`: Custom business logic and REST API implementations
    -   `Admin/`: Admin panel functionality
    -   `Installer/`: Installation and setup utilities
    -   `rest-api/`: RESTful API endpoints and documentation
-   `routes/`: API and Web routes
-   `config/`: Application configuration

## Getting Started

### Prerequisites
-   **PHP**: 8.1 or higher
-   **Database**: MySQL 5.7+, MariaDB 10.2+, PostgreSQL 10+, or SQLite 3.8+
-   **Composer**: 2.0 or higher
-   **Node.js**: 16+ (optional, for asset compilation)

### Installation

#### 1. Clone and Setup Environment
```bash
# Copy environment file
cp .env.example .env

# Edit .env and configure your database connection
# For MySQL/MariaDB:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=alamiaconnect
DB_USERNAME=root
DB_PASSWORD=

# For SQLite (recommended for testing):
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

#### 2. Install Dependencies
```bash
composer install
```

#### 3. Run the Alamia Installer
The installer handles:
- Application key generation
- Database migrations (Webkul core + Alamia custom tables)
- Database seeding with default data
- Admin user creation

```bash
php artisan alamia:install
```

**Interactive Installation:**
The installer will prompt you for:
- Application name (default: AlamiaConnect)
- Application URL
- Default locale (ar, en, tr, es, fa, pt_BR)
- Default currency
- Database credentials
- Admin user details

**Non-Interactive Installation:**
```bash
# Skip environment setup (use existing .env)
php artisan alamia:install --skip-env-check

# Skip admin creation (for automated deployments)
php artisan alamia:install --skip-admin-creation

# Both options
php artisan alamia:install --skip-env-check --skip-admin-creation
```

#### 4. Install REST API
To enable and configure the REST API with Swagger documentation:

```bash
php artisan alamia-rest-api:install
```

This command will:
- Publish L5-Swagger configuration
- Generate API documentation
- Set up Swagger UI

#### 5. Start the Development Server
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## API Documentation

### REST API Endpoints
The API follows JSON:API standards. All endpoints are managed within the `Alamia\RestApi` package.

**Base URL**: `http://localhost:8000/api`

### Swagger UI Documentation
Interactive API documentation is available via Swagger UI:

**URL**: `http://localhost:8000/api/documentation`

The Swagger UI provides:
- Complete API endpoint reference
- Request/response schemas
- Interactive API testing
- Authentication examples using Laravel Sanctum

### Regenerate API Documentation
```bash
php artisan l5-swagger:generate
```

## Authentication

The API uses **Laravel Sanctum** for token-based authentication.

### Obtaining an Access Token
```bash
POST /api/v1/admin/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "admin123"
}
```

### Using the Token
Include the token in the Authorization header:
```
Authorization: Bearer {your-token-here}
```

## Database Support

### Supported Databases
- **MySQL** 5.7+ (recommended for production)
- **MariaDB** 10.2+
- **PostgreSQL** 10+
- **SQLite** 3.8+ (recommended for development/testing)

### SQLite Setup
```bash
# Create database directory and file
mkdir -p database
touch database/database.sqlite

# Update .env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Run installer
php artisan alamia:install
```

## Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
```

## Useful Commands

```bash
# Clear all caches
php artisan optimize:clear

# Clear specific caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check migration status
php artisan migrate:status

# Rollback migrations
php artisan migrate:rollback

# Fresh install (WARNING: destroys all data)
php artisan migrate:fresh --seed
```

## Configuration

### Admin Panel
Access the admin panel at: `http://localhost:8000/admin`

Default credentials (if created during installation):
- **Email**: admin@example.com
- **Password**: admin123

### Supported Locales
- Arabic (ar)
- English (en)
- Turkish (tr)
- Spanish (es)
- Persian (fa)
- Portuguese Brazil (pt_BR)
- Vietnamese (vi)

## Contributing

Please ensure all code follows PSR-12 coding standards and includes appropriate tests.

## License

Copyright 2026 Alamia Connect. All rights reserved.

---

Made with care by the Alamia Team
