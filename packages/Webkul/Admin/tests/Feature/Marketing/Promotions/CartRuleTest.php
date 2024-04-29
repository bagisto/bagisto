<?php

use Webkul\CartRule\Models\CartRule;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should returns the cart rule page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.marketing.promotions.cart_rules.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.cart-rules.index.title'))
        ->assertSeeText(trans('admin::app.marketing.promotions.cart-rules.index.create-btn'));
});

it('should returns the create page of cart rules', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.marketing.promotions.cart_rules.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.cart-rules.create.title'))
        ->assertSeeText(trans('admin::app.account.edit.back-btn'))
        ->assertSeeText(trans('admin::app.marketing.promotions.cart-rules.create.save-btn'));
});

it('should fail the validation with errors when certain field not provided when store the cart rule', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.marketing.promotions.cart_rules.store'))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('channels')
        ->assertJsonValidationErrorFor('customer_groups')
        ->assertJsonValidationErrorFor('coupon_type')
        ->assertJsonValidationErrorFor('action_type')
        ->assertJsonValidationErrorFor('discount_amount')
        ->assertUnprocessable();
});

it('should store the newly created cart rule', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.marketing.promotions.cart_rules.store', [
        'name'        => $name = fake()->name(),
        'description' => $description = substr(fake()->paragraph(), 0, 50),

        'channels' => [
            1,
        ],

        'customer_groups' => [
            1,
            2,
            3,
        ],

        'discount_amount' => 0,
        'coupon_type'     => 0,
        'action_type'     => $actionType = fake()->randomElement(['by_percent', 'by_fixed', 'cart_fixed', 'buy_x_get_y']),
        'starts_from'     => '',
        'ends_till'       => '',
    ]))
        ->assertRedirect(route('admin.marketing.promotions.cart_rules.index'))
        ->isRedirection();

    $this->assertModelWise([
        CartRule::class => [
            [
                'name'        => $name,
                'description' => $description,
                'action_type' => $actionType,
            ],
        ],
    ]);
});

it('should copy the existing cart rule', function () {
    // Arrange.
    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([1, 2, 3]);
        $cartRule->cart_rule_channels()->sync([1]);
    })->create();

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.marketing.promotions.cart_rules.copy', $cartRule->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.cart-rules.edit.title'));
});

it('should update the existing cart rule', function () {
    // Arrange.
    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([1, 2, 3]);
        $cartRule->cart_rule_channels()->sync([1]);
    })->create();

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.marketing.promotions.cart_rules.edit', $cartRule->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.cart-rules.edit.title'));
});

it('should fail the validation with errors when certain field not provided when update the cart rule', function () {
    // Arrange.
    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([1, 2, 3]);
        $cartRule->cart_rule_channels()->sync([1]);
    })->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.marketing.promotions.cart_rules.update', $cartRule->id))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('channels')
        ->assertJsonValidationErrorFor('customer_groups')
        ->assertJsonValidationErrorFor('coupon_type')
        ->assertJsonValidationErrorFor('action_type')
        ->assertJsonValidationErrorFor('discount_amount')
        ->assertUnprocessable();
});

it('should update the cart rule', function () {
    // Arrange.
    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([1, 2, 3]);
        $cartRule->cart_rule_channels()->sync([1]);
    })->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.marketing.promotions.cart_rules.update', $cartRule->id), [
        'name'        => $name = fake()->name(),
        'description' => $description = substr(substr(fake()->paragraph(), 0, 50), 0, 50),

        'channels' => [
            1,
        ],

        'customer_groups' => [
            1,
            2,
            3,
        ],

        'discount_amount' => 0,
        'coupon_type'     => 0,
        'action_type'     => $actionType = fake()->randomElement(['by_percent', 'by_fixed', 'cart_fixed', 'buy_x_get_y']),
        'starts_from'     => '',
        'ends_till'       => '',
    ])
        ->assertRedirect(route('admin.marketing.promotions.cart_rules.index'))
        ->isRedirection();

    $this->assertModelWise([
        CartRule::class => [
            [
                'name'        => $name,
                'description' => $description,
                'action_type' => $actionType,
            ],
        ],
    ]);
});

it('should delete the cart rules', function () {
    // Arrange.
    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([1, 2, 3]);
        $cartRule->cart_rule_channels()->sync([1]);
    })->create();

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.promotions.cart_rules.delete', $cartRule->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.cart-rules.delete-success'));

    $this->assertDatabaseMissing('cart_rules', [
        'id' => $cartRule->id,
    ]);
});
