---
name: pest-testing
description: "Tests applications using the Pest 3 PHP framework in Bagisto. Activates when writing tests, creating unit or feature tests, adding assertions, testing Livewire components, architecture testing, debugging test failures, working with datasets or mocking; or when the user mentions test, spec, TDD, expects, assertion, coverage, or needs to verify functionality works."
license: MIT
metadata:
  author: bagisto
---

# Pest Testing in Bagisto

## When to Apply

Activate this skill when:
- Creating new tests (unit or feature)
- Modifying existing tests
- Debugging test failures
- Working with datasets, mocking, or test organization
- Writing architecture tests
- Testing Bagisto packages (Admin, Shop, Core, etc.)

## Bagisto Testing Structure

### Test Locations

Bagisto tests are organized within packages in `packages/Webkul/{Package}/tests/`:

```
packages/Webkul/
├── Admin/
│   └── tests/
│       ├── AdminTestCase.php          # Base test case
│       ├── Concerns/
│       │   └── AdminTestBench.php     # Test helpers
│       └── Feature/
│           ├── ExampleTest.php
│           └── ...
├── Shop/
│   └── tests/
│       ├── ShopTestCase.php
│       ├── Concerns/
│       │   └── ShopTestBench.php
│       └── Feature/
│           ├── Checkout/
│           │   └── CheckoutTest.php
│           └── ...
├── Core/
│   └── tests/
│       ├── CoreTestCase.php
│       ├── Concerns/
│       │   └── CoreAssertions.php
│       ├── Unit/
│       └── Feature/
├── DataGrid/
│   └── tests/
│       ├── DataGridTestCase.php
│       └── Unit/
└── Installer/
    └── tests/
        ├── InstallerTestCase.php
        └── Feature/
```

### Available Test Suites

Bagisto has the following test suites configured in `phpunit.xml`:

| Test Suite | Location | Command |
|------------|----------|---------|
| Admin Feature Test | `packages/Webkul/Admin/tests/Feature` | `php artisan test --testsuite="Admin Feature Test"` |
| Core Unit Test | `packages/Webkul/Core/tests/Unit` | `php artisan test --testsuite="Core Unit Test"` |
| DataGrid Unit Test | `packages/Webkul/DataGrid/tests/Unit` | `php artisan test --testsuite="DataGrid Unit Test"` |
| Installer Feature Test | `packages/Webkul/Installer/tests/Feature` | `php artisan test --testsuite="Installer Feature Test"` |
| Shop Feature Test | `packages/Webkul/Shop/tests/Feature` | `php artisan test --testsuite="Shop Feature Test"` |

## Pest.php Configuration

Bagisto uses `tests/Pest.php` to register test cases for each package:

```php
<?php

uses(Webkul\Admin\Tests\AdminTestCase::class)->in('../packages/Webkul/Admin/tests');
uses(Webkul\Core\Tests\CoreTestCase::class)->in('../packages/Webkul/Core/tests');
uses(Webkul\DataGrid\Tests\DataGridTestCase::class)->in('../packages/Webkul/DataGrid/tests');
uses(Webkul\Installer\Tests\InstallerTestCase::class)->in('../packages/Webkul/Installer/tests');
uses(Webkul\Shop\Tests\ShopTestCase::class)->in('../packages/Webkul/Shop/tests');
```

### Test Case Structure

Each package has its own test case that extends `Tests\TestCase`:

```php
// packages/Webkul/Shop/tests/ShopTestCase.php
<?php

namespace Webkul\Shop\Tests;

use Tests\TestCase;
use Webkul\Core\Tests\Concerns\CoreAssertions;
use Webkul\Shop\Tests\Concerns\ShopTestBench;

class ShopTestCase extends TestCase
{
    use CoreAssertions, ShopTestBench;
}
```

## Composer.json Autoload Configuration

### Production Autoload

Package namespaces are registered in root `composer.json`:

```json
"autoload": {
    "psr-4": {
        "Webkul\\Admin\\": "packages/Webkul/Admin/src",
        "Webkul\\Shop\\": "packages/Webkul/Shop/src",
        "Webkul\\Core\\": "packages/Webkul/Core/src",
        ...
    }
}
```

### Development Autoload

Test namespaces are registered in `autoload-dev`:

