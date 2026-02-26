<bagisto-guidelines>
=== foundation rules ===

# Bagisto Guidelines

Bagisto is a Laravel-based e-commerce platform. These guidelines are specifically curated for developing with Bagisto and its package-based architecture.

## Foundational Context

This application is a **Bagisto** e-commerce platform built on Laravel 11. You must be familiar with both Laravel and Bagisto's modular package architecture.

### Technology Stack

- **PHP**: 8.3.28
- **Laravel**: v11
- **Vue.js**: For admin panel interactivity
- **Tailwind CSS**: For styling
- **Laravel Octane**: v2
- **Laravel Sanctum**: v4
- **Laravel Socialite**: v5
- **Laravel Boost**: v2
- **Laravel MCP**: v0
- **Laravel Pint**: v1
- **Pest**: v3
- **PHPUnit**: v11

### Bagisto Core Packages

Bagisto uses a modular package structure in `packages/Webkul/`:

| Package | Purpose |
|---------|---------|
| **Admin** | Admin panel functionality |
| **Shop** | Customer storefront |
| **Core** | Common utilities and helpers |
| **Product** | Product management |
| **Category** | Category management |
| **Checkout** | Cart and checkout process |
| **Payment** | Payment methods (CashOnDelivery, MoneyTransfer) |
| **Paypal** | PayPal integration |
| **Shipping** | Shipping methods |
| **Sales** | Order management |
| **Customer** | Customer management |
| **Attribute** | Product attributes |
| **Inventory** | Stock management |
| **CartRule** | Cart promotions |
| **CatalogRule** | Catalog promotions |
| **DataGrid** | Admin data tables |
| **Tax** | Tax calculation |
| **CMS** | Content management |
| **Theme** | Theme management |

## Skills Activation

This project has domain-specific skills available. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

- `pest-testing` — Tests applications using the Pest 3 PHP framework. Activates when writing tests, creating unit or feature tests, adding assertions, testing Livewire components, architecture testing, debugging test failures, working with datasets or mocking; or when the user mentions test, spec, TDD, expects, assertion, coverage, or needs to verify functionality works.

- `payment-method-development` — Payment gateway development in Bagisto. Activates when creating payment methods, integrating payment gateways like Stripe, PayPal, or any third-party payment processor; or when the user mentions payment, payment gateway, payment method, Stripe, PayPal, or needs to add a new payment option to the checkout.

## Bagisto Architecture

### Package Structure

Every Bagisto package follows a standardized structure:

```
packages/Webkul/{PackageName}/
├── src/
│   ├── Config/
│   │   ├── admin-menu.php
│   │   └── system.php
│   ├── Database/
│   │   ├── Migrations/
│   │   ├── Seeders/
│   │   └── Factories/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   └── Shop/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   │   └── {Package}Proxy.php
│   ├── Repositories/
│   │   └── {Package}Repository.php
│   ├── Resources/
│   │   ├── views/
│   │   └── lang/
│   ├── Providers/
│   │   └── {Package}ServiceProvider.php
│   └── manifest.php
└── composer.json
```

### Repository Pattern

Bagisto uses the Prettus L5 Repository pattern. Always use repositories for data access:

```php
// Correct way - use = app(ProductRepositoryInterface::class);
$products = $repository repository
$repository->all();

# Avoid raw queries
$products = Product::all(); # Less preferred
```

### Service Providers

Service providers must:
- Load routes from `Routes/admin-routes.php` and `Routes/shop-routes.php`
- Load migrations automatically
- Load translations from `Resources/lang`
- Load views from `Resources/views`
- Merge package configuration using `$this->mergeConfigFrom()`

## Conventions

- Always follow existing code conventions used in this application.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing new one.
- Use PHPDoc blocks with proper punctuation for all classes and methods.
- Follow the package structure when creating new packages.
- Use repositories for database operations.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work.
- Unit and feature tests are more important than manual verification.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.
- Custom packages should be placed in `packages/Webkul/`.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan

- Use the `list-artisan-commands` tool when you need to call an Artisan command to double-check the available parameters.

## URLs

- Whenever you share a project URL with the user, you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain/IP, and port.

## Tinker / Debugging

- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.
- Use the `database-schema` tool to inspect table structure before writing migrations or models.

## Reading Browser Logs With the `browser-logs` Tool

- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)

- Boost comes with a powerful `search-docs` tool you should use before trying other approaches when working with Laravel or Laravel ecosystem packages.
- This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation for your circumstance.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic-based queries at once. For example: `['rate limiting', 'routing rate limiting', 'routing']`.
- Do not add package names to queries; package information is already shared.

### Available Search Syntax

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'.
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit".
3. Quoted Phrases (Exact Position) - query="infinite scroll" - words must be adjacent and in that order.
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit".
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms.

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.

## Constructors

- Use PHP 8 constructor property promotion in `__construct()`.
    - `public function __construct(public GitHub $github) { }`
- Do not allow empty `__construct()` methods with zero parameters unless the constructor is private.

## Type Declarations

- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

```php
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
```

## Enums

- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.

## Comments

- Prefer PHPDoc blocks over inline comments. Never use comments within the code itself unless the logic is exceptionally complex.

## PHPDoc Blocks

- Add useful array shape type definitions when appropriate.
- Always use proper punctuation at the end of descriptions.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input.

## Database

- Always use proper Eloquent relationship methods with return type hints.
- Use Eloquent models and relationships before suggesting raw database queries.
- Use Repository pattern for Bagisto packages.
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## Controllers & Validation

- Always create Form Request classes for validation rather than inline validation in controllers.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

## Authentication & Authorization

- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Queues

- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

## Configuration

- Use environment variables only in configuration files - never use the `env()` function directly outside of config files.
- Always use `config('app.name')`, not `env('APP_NAME')`.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== laravel/v11 rules ===

# Laravel 11

- CRITICAL: ALWAYS use `search-docs` tool for version-specific Laravel documentation and updated code examples.
- Laravel 11 brought a new streamlined file structure which this project now uses.

## Laravel 11 Structure

- In Laravel 11, middleware are no longer registered in `app/Http/Kernel.php`.
- Middleware are configured declaratively in `bootstrap/app.php` using `Application::configure()->withMiddleware()`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- No app\Console\Kernel.php - use `bootstrap/app.php` or `routes/console.php` for console configuration.
- Commands auto-register - files in `app/Console/Commands/` are automatically available and do not require manual registration.

## Database

- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 11 allows limiting eagerly loaded records natively: `$query->latest()->limit(10);`.

### Models

- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property.

## New Artisan Commands

- List Artisan commands using Boost's MCP tool, if available:
    - `php artisan make:enum`
    - `php artisan make:class`
    - `php artisan make:interface`

=== boost/core rules ===

# Laravel Boost

- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan

- Use the `list-artisan-commands` tool when you need to call an Artisan command to double-check the available parameters.

## URLs

- Whenever you share a project URL with the user, you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain/IP, and port.

## Tinker / Debugging

- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.
- Use the `database-schema` tool to inspect table structure before writing migrations or models.

## Reading Browser Logs

- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation

- Use `search-docs` tool before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic-based queries at once.

=== pint/core rules ===

# Laravel Pint Code Formatter

- You must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.
- CRITICAL: ALWAYS use `search-docs` tool for version-specific Pest documentation and updated code examples.
- IMPORTANT: Activate `pest-testing` every time you're working with a Pest or testing-related task.

=== payment-method-development rules ===

# Payment Gateway Development

- CRITICAL: ALWAYS use the payment-method-development skill when working with payment methods in Bagisto.
- Payment methods in Bagisto are located in `packages/Webkul/Payment/src/Payment/` and `packages/Webkul/Paypal/src/Payment/`.
- All payment methods extend `Webkul\Payment\Payment\Payment` abstract class.
- Payment configuration is defined in `Config/payment-methods.php` files.
- System configuration for admin panel is defined in `Config/system.php` files.
- Service providers must merge payment method configuration using `$this->mergeConfigFrom()`.
- Always follow the existing code patterns and PHPDoc conventions when creating payment methods.
- For testing payment methods, refer to `packages/Webkul/Shop/tests/Feature/Checkout/CheckoutTest.php`.

</bagisto-guidelines>
