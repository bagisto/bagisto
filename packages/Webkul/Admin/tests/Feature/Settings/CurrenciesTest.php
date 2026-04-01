<?php

use Webkul\Core\Models\Currency;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the currencies index page', function () {
    $this->loginAsAdmin();

    get(route('admin.settings.currencies.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.currencies.index.title'))
        ->assertSeeText(trans('admin::app.settings.currencies.index.create-btn'));
});

it('should deny guest access to the currencies index page', function () {
    get(route('admin.settings.currencies.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created currency', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.currencies.store'), $data = [
        'code' => fake()->randomElement(['EUR', 'GBP', 'JPY', 'AUD', 'CHF', 'CAD', 'CNY', 'BRL']),
        'name' => fake()->name(),
        'symbol' => fake()->randomElement(['€', '£', '¥', 'A$', 'CHF', 'C$', '¥', 'R$']),
        'decimal' => rand(1, 4),
        'group_separator' => ',',
        'decimal_separator' => '.',
        'currency_position' => fake()->randomElement(['left', 'left_with_space', 'right', 'right_with_space']),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.currencies.index.create-success'));

    $this->assertDatabaseHas('currencies', [
        'code' => strtoupper($data['code']),
        'name' => $data['name'],
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.currencies.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name');
});

// ============================================================================
// Edit
// ============================================================================

it('should return currency details for edit', function () {
    $currency = Currency::factory()->create();

    $this->loginAsAdmin();

    get(route('admin.settings.currencies.edit', $currency->id))
        ->assertOk()
        ->assertJsonFragment($currency->toArray());
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing currency', function () {
    $currency = Currency::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.currencies.update'), [
        'id' => $currency->id,
        'name' => $name = fake()->name(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.currencies.index.update-success'));

    $this->assertDatabaseHas('currencies', [
        'id' => $currency->id,
        'name' => $name,
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $currency = Currency::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.currencies.update'), [
        'id' => $currency->id,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a currency', function () {
    $currency = Currency::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.settings.currencies.delete', $currency->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.currencies.index.delete-success'));

    $this->assertDatabaseMissing('currencies', ['id' => $currency->id]);
});
