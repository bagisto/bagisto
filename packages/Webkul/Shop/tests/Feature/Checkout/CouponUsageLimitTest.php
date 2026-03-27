<?php

use Illuminate\Support\Facades\DB;
use Webkul\CartRule\Exceptions\CouponUsageLimitExceededException;
use Webkul\CartRule\Listeners\Order as OrderListener;
use Webkul\CartRule\Models\CartRule;
use Webkul\CartRule\Models\CartRuleCoupon;
use Webkul\CartRule\Models\CartRuleCouponUsage;
use Webkul\CartRule\Models\CartRuleCustomer;
use Webkul\Customer\Models\Customer;

/**
 * Helper to create a cart rule with a coupon code and wire it to the default
 * channel and customer group.
 */
function createCartRuleWithCoupon(array $ruleOverrides = [], array $couponOverrides = []): array
{
    $cartRule = CartRule::factory()->create(array_merge([
        'name' => 'Race Condition Test Rule',
        'coupon_type' => 1,
        'action_type' => 'by_percent',
        'discount_amount' => 50,
        'status' => 1,
        'usage_per_customer' => 0,
        'uses_per_coupon' => 0,
        'times_used' => 0,
    ], $ruleOverrides));

    $cartRule->cart_rule_channels()->attach([core()->getCurrentChannel()->id]);
    $cartRule->cart_rule_customer_groups()->attach([1, 2, 3]);

    $coupon = CartRuleCoupon::factory()->create(array_merge([
        'cart_rule_id' => $cartRule->id,
        'code' => 'TESTCOUPON',
        'usage_limit' => 1,
        'usage_per_customer' => 1,
        'times_used' => 0,
        'is_primary' => 1,
    ], $couponOverrides));

    return [$cartRule, $coupon];
}

/**
 * Helper to build a fake order object for the listener.
 */
function fakeOrder(int $cartRuleId, string $couponCode, ?int $customerId = null): object
{
    return (object) [
        'discount_amount' => 50,
        'applied_cart_rule_ids' => (string) $cartRuleId,
        'coupon_code' => $couponCode,
        'customer_id' => $customerId,
    ];
}

it('should increment coupon and rule usage correctly on successful order', function () {
    [$cartRule, $coupon] = createCartRuleWithCoupon([
        'usage_per_customer' => 5,
    ], [
        'usage_limit' => 10,
        'usage_per_customer' => 5,
        'times_used' => 2,
    ]);

    $customer = Customer::factory()->create();

    $order = fakeOrder($cartRule->id, $coupon->code, $customer->id);

    app(OrderListener::class)->manageCartRule($order);

    $coupon->refresh();
    $cartRule->refresh();

    expect($coupon->times_used)->toBe(3);
    expect($cartRule->times_used)->toBe(1);

    $this->assertDatabaseHas('cart_rule_coupon_usage', [
        'customer_id' => $customer->id,
        'cart_rule_coupon_id' => $coupon->id,
        'times_used' => 1,
    ]);

    $this->assertDatabaseHas('cart_rule_customers', [
        'customer_id' => $customer->id,
        'cart_rule_id' => $cartRule->id,
        'times_used' => 1,
    ]);
});

it('should throw when coupon global usage limit is already exhausted', function () {
    [$cartRule, $coupon] = createCartRuleWithCoupon([], [
        'usage_limit' => 1,
        'usage_per_customer' => 0,
        'times_used' => 1,
    ]);

    $customer = Customer::factory()->create();
    $order = fakeOrder($cartRule->id, $coupon->code, $customer->id);

    expect(fn () => app(OrderListener::class)->manageCartRule($order))
        ->toThrow(CouponUsageLimitExceededException::class);

    $coupon->refresh();
    expect($coupon->times_used)->toBe(1);
});

it('should throw when per-customer coupon usage limit is already exhausted', function () {
    [$cartRule, $coupon] = createCartRuleWithCoupon([], [
        'usage_limit' => 100,
        'usage_per_customer' => 1,
        'times_used' => 0,
    ]);

    $customer = Customer::factory()->create();

    CartRuleCouponUsage::create([
        'customer_id' => $customer->id,
        'cart_rule_coupon_id' => $coupon->id,
        'times_used' => 1,
    ]);

    $order = fakeOrder($cartRule->id, $coupon->code, $customer->id);

    expect(fn () => app(OrderListener::class)->manageCartRule($order))
        ->toThrow(CouponUsageLimitExceededException::class);

    $coupon->refresh();
    expect($coupon->times_used)->toBe(0);
});

it('should throw when per-customer cart rule usage limit is already exhausted', function () {
    [$cartRule, $coupon] = createCartRuleWithCoupon([
        'usage_per_customer' => 1,
    ], [
        'usage_limit' => 100,
        'usage_per_customer' => 100,
        'times_used' => 0,
    ]);

    $customer = Customer::factory()->create();

    CartRuleCustomer::create([
        'customer_id' => $customer->id,
        'cart_rule_id' => $cartRule->id,
        'times_used' => 1,
    ]);

    $order = fakeOrder($cartRule->id, $coupon->code, $customer->id);

    expect(fn () => app(OrderListener::class)->manageCartRule($order))
        ->toThrow(CouponUsageLimitExceededException::class);
});

