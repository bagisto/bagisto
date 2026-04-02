<?php

use Webkul\Customer\Models\Customer;

// ============================================================================
// No Coupon — Fixed Discount
// ============================================================================

it('should apply fixed cart rule discount for all customer groups on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 500]], [0]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $response = $this->addProductToCart($product->id, 1, ['links' => $product->downloadable_links->pluck('id')->toArray()])->assertOk();

    $this->assertCartDiscount($response, 50);
});

it('should apply fixed cart rule discount for guest customer on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 500]], [0]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 30], [1]);

    $response = $this->addProductToCart($product->id, 1, ['links' => $product->downloadable_links->pluck('id')->toArray()])->assertOk();

    $this->assertCartDiscount($response, 30);
});

it('should apply fixed cart rule discount for general customer on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 500]], [0]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 40], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, ['links' => $product->downloadable_links->pluck('id')->toArray()])->assertOk();

    $this->assertCartDiscount($response, 40);
});

it('should apply fixed cart rule discount for wholesaler customer on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 500]], [0]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 60], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, ['links' => $product->downloadable_links->pluck('id')->toArray()])->assertOk();

    $this->assertCartDiscount($response, 60);
});

// ============================================================================
// No Coupon — Percentage Discount
// ============================================================================

it('should apply percentage cart rule discount for all customer groups on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 10]);

    $response = $this->addProductToCart($product->id, 1, ['links' => $product->downloadable_links->pluck('id')->toArray()])->assertOk();

    $this->assertCartDiscount($response, 100);
});

it('should apply percentage cart rule discount for guest customer on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 15], [1]);

    $response = $this->addProductToCart($product->id, 1, ['links' => $product->downloadable_links->pluck('id')->toArray()])->assertOk();

    $this->assertCartDiscount($response, 150);
});

it('should apply percentage cart rule discount for general customer on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, ['links' => $product->downloadable_links->pluck('id')->toArray()])->assertOk();

    $this->assertCartDiscount($response, 200);
});

it('should apply percentage cart rule discount for wholesaler customer on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 25], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, ['links' => $product->downloadable_links->pluck('id')->toArray()])->assertOk();

    $this->assertCartDiscount($response, 250);
});

// ============================================================================
// Specific Coupon
// ============================================================================

it('should apply coupon with fixed discount for all customer groups on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 500]], [0]);

    $this->createCouponCartRule('SAVE75', ['action_type' => 'by_fixed', 'discount_amount' => 75]);

    $this->addProductToCart($product->id, 1, ['links' => $product->downloadable_links->pluck('id')->toArray()])->assertOk();

    $response = $this->applyCoupon('SAVE75')->assertOk();

    $this->assertCartDiscount($response, 75);
    $response->assertJsonPath('data.coupon_code', 'SAVE75');
});

it('should apply coupon with fixed discount for guest customer on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 500]], [0]);

    $this->createCouponCartRule('GUEST50', ['action_type' => 'by_fixed', 'discount_amount' => 50], [1]);

    $this->addProductToCart($product->id, 1, ['links' => $product->downloadable_links->pluck('id')->toArray()])->assertOk();

    $response = $this->applyCoupon('GUEST50')->assertOk();

    $this->assertCartDiscount($response, 50);
});

it('should apply coupon with fixed discount for general customer on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 500]], [0]);

    $this->createCouponCartRule('GEN60', ['action_type' => 'by_fixed', 'discount_amount' => 60], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $this->addProductToCart($product->id, 1, ['links' => $product->downloadable_links->pluck('id')->toArray()])->assertOk();

    $response = $this->applyCoupon('GEN60')->assertOk();

    $this->assertCartDiscount($response, 60);
});

it('should apply coupon with fixed discount for wholesaler customer on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 500]], [0]);

    $this->createCouponCartRule('WHOLE80', ['action_type' => 'by_fixed', 'discount_amount' => 80], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $this->addProductToCart($product->id, 1, ['links' => $product->downloadable_links->pluck('id')->toArray()])->assertOk();

    $response = $this->applyCoupon('WHOLE80')->assertOk();

    $this->assertCartDiscount($response, 80);
});

it('should apply coupon with percentage discount for all customer groups on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->createCouponCartRule('SAVE20', ['action_type' => 'by_percent', 'discount_amount' => 20]);

    $this->addProductToCart($product->id, 1, ['links' => $product->downloadable_links->pluck('id')->toArray()])->assertOk();

    $response = $this->applyCoupon('SAVE20')->assertOk();

    $this->assertCartDiscount($response, 200);
    $response->assertJsonPath('data.coupon_code', 'SAVE20');
});
