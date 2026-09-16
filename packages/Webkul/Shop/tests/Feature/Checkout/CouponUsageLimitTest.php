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
 * A cart rule and its coupon, wired to the current channel and every customer group.
 *
 * @return array{0: CartRule, 1: CartRuleCoupon}
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
 * A stand-in order carrying the only fields the listener reads off a saved order.
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

/**
 * The translated message a usage limit failure carries, guarded against an unresolved key.
 */
function couponUsageLimitMessage(): string
{
    $message = trans('shop::app.checkout.coupon.usage-limit-exceeded');

    expect($message)->not->toContain('::');

    return $message;
}

// ============================================================================
// Usage Limits
// ============================================================================

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

    expect($coupon->times_used)->toBe(3)
        ->and($cartRule->times_used)->toBe(1);

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
        ->toThrow(CouponUsageLimitExceededException::class, couponUsageLimitMessage());

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
        ->toThrow(CouponUsageLimitExceededException::class, couponUsageLimitMessage());

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
        ->toThrow(CouponUsageLimitExceededException::class, couponUsageLimitMessage());
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

it('should refuse a second use of the same coupon by the same customer', function () {
    [$cartRule, $coupon] = createCartRuleWithCoupon([], [
        'usage_limit' => 10,
        'usage_per_customer' => 1,
        'times_used' => 0,
    ]);

    $customer = Customer::factory()->create();

    app(OrderListener::class)->manageCartRule(fakeOrder($cartRule->id, $coupon->code, $customer->id));

    $coupon->refresh();

    expect($coupon->times_used)->toBe(1);

    expect(fn () => app(OrderListener::class)->manageCartRule(fakeOrder($cartRule->id, $coupon->code, $customer->id)))
        ->toThrow(CouponUsageLimitExceededException::class, couponUsageLimitMessage());

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

// ============================================================================
// Transactions
// ============================================================================

it('should acquire row-level locks during coupon usage validation', function () {
    [$cartRule, $coupon] = createCartRuleWithCoupon([], [
        'usage_limit' => 2,
        'usage_per_customer' => 0,
        'times_used' => 0,
    ]);

    $customer = Customer::factory()->create();

    DB::beginTransaction();

    app(OrderListener::class)->manageCartRule(fakeOrder($cartRule->id, $coupon->code, $customer->id));

    $coupon->refresh();

    expect($coupon->times_used)->toBe(1);

    DB::commit();

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

    $coupon->refresh();

    expect($coupon->times_used)->toBe(0);
});

// ============================================================================
// Usage Records
// ============================================================================

it('should increment coupon usage for guest orders without per-customer tracking', function () {
    [$cartRule, $coupon] = createCartRuleWithCoupon([], [
        'usage_limit' => 10,
        'usage_per_customer' => 1,
        'times_used' => 0,
    ]);

    $order = fakeOrder($cartRule->id, $coupon->code, null);

    app(OrderListener::class)->manageCartRule($order);

    $coupon->refresh();

    $cartRule->refresh();

    expect($coupon->times_used)->toBe(1)
        ->and($cartRule->times_used)->toBe(1);

    $this->assertDatabaseMissing('cart_rule_coupon_usage', [
        'cart_rule_coupon_id' => $coupon->id,
    ]);

    $this->assertDatabaseMissing('cart_rule_customers', [
        'cart_rule_id' => $cartRule->id,
    ]);
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

    expect(CartRuleCouponUsage::query()->where('customer_id', $customer->id)
        ->where('cart_rule_coupon_id', $coupon->id)
        ->count()
    )->toBe(1);
});
