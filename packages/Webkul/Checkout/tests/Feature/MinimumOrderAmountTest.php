<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\Cart as CartModel;

/**
 * A cart totalling 100.00 with 10.00 of tax and a 5.00 discount, under the two minimum-order flags.
 */
function cartUnderMinimumOrderFlags(bool $includeTax, bool $includeDiscount): CartModel
{
    test()->setConfig([
        'sales.order_settings.minimum_order.enable' => 1,
        'sales.order_settings.minimum_order.minimum_order_amount' => 90,
        'sales.order_settings.minimum_order.include_tax_to_amount' => (int) $includeTax,
        'sales.order_settings.minimum_order.include_discount_amount' => (int) $includeDiscount,
    ]);

    $cart = CartModel::factory()->create([
        'sub_total' => 100,
        'base_sub_total' => 100,
        'tax_total' => 10,
        'base_tax_total' => 10,
        'discount_amount' => 5,
        'base_discount_amount' => 5,
        'grand_total' => 105,
        'base_grand_total' => 105,
    ]);

    Cart::setCart($cart);

    return $cart;
}

// ============================================================================
// The Amount The Minimum Order Rule Is Measured Against
// ============================================================================

it('should measure the configured amount for each combination of the two flags', function (
    bool $includeTax,
    bool $includeDiscount,
    float $expected
) {
    cartUnderMinimumOrderFlags($includeTax, $includeDiscount);

    expect(Cart::getOrderAmount())->toBe($expected);
})->with([
    'neither flag: the subtotal alone' => [false, false, 100.0],
    'tax included: the subtotal plus tax' => [true, false, 110.0],
    'discount included: the subtotal less the discount' => [false, true, 95.0],
    'both flags: the subtotal less the discount' => [true, true, 95.0],
]);

// ============================================================================
// Whether The Cart Clears The Minimum
// ============================================================================

it('should clear a minimum of 90 only where the measured amount reaches it', function (
    bool $includeTax,
    bool $includeDiscount,
    bool $clears
) {
    cartUnderMinimumOrderFlags($includeTax, $includeDiscount);

    expect(Cart::haveMinimumOrderAmount())->toBe($clears);
})->with([
    'neither flag' => [false, false, true],
    'tax included' => [true, false, true],
    'discount included' => [false, true, true],
    'both flags' => [true, true, true],
]);

it('should ignore the minimum entirely while the rule is switched off', function () {
    cartUnderMinimumOrderFlags(false, true);

    test()->setConfig('sales.order_settings.minimum_order.enable', 0);

    expect(Cart::haveMinimumOrderAmount())->toBeTrue();
});

// ============================================================================
// The Error Descriptor The Storefront Reads By Key
// ============================================================================

it('should describe a below-minimum cart with the keys its consumers read by name', function () {
    cartUnderMinimumOrderFlags(false, false);

    $this->setConfig('sales.order_settings.minimum_order.minimum_order_amount', 500);

    $errors = Cart::getErrors();

    expect(array_keys($errors))->toBe(['error_code', 'message', 'amount'])
        ->and($errors['error_code'])->toBe('MINIMUM_ORDER_AMOUNT')
        ->and($errors['amount'])->toBe(core()->formatPrice(500));
});

it('should prefer the configured description over the generic minimum-order message', function () {
    cartUnderMinimumOrderFlags(false, false);

    $this->setConfig([
        'sales.order_settings.minimum_order.minimum_order_amount' => 500,
        'sales.order_settings.minimum_order.description' => 'Spend a little more.',
    ]);

    expect(Cart::getErrors()['message'])->toBe('Spend a little more.');
});
