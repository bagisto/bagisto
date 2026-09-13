<?php

use Pest\Repositories\DatasetsRepository;
use Webkul\Admin\Tests\AdminTestCase;
use Webkul\Category\Tests\CategoryTestCase;
use Webkul\Core\Tests\CoreTestCase;
use Webkul\Customer\Tests\CustomerTestCase;
use Webkul\DataGrid\Tests\DataGridTestCase;
use Webkul\EUWithdrawal\Tests\EUWithdrawalTestCase;
use Webkul\FPC\Tests\FPCTestCase;
use Webkul\Installer\Tests\InstallerTestCase;
use Webkul\Omnibus\Tests\OmnibusTestCase;
use Webkul\PayGlocal\Tests\PayGlocalTestCase;
use Webkul\Payment\Tests\PaymentTestCase;
use Webkul\PayU\Tests\PayUTestCase;
use Webkul\Product\Tests\ProductTestCase;
use Webkul\Razorpay\Tests\RazorpayTestCase;
use Webkul\Rule\Tests\RuleTestCase;
use Webkul\Sales\Tests\SalesTestCase;
use Webkul\Shipping\Tests\ShippingTestCase;
use Webkul\Shop\Tests\ShopTestCase;
use Webkul\Stripe\Tests\StripeTestCase;
use Webkul\Tax\Tests\TaxTestCase;

ini_set('memory_limit', '1024M');

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(AdminTestCase::class)->in('../packages/Webkul/Admin/tests');
uses(CategoryTestCase::class)->in('../packages/Webkul/Category/tests');
uses(CoreTestCase::class)->in('../packages/Webkul/Core/tests');
uses(CustomerTestCase::class)->in('../packages/Webkul/Customer/tests');
uses(DataGridTestCase::class)->in('../packages/Webkul/DataGrid/tests');
uses(EUWithdrawalTestCase::class)->in('../packages/Webkul/EUWithdrawal/tests');
uses(FPCTestCase::class)->in('../packages/Webkul/FPC/tests');
uses(InstallerTestCase::class)->in('../packages/Webkul/Installer/tests');
uses(OmnibusTestCase::class)->in('../packages/Webkul/Omnibus/tests');
uses(PayGlocalTestCase::class)->in('../packages/Webkul/PayGlocal/tests');
uses(PaymentTestCase::class)->in('../packages/Webkul/Payment/tests');
uses(PayUTestCase::class)->in('../packages/Webkul/PayU/tests');
uses(ProductTestCase::class)->in('../packages/Webkul/Product/tests');
uses(RazorpayTestCase::class)->in('../packages/Webkul/Razorpay/tests');
uses(RuleTestCase::class)->in('../packages/Webkul/Rule/tests');
uses(SalesTestCase::class)->in('../packages/Webkul/Sales/tests');
uses(ShippingTestCase::class)->in('../packages/Webkul/Shipping/tests');
uses(ShopTestCase::class)->in('../packages/Webkul/Shop/tests');
uses(StripeTestCase::class)->in('../packages/Webkul/Stripe/tests');
uses(TaxTestCase::class)->in('../packages/Webkul/Tax/tests');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| Custom expectations shared by every package suite. Prices are compared at the current
| currency's precision, the way the storefront and the admin display them, so a test never
| fails on a floating point tail the customer would never see.
|
*/

expect()->extend('toBePrice', function (float $expected, ?int $decimal = null) {
    $decimal ??= core()->getCurrentCurrency()->decimal;

    expect(number_format((float) $this->value, $decimal))->toBe(number_format($expected, $decimal));

    return $this;
});

/*
|--------------------------------------------------------------------------
| Datasets
|--------------------------------------------------------------------------
|
| Datasets shared by every package suite live in tests/Datasets and are loaded by Pest on
| boot. Pest scopes a dataset to the directory it is declared in, which would hide one
| declared here from the package suites, so they are registered against the repository
| root instead. Package-specific datasets belong next to the tests that use them.
|
*/

/**
 * Register a dataset every test in the repository can use, whichever package it lives in.
 */
function sharedDataset(string $name, Closure|iterable $dataset): void
{
    DatasetsRepository::set($name, $dataset, dirname(__DIR__));
}
