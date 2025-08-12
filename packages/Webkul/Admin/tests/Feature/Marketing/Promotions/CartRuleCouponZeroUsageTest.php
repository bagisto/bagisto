<?php

use Webkul\CartRule\Models\CartRule;
use Webkul\CartRule\Models\CartRuleCoupon;
use Webkul\Checkout\Facades\Cart;
use Webkul\Product\Models\Product;

use function Pest\Laravel\postJson;

it('should block coupon usage when uses_per_coupon is set to zero', function () {
    // Arrange
    $this->loginAsAdmin();

    // Create a product
    $product = Product::factory()->create([
        'status' => 1,
        'type' => 'simple',
    ]);

    // Create a cart rule with coupon usage limit set to 0
    $cartRule = CartRule::factory()->create([
        'name' => 'Zero Usage Test',
        'status' => 1,
        'coupon_type' => 1, // specific coupon
        'uses_per_coupon' => 0, // This should block all usage
        'usage_per_customer' => 5,
        'action_type' => 'by_percent',
        'discount_amount' => 10,
    ]);

    $cartRule->cart_rule_customer_groups()->sync([1, 2, 3]);
    $cartRule->cart_rule_channels()->sync([1]);

    // Create a coupon for the cart rule
    $coupon = CartRuleCoupon::create([
        'cart_rule_id' => $cartRule->id,
        'code' => 'ZEROLIMIT',
        'usage_limit' => 0, // Zero usage limit should block usage
        'usage_per_customer' => 5,
        'is_primary' => 1,
    ]);

    // Add product to cart
    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    // Act & Assert - Try to apply the coupon
    postJson(route('shop.api.checkout.cart.coupons.store'), [
        'code' => 'ZEROLIMIT'
    ])
        ->assertStatus(422) // Should be rejected
        ->assertJsonPath('message', 'Coupon not found.'); // Should not be found/applicable

    // Verify coupon was not applied
    $cart = Cart::getCart();
    expect($cart->coupon_code)->toBeNull();
});

it('should block coupon usage when usage_per_customer is set to zero', function () {
    // Arrange
    $this->loginAsAdmin();

    // Create a product
    $product = Product::factory()->create([
        'status' => 1,
        'type' => 'simple',
    ]);

    // Create a cart rule with per-customer usage limit set to 0
    $cartRule = CartRule::factory()->create([
        'name' => 'Zero Customer Usage Test',
        'status' => 1,
        'coupon_type' => 1, // specific coupon
        'uses_per_coupon' => 10,
        'usage_per_customer' => 0, // This should block all usage for customers
        'action_type' => 'by_percent',
        'discount_amount' => 10,
    ]);

    $cartRule->cart_rule_customer_groups()->sync([1, 2, 3]);
    $cartRule->cart_rule_channels()->sync([1]);

    // Create a coupon for the cart rule
    $coupon = CartRuleCoupon::create([
        'cart_rule_id' => $cartRule->id,
        'code' => 'ZEROCUSTOMER',
        'usage_limit' => 10,
        'usage_per_customer' => 0, // Zero per-customer usage should block usage
        'is_primary' => 1,
    ]);

    // Add product to cart
    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    // Act & Assert - Try to apply the coupon
    postJson(route('shop.api.checkout.cart.coupons.store'), [
        'code' => 'ZEROCUSTOMER'
    ])
        ->assertStatus(422) // Should be rejected
        ->assertJsonPath('message', 'Coupon not found.'); // Should not be found/applicable

    // Verify coupon was not applied
    $cart = Cart::getCart();
    expect($cart->coupon_code)->toBeNull();
});

it('should allow coupon usage when usage limits are greater than zero', function () {
    // Arrange
    $this->loginAsAdmin();

    // Create a product
    $product = Product::factory()->create([
        'status' => 1,
        'type' => 'simple',
    ]);

    // Create a cart rule with positive usage limits
    $cartRule = CartRule::factory()->create([
        'name' => 'Normal Usage Test',
        'status' => 1,
        'coupon_type' => 1, // specific coupon
        'uses_per_coupon' => 5,
        'usage_per_customer' => 2,
        'action_type' => 'by_percent',
        'discount_amount' => 10,
    ]);

    $cartRule->cart_rule_customer_groups()->sync([1, 2, 3]);
    $cartRule->cart_rule_channels()->sync([1]);

    // Create a coupon for the cart rule
    $coupon = CartRuleCoupon::create([
        'cart_rule_id' => $cartRule->id,
        'code' => 'NORMALUSAGE',
        'usage_limit' => 5,
        'usage_per_customer' => 2,
        'is_primary' => 1,
    ]);

    // Add product to cart
    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    // Act & Assert - Try to apply the coupon
    postJson(route('shop.api.checkout.cart.coupons.store'), [
        'code' => 'NORMALUSAGE'
    ])
        ->assertStatus(200) // Should be accepted
        ->assertJsonPath('message', trans('shop::app.checkout.coupon.success-apply'));

    // Verify coupon was applied
    $cart = Cart::getCart();
    expect($cart->coupon_code)->toBe('NORMALUSAGE');
});