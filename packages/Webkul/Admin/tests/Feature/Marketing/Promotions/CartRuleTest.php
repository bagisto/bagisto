<?php

use Webkul\CartRule\Models\CartRule;
use Webkul\CartRule\Models\CartRuleCoupon;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

/**
 * Create a cart rule with channels and customer groups synced.
 */
function createCartRule(array $attributes = []): CartRule
{
    return CartRule::factory()
        ->afterCreating(function (CartRule $rule) {
            $rule->channels()->sync([1]);
            $rule->customer_groups()->sync([1, 2, 3]);
        })
        ->create($attributes);
}

// ============================================================================
// Index
// ============================================================================

it('should return the cart rule index page', function () {
    $this->loginAsAdmin();

    get(route('admin.marketing.promotions.cart_rules.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.cart-rules.index.title'))
        ->assertSeeText(trans('admin::app.marketing.promotions.cart-rules.index.create-btn'));
});

it('should deny guest access to the cart rule index page', function () {
    get(route('admin.marketing.promotions.cart_rules.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Create
// ============================================================================

it('should return the cart rule create page', function () {
    $this->loginAsAdmin();

    get(route('admin.marketing.promotions.cart_rules.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.cart-rules.create.title'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created cart rule', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.promotions.cart_rules.store'), [
        'name' => $name = fake()->words(3, true),
        'description' => $description = fake()->sentence(),
        'channels' => [1],
        'customer_groups' => [1, 2, 3],
        'discount_amount' => 15,
        'coupon_type' => 0,
        'action_type' => $actionType = fake()->randomElement(['by_percent', 'by_fixed', 'cart_fixed', 'buy_x_get_y']),
        'starts_from' => '',
        'ends_till' => '',
    ])
        ->assertRedirect(route('admin.marketing.promotions.cart_rules.index'));

    $this->assertDatabaseHas('cart_rules', [
        'name' => $name,
        'description' => $description,
        'action_type' => $actionType,
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.promotions.cart_rules.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('channels')
        ->assertJsonValidationErrorFor('customer_groups')
        ->assertJsonValidationErrorFor('coupon_type')
        ->assertJsonValidationErrorFor('action_type')
        ->assertJsonValidationErrorFor('discount_amount');
});

// ============================================================================
// Copy
// ============================================================================

it('should copy an existing cart rule', function () {
    $cartRule = createCartRule();

    $this->loginAsAdmin();

    get(route('admin.marketing.promotions.cart_rules.copy', $cartRule->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.cart-rules.edit.title'));

    $this->assertDatabaseHas('cart_rules', [
        'name' => trans('admin::app.marketing.promotions.cart-rules.index.datagrid.copy-of', ['value' => $cartRule->name]),
        'status' => 0,
    ]);
});

// ============================================================================
// Edit
// ============================================================================

it('should return the cart rule edit page', function () {
    $cartRule = createCartRule();

    $this->loginAsAdmin();

    get(route('admin.marketing.promotions.cart_rules.edit', $cartRule->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.cart-rules.edit.title'));
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing cart rule', function () {
    $cartRule = createCartRule();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.promotions.cart_rules.update', $cartRule->id), [
        'name' => $name = fake()->words(3, true),
        'description' => fake()->sentence(),
        'channels' => [1],
        'customer_groups' => [1, 2, 3],
        'discount_amount' => 20,
        'coupon_type' => 0,
        'action_type' => 'by_fixed',
        'starts_from' => '',
        'ends_till' => '',
    ])
        ->assertRedirect(route('admin.marketing.promotions.cart_rules.index'));

    $this->assertDatabaseHas('cart_rules', [
        'id' => $cartRule->id,
        'name' => $name,
        'action_type' => 'by_fixed',
    ]);
});

it('should persist boolean fields when storing a cart rule', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.promotions.cart_rules.store'), [
        'name' => fake()->words(3, true),
        'channels' => [1],
        'customer_groups' => [1, 2, 3],
        'discount_amount' => 10,
        'coupon_type' => 0,
        'action_type' => 'by_percent',
        'status' => 1,
        'apply_to_shipping' => 1,
        'free_shipping' => 1,
        'end_other_rules' => 1,
        'starts_from' => '',
        'ends_till' => '',
    ])
        ->assertRedirect(route('admin.marketing.promotions.cart_rules.index'));

    $cartRule = CartRule::latest('id')->first();

    expect($cartRule->status)->toBeTrue()
        ->and($cartRule->apply_to_shipping)->toBeTrue()
        ->and($cartRule->free_shipping)->toBeTrue()
        ->and($cartRule->end_other_rules)->toBeTrue();
});

it('should persist disabled boolean fields when updating a cart rule', function () {
    $cartRule = createCartRule();

    $this->loginAsAdmin();

    // Update with all boolean fields disabled.
    putJson(route('admin.marketing.promotions.cart_rules.update', $cartRule->id), [
        'name' => $cartRule->name,
        'channels' => [1],
        'customer_groups' => [1, 2, 3],
        'discount_amount' => 10,
        'coupon_type' => 0,
        'action_type' => 'by_percent',
        'status' => 0,
        'apply_to_shipping' => 0,
        'free_shipping' => 0,
        'end_other_rules' => 0,
        'starts_from' => '',
        'ends_till' => '',
    ])
        ->assertRedirect(route('admin.marketing.promotions.cart_rules.index'));

    $cartRule->refresh();

    expect($cartRule->status)->toBeFalse()
        ->and($cartRule->apply_to_shipping)->toBeFalse()
        ->and($cartRule->free_shipping)->toBeFalse()
        ->and($cartRule->end_other_rules)->toBeFalse();
});

it('should fail validation when required fields are missing on update', function () {
    $cartRule = createCartRule();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.promotions.cart_rules.update', $cartRule->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('channels')
        ->assertJsonValidationErrorFor('customer_groups')
        ->assertJsonValidationErrorFor('coupon_type')
        ->assertJsonValidationErrorFor('action_type')
        ->assertJsonValidationErrorFor('discount_amount');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a cart rule', function () {
    $cartRule = createCartRule();

    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.promotions.cart_rules.delete', $cartRule->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.cart-rules.delete-success'));

    $this->assertDatabaseMissing('cart_rules', ['id' => $cartRule->id]);
});

// ============================================================================
// Coupon Generation
// ============================================================================

it('should generate coupon codes for a cart rule', function () {
    $cartRule = createCartRule(['coupon_type' => 1, 'use_auto_generation' => true]);

    $this->loginAsAdmin();

    postJson(route('admin.marketing.promotions.cart_rules.coupons.store', $cartRule->id), [
        'coupon_qty' => 3,
        'code_length' => 12,
        'code_format' => 'alphanumeric',
        'code_prefix' => '',
        'code_suffix' => '',
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.cart-rules-coupons.success', ['name' => 'Cart rule coupons']));

    expect(CartRuleCoupon::where('cart_rule_id', $cartRule->id)->count())->toBe(3);
});

it('should fail validation when coupon generation fields are missing', function () {
    $cartRule = createCartRule(['coupon_type' => 1, 'use_auto_generation' => true]);

    $this->loginAsAdmin();

    postJson(route('admin.marketing.promotions.cart_rules.coupons.store', $cartRule->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('coupon_qty')
        ->assertJsonValidationErrorFor('code_length')
        ->assertJsonValidationErrorFor('code_format');
});

it('should delete a generated coupon code', function () {
    $cartRule = createCartRule(['coupon_type' => 1, 'use_auto_generation' => true]);

    $coupon = CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'is_primary' => 0,
    ]);

    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.promotions.cart_rules.coupons.delete', $coupon->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.cart-rules-coupons.delete-success'));

    $this->assertDatabaseMissing('cart_rule_coupons', ['id' => $coupon->id]);
});

it('should mass delete generated coupon codes', function () {
    $cartRule = createCartRule(['coupon_type' => 1, 'use_auto_generation' => true]);

    $coupons = CartRuleCoupon::factory()->count(2)->create([
        'cart_rule_id' => $cartRule->id,
        'is_primary' => 0,
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.marketing.promotions.cart_rules.coupons.mass_delete'), [
        'indices' => $coupons->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.cart-rules-coupons.mass-delete-success'));

    foreach ($coupons as $coupon) {
        $this->assertDatabaseMissing('cart_rule_coupons', ['id' => $coupon->id]);
    }
});
