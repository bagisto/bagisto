<?php

use Webkul\Admin\Tests\AdminTestCase;
use Webkul\Core\Tests\CoreTestCase;
use Webkul\Customer\Tests\CustomerTestCase;
use Webkul\DataGrid\Tests\DataGridTestCase;
use Webkul\Installer\Tests\InstallerTestCase;
use Webkul\Payment\Tests\PaymentTestCase;
use Webkul\PayU\Tests\PayUTestCase;
use Webkul\Razorpay\Tests\RazorpayTestCase;
use Webkul\Shop\Tests\ShopTestCase;
use Webkul\Stripe\Tests\StripeTestCase;

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
uses(CoreTestCase::class)->in('../packages/Webkul/Core/tests');
uses(CustomerTestCase::class)->in('../packages/Webkul/Customer/tests');
uses(DataGridTestCase::class)->in('../packages/Webkul/DataGrid/tests');
uses(InstallerTestCase::class)->in('../packages/Webkul/Installer/tests');
uses(PaymentTestCase::class)->in('../packages/Webkul/Payment/tests');
uses(PayUTestCase::class)->in('../packages/Webkul/PayU/tests');
uses(RazorpayTestCase::class)->in('../packages/Webkul/Razorpay/tests');
uses(ShopTestCase::class)->in('../packages/Webkul/Shop/tests');
uses(StripeTestCase::class)->in('../packages/Webkul/Stripe/tests');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}