```json
"autoload-dev": {
    "psr-4": {
        "Tests\\": "tests/",
        "Webkul\\Admin\\Tests\\": "packages/Webkul/Admin/tests",
        "Webkul\\Core\\Tests\\": "packages/Webkul/Core/tests",
        "Webkul\\DataGrid\\Tests\\": "packages/Webkul/DataGrid/tests",
        "Webkul\\Installer\\Tests\\": "packages/Webkul/Installer/tests",
        "Webkul\\Shop\\Tests\\": "packages/Webkul/Shop/tests"
    }
}
```

## Running Tests

### Run All Tests

```bash
php artisan test --compact
```

### Run Specific Test Suite

```bash
php artisan test --testsuite="Shop Feature Test"
php artisan test --testsuite="Admin Feature Test"
php artisan test --testsuite="Core Unit Test"
```

### Run Specific Test File

```bash
php artisan test --compact packages/Webkul/Shop/tests/Feature/Checkout/CheckoutTest.php
```

### Run Test with Filter

```bash
php artisan test --compact --filter=testName
```

### Run Tests for Specific Package

```bash
# Shop tests
php artisan test --compact packages/Webkul/Shop/tests/

# Admin tests
php artisan test --compact packages/Webkul/Admin/tests/

# Core tests
php artisan test --compact packages/Webkul/Core/tests/
```

## Creating New Tests

### Create Feature Test

```bash
php artisan make:test --pest packages/Webkul/Shop/tests/Feature/Checkout/MyNewTest
```

### Create Unit Test

```bash
php artisan make:test --pest --unit packages/Webkul/Core/tests/Unit/MyNewTest
```

## Basic Test Structure

```php
<?php

namespace Webkul\Shop\Tests\Feature\Checkout;

use Webkul\Shop\Tests\ShopTestCase;

it('should pass basic test', function () {
    expect(true)->toBeTrue();
});

it('should return successful response', function () {
    $response = $this->getJson('/api/categories');

    $response->assertStatus(200);
});
```

## Assertions

Use specific assertions (`assertSuccessful()`, `assertNotFound()`) instead of `assertStatus()`:

| Use | Instead of |
|-----|------------|
| `assertSuccessful()` | `assertStatus(200)` |
| `assertNotFound()` | `assertStatus(404)` |
| `assertForbidden()` | `assertStatus(403)` |

## Mocking

Import mock function before use:

```php
use function Pest\Laravel\mock;
```

## Datasets

Use datasets for repetitive tests:

```php
it('has valid emails', function (string $email) {
    expect($email)->not->toBeEmpty();
})->with([
    'james' => 'james@bagisto.com',
    'john'  => 'john@bagisto.com',
]);
```

## Architecture Testing

Pest 3 includes architecture testing to enforce code conventions:

```php
arch('controllers')
    ->expect('Webkul\Admin\Http\Controllers')
    ->toExtendNothing()
    ->toHaveSuffix('Controller');

arch('models')
    ->expect('Webkul\Core\Models')
    ->toExtend('Illuminate\Database\Eloquent\Model');

arch('no debugging')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed();
```

## Adding Tests to a New Package

If you add tests to a new package, you need to:

1. **Register in Pest.php:** Add the test case binding:

```php
uses(Webkul\NewPackage\Tests\NewPackageTestCase::class)->in('../packages/Webkul/NewPackage/tests');
```

2. **Register in composer.json (autoload-dev):**

```json
"autoload-dev": {
    "psr-4": {
        "Webkul\\NewPackage\\Tests\\": "packages/Webkul/NewPackage/tests"
    }
}
```

3. **Register in phpunit.xml:** Add a new testsuite:

```xml
<testsuite name="New Package Test">
    <directory suffix="Test.php">packages/Webkul/NewPackage/tests</directory>
</testsuite>
```

4. **Run composer dump-autoload:**

```bash
composer dump-autoload
```

## Common Pitfalls

- Not importing `use function Pest\Laravel\mock;` before using mock
- Using `assertStatus(200)` instead of `assertSuccessful()`
- Forgetting to run `composer dump-autoload` after adding test namespace
- Not registering test case in `tests/Pest.php`
- Not adding testsuite to `phpunit.xml` for package-specific testing
- Deleting tests without approval
- Forgetting to register test namespace in composer.json autoload-dev

## Testing Best Practices

- Test happy paths, failure paths, and edge cases.
- Use factories for model creation in tests.
- Follow existing test patterns in the package.
- Use `$this->faker` or `fake()` for generating test data.
- Keep tests focused and independent.