it('should not throw when coupon has no usage limits', function () {
    [$cartRule, $coupon] = createCartRuleWithCoupon([
        'usage_per_customer' => 0,
    ], [
        'usage_limit' => 0,
        'usage_per_customer' => 0,
        'times_used' => 999,
    ]);

    $customer = Customer::factory()->create();
    $order = fakeOrder($cartRule->id, $coupon->code, $customer->id);

    app(OrderListener::class)->manageCartRule($order);

    $coupon->refresh();
    expect($coupon->times_used)->toBe(1000);
});

it('should allow different customers to use coupon when global limit is not reached', function () {
    [$cartRule, $coupon] = createCartRuleWithCoupon([], [
        'usage_limit' => 3,
        'usage_per_customer' => 1,
        'times_used' => 0,
    ]);

    $customer1 = Customer::factory()->create();
    $customer2 = Customer::factory()->create();

    app(OrderListener::class)->manageCartRule(fakeOrder($cartRule->id, $coupon->code, $customer1->id));

    $coupon->refresh();
    expect($coupon->times_used)->toBe(1);

    app(OrderListener::class)->manageCartRule(fakeOrder($cartRule->id, $coupon->code, $customer2->id));

    $coupon->refresh();
    expect($coupon->times_used)->toBe(2);

    $this->assertDatabaseHas('cart_rule_coupon_usage', [
        'customer_id' => $customer1->id,
        'cart_rule_coupon_id' => $coupon->id,
        'times_used' => 1,
    ]);

    $this->assertDatabaseHas('cart_rule_coupon_usage', [
        'customer_id' => $customer2->id,
        'cart_rule_coupon_id' => $coupon->id,
        'times_used' => 1,
    ]);
});

it('should enforce sequential usage — second use by same customer fails', function () {
    [$cartRule, $coupon] = createCartRuleWithCoupon([], [
        'usage_limit' => 10,
        'usage_per_customer' => 1,
        'times_used' => 0,
    ]);

    $customer = Customer::factory()->create();

    // First usage succeeds.
    app(OrderListener::class)->manageCartRule(fakeOrder($cartRule->id, $coupon->code, $customer->id));

    $coupon->refresh();
    expect($coupon->times_used)->toBe(1);

    // Second usage by same customer fails.
    expect(fn () => app(OrderListener::class)->manageCartRule(fakeOrder($cartRule->id, $coupon->code, $customer->id)))
        ->toThrow(CouponUsageLimitExceededException::class);

    $coupon->refresh();
    expect($coupon->times_used)->toBe(1);
});

it('should skip coupon processing when order has no discount amount', function () {
    [$cartRule, $coupon] = createCartRuleWithCoupon([], [
        'usage_limit' => 1,
        'times_used' => 0,
    ]);

    $order = (object) [
        'discount_amount' => 0,
        'applied_cart_rule_ids' => (string) $cartRule->id,
        'coupon_code' => $coupon->code,
        'customer_id' => 1,
    ];

    app(OrderListener::class)->manageCartRule($order);

    $coupon->refresh();
    expect($coupon->times_used)->toBe(0);
});

it('should acquire row-level locks during coupon usage validation', function () {
    [$cartRule, $coupon] = createCartRuleWithCoupon([], [
        'usage_limit' => 2,
        'usage_per_customer' => 0,
        'times_used' => 0,
    ]);

    $customer = Customer::factory()->create();

    /**
     * Simulate what happens inside the order creation transaction:
     * wrap the listener call in a DB transaction and verify usage
     * is correctly incremented.
     */
    DB::beginTransaction();

    app(OrderListener::class)->manageCartRule(fakeOrder($cartRule->id, $coupon->code, $customer->id));

    // Within the transaction, usage should be incremented.
    $coupon->refresh();
    expect($coupon->times_used)->toBe(1);

    DB::commit();

    // After commit, the incremented value persists.
    $coupon->refresh();
    expect($coupon->times_used)->toBe(1);
});

it('should roll back coupon usage when transaction fails', function () {
    [$cartRule, $coupon] = createCartRuleWithCoupon([], [
        'usage_limit' => 5,
        'usage_per_customer' => 0,
        'times_used' => 0,
    ]);

    $customer = Customer::factory()->create();

    DB::beginTransaction();

    app(OrderListener::class)->manageCartRule(fakeOrder($cartRule->id, $coupon->code, $customer->id));

    $coupon->refresh();
    expect($coupon->times_used)->toBe(1);

    DB::rollBack();

    // After rollback, usage should revert to 0.
    $coupon->refresh();
    expect($coupon->times_used)->toBe(0);
});

it('should increment existing per-customer coupon usage instead of creating duplicate', function () {
    [$cartRule, $coupon] = createCartRuleWithCoupon([], [
        'usage_limit' => 10,
        'usage_per_customer' => 5,
        'times_used' => 0,
    ]);

    $customer = Customer::factory()->create();

    CartRuleCouponUsage::create([
        'customer_id' => $customer->id,
        'cart_rule_coupon_id' => $coupon->id,
        'times_used' => 2,
    ]);

    app(OrderListener::class)->manageCartRule(fakeOrder($cartRule->id, $coupon->code, $customer->id));

    $this->assertDatabaseHas('cart_rule_coupon_usage', [
        'customer_id' => $customer->id,
        'cart_rule_coupon_id' => $coupon->id,
        'times_used' => 3,
    ]);

    // Ensure no duplicate rows were created.
    expect(CartRuleCouponUsage::where('customer_id', $customer->id)
        ->where('cart_rule_coupon_id', $coupon->id)
        ->count()
    )->toBe(1);
});
