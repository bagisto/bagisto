<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Core\Models\CoreConfig;

/**
 * A cart totalling 100.00 with 10.00 of tax and a 5.00 discount, under the two minimum-order flags.
 */
function cartUnderMinimumOrderFlags(bool $includeTax, bool $includeDiscount): CartModel
{
    foreach ([
        'sales.order_settings.minimum_order.include_tax_to_amount' => $includeTax,
        'sales.order_settings.minimum_order.include_discount_amount' => $includeDiscount,
    ] as $code => $enabled) {
        CoreConfig::query()->where('code', $code)->delete();

        CoreConfig::factory()->create(['code' => $code, 'value' => (int) $enabled]);
    }

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
    int $expected
) {
    cartUnderMinimumOrderFlags($includeTax, $includeDiscount);

    expect(Cart::getOrderAmount())->toBe($expected);
})->with([
    'neither flag: the subtotal alone' => [false, false, 100],
    'tax included: the subtotal plus tax' => [true, false, 110],
    'discount included: the subtotal less the discount' => [false, true, 95],
    'both flags: the subtotal less the discount' => [true, true, 95],
]);
