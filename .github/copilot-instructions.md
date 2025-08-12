# Bagisto E-commerce Platform
Bagisto is a Laravel-based open-source e-commerce platform built with PHP 8.3+, Vue.js, MySQL, and modern web technologies. It provides both shop frontend and admin panel interfaces with comprehensive e-commerce functionality.

Always reference these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.

## Working Effectively

### Bootstrap, Build, and Test the Repository
- **NEVER CANCEL builds or long-running commands** - Set timeouts of 60+ minutes for builds and 30+ minutes for tests
- Start MySQL service: `sudo service mysql start`
- Setup database:
  ```bash
  mysql -u debian-sys-maint -p0hXmNy8KA8mCtCWZ -e "CREATE DATABASE bagisto; CREATE USER 'bagisto'@'localhost' IDENTIFIED BY 'bagisto123'; GRANT ALL PRIVILEGES ON bagisto.* TO 'bagisto'@'localhost'; FLUSH PRIVILEGES;"
  ```
- Install PHP dependencies: `composer install --no-interaction` -- takes 60 seconds. NEVER CANCEL. Set timeout to 90+ minutes.
- Setup environment: `cp .env.example .env`
- Configure database in .env:
  ```
  DB_DATABASE=bagisto
  DB_USERNAME=bagisto
  DB_PASSWORD=bagisto123
  ```
- Generate app key: `php artisan key:generate`
- Install Bagisto: `php artisan bagisto:install --skip-env-check --skip-admin-creation --skip-github-star` -- takes 10 seconds
- Install root npm dependencies: `npm install` -- takes 22 seconds
- Install Admin package dependencies: `cd packages/Webkul/Admin && npm install` -- takes 55 seconds
- Install Shop package dependencies: `cd packages/Webkul/Shop && npm install` -- takes 4 seconds
- Build Admin assets: `cd packages/Webkul/Admin && npm run build` -- takes 5 seconds
- Build Shop assets: `cd packages/Webkul/Shop && npm run build` -- takes 3 seconds

### Running the Application
- ALWAYS run the bootstrapping steps first
- Start development server: `php artisan serve --host=0.0.0.0 --port=8000`
- Shop frontend: http://localhost:8000
- Admin panel: http://localhost:8000/admin (redirects to /admin/login)

### Testing
- Install dev dependencies first: `composer install --no-interaction` (includes Pest/PHPUnit)
- Run unit tests: `vendor/bin/pest --testdox` -- takes 6 minutes. NEVER CANCEL. Set timeout to 15+ minutes.
- Run specific test suite: `vendor/bin/pest packages/Webkul/Admin/tests`
- 846 total tests across Admin, Core, DataGrid, and Shop packages

### Code Quality
- Run code formatter: `vendor/bin/pint` (Laravel Pint for PSR-12 compliance)
- Check code style: `vendor/bin/pint --test`
- CI pipeline runs: Pest tests, Admin Playwright tests (6 shards), Shop Playwright tests

## Validation

### Manual Testing Scenarios
After making changes, ALWAYS test these complete user workflows:

#### Shop Frontend Validation
1. **Homepage Load**: Access http://localhost:8000 and verify page loads with proper styling
2. **Product Browsing**: Navigate categories and product detail pages
3. **Search Functionality**: Test product search and filtering
4. **Cart Operations**: Add products to cart, view cart, modify quantities
5. **Customer Registration**: Register new account, verify email workflows
6. **Checkout Process**: Complete guest and registered user checkout flows

#### Admin Panel Validation  
1. **Admin Login**: Access http://localhost:8000/admin/login with admin credentials
2. **Dashboard Access**: Verify admin dashboard loads with widgets and data
3. **Product Management**: Create, edit, and manage products
4. **Category Management**: Manage product categories and attributes
5. **Order Management**: View and process orders
6. **Configuration**: Test system settings and configurations

### CI/CD Validation
- **Always run** `vendor/bin/pint` before committing (CI will fail otherwise)
- Playwright tests run in GitHub Actions for both Admin (6 shards) and Shop interfaces
- Database seeding available: `php artisan db:seed --class="Webkul\\Installer\\Database\\Seeders\\ProductTableSeeder"`

## Key Architecture Components

### Package Structure
```
packages/Webkul/
├── Admin/          # Admin panel (Vue.js + Laravel)
├── Shop/           # Shop frontend (Vue.js + Laravel)  
├── Core/           # Core functionality
├── Attribute/      # Product attributes
├── Category/       # Category management
├── Customer/       # Customer management
├── Product/        # Product management
├── Sales/          # Order and sales
├── Payment/        # Payment methods
├── Shipping/       # Shipping methods
└── [20+ other packages]
```

### Technology Stack
- **Backend**: Laravel 11.x, PHP 8.3+
- **Frontend**: Vue.js 3.x, Vite, Tailwind CSS
- **Database**: MySQL 8.0
- **Testing**: Pest (PHPUnit), Playwright for E2E
- **Asset Building**: Vite with Laravel Vite plugin
- **Code Quality**: Laravel Pint (PSR-12)

### Configuration Files
- `composer.json`: PHP dependencies and autoloading
- `package.json`: Root npm dependencies (minimal Vite setup)
- `packages/Webkul/Admin/package.json`: Admin package dependencies (Vue, Playwright, etc.)
- `packages/Webkul/Shop/package.json`: Shop package dependencies
- `phpunit.xml`: Test configuration for all packages
- `pint.json`: Code style configuration
- `.env.example`: Environment template with all required variables

### Common Commands Reference
```bash
# Database operations
php artisan migrate
php artisan db:seed
php artisan bagisto:install

# Development
php artisan serve
php artisan queue:work
php artisan cache:clear

# Asset building
npm run build          # Root (currently has issues, use package-specific)
npm run dev            # Development mode

# Testing
vendor/bin/pest        # All tests
vendor/bin/pest --parallel  # Parallel execution
phpunit                # Alternative test runner

# Code quality
vendor/bin/pint        # Format code
vendor/bin/pint --test # Check formatting
```

### Troubleshooting Common Issues
- **Composer GitHub rate limits**: Use `COMPOSER_AUTH='{}' composer install --no-interaction`
- **Vite build errors in root**: Build individual packages instead (`packages/Webkul/Admin` and `packages/Webkul/Shop`)
- **MySQL authentication**: Use debian-sys-maint user or configure root password
- **Asset loading issues**: Ensure both Admin and Shop packages are built with `npm run build`
- **Test failures**: Run `composer install` to include dev dependencies before testing

### Expected Timing
- **Composer install (production)**: ~60 seconds
- **Composer install (with dev)**: ~120 seconds  
- **npm install (all packages)**: ~80 seconds total
- **Asset builds**: ~8 seconds total
- **Bagisto installation**: ~10 seconds
- **Test suite**: ~6 minutes (846 tests)
- **NEVER CANCEL** any operation that takes less than these expected times

This platform supports multi-channel, multi-currency, multi-locale e-commerce with extensive customization capabilities through its modular package architecture.