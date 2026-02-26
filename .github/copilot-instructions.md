# Bagisto Development Guide

## Project Overview

This is a **Bagisto** e-commerce platform - an open-source Laravel-based e-commerce framework. Bagisto is built with:
- **PHP** (Server-side)
- **Laravel** (PHP Framework)
- **Vue.js** (Frontend components)
- **Tailwind CSS** (Styling)

## Architecture

### Modular Package Structure

Bagisto follows a modular, package-based architecture. All core features are organized into Laravel packages located in `packages/Webkul/`.

### Available Packages

- `Admin` - Administrative interface and management
- `Attribute` - Product attributes and attribute sets
- `BookingProduct` - Booking/rental functionality
- `CartRule` - Cart-based promotions
- `CatalogRule` - Catalog-based promotions
- `Category` - Category management
- `Checkout` - Cart and checkout process
- `Core` - Core utilities and helpers
- `Customer` - Customer management
- `DataGrid` - Tabular data display
- `DataTransfer` - Import/export data
- `FPC` - Full page caching
- `Inventory` - Stock management
- `Marketing` - Marketing tools
- `Payment` - Payment integrations
- `Product` - Product management
- `Sales` - Order management
- `Shipping` - Shipping methods
- `Shop` - Customer storefront
- `Tax` - Tax calculations
- `Theme` - Theme management
- `User` - User management

### Standard Package Structure

Each package follows this structure:

```
Package/src/
├── Config/              # Configuration files (admin-menu.php, system.php)
├── Database/
│   ├── Migrations/     # Database migrations
│   ├── Seeders/        # Database seeders
│   └── Factories/      # Model factories
├── Http/
│   ├── Controllers/    # Admin and Shop controllers
│   ├── Middleware/     # Route middleware
│   └── Requests/       # Form requests/validation
├── Models/             # Eloquent models with Proxy pattern
├── Repositories/       # Repository pattern (Prettus L5 Repository)
├── Resources/
│   ├── views/          # Blade views (admin/, shop/)
│   ├── lang/           # Localization files
│   └── assets/         # CSS, JS assets
├── Routes/             # admin-routes.php, shop-routes.php
├── Providers/          # Service providers
└── Contracts/         # Interface definitions
```

## Development Patterns

### Repository Pattern

Bagisto uses **Prettus L5 Repository** for data access abstraction:
- Repository Contracts define interfaces
- Repository Implementations contain data access logic
- Works with Eloquent models

### Event-Driven Architecture

The framework triggers events throughout the application lifecycle for extensibility.

### Proxy Pattern

Models use proxy classes (e.g., `ProductProxy`) for extensibility.

## Key Conventions

### Naming Conventions

- **Namespace**: `Webkul\<PackageName>`
- **Routes**: Separate `admin-routes.php` and `shop-routes.php`
- **Views**: Organized in `admin/` and `shop/` folders
- **Models**: Singular name (e.g., `Product`, `Category`)
- **Repositories**: `<ModelName>Repository` pattern
- **Controllers**: `<ModelName>Controller` in Admin/Shop folders

### Package Registration

1. Add namespace to `composer.json` psr-4 autoload
2. Run `composer dump-autoload`
3. Register service provider in `bootstrap/providers.php`
4. Run `php artisan optimize:clear`

### Creating New Packages

Use Bagisto Package Generator:
```bash
composer require bagisto/bagisto-package-generator
php artisan package:make Webkul/<PackageName>
```

Or manually create:
1. Create `packages/Webkul/<PackageName>/src/`
2. Create Service Provider in `src/Providers/`
3. Update composer.json and register provider

## Working with Features

### Shipping Methods
- Extend `Webkul\Shipping\Carriers\AbstractCarrier`
- Configure in `Config/system.php`
- Register in service provider

### Payment Methods
- Extend `Webkul\Payment\Payment\AbstractPayment`
- Configure in `Config/system.php`

### Product Types
- Extend appropriate type class in `Product\Type/`
- Configure in `Config/product_types.php`

### Themes
- Create in `packages/Webkul/<Theme>/`
- Use Vite for asset bundling
- Follow Blade templating conventions

## Code Style

- Use **Pint** for PHP code style (`./vendor/bin/pint`)
- Follow Laravel conventions
- Use type hints where possible
- Write meaningful variable/method names

## Testing

- Use PHPUnit for testing
- Tests located in `tests/` directory
- Follow existing test patterns

## Documentation References

- [Architecture Overview](https://devdocs.bagisto.com/architecture/overview.html)
- [Backend Architecture](https://devdocs.bagisto.com/architecture/backend.html)
- [Frontend Architecture](https://devdocs.bagisto.com/architecture/frontend.html)
- [Package Development](https://devdocs.bagisto.com/package-development/getting-started.html)
- [Shipping Method Development](https://devdocs.bagisto.com/shipping-method-development/getting-started.html)
- [Payment Method Development](https://devdocs.bagisto.com/payment-method-development/getting-started.html)
- [Product Type Development](https://devdocs.bagisto.com/product-type-development/getting-started.html)
- [Theme Development](https://devdocs.bagisto.com/theme-development/getting-started.html)

## Important Notes

- Never modify core packages directly - use events/listeners or create custom packages
- Clear caches after making changes: `php artisan optimize:clear`
- Use repository pattern for all database operations
- Follow the modular structure when adding new features
