<?php

use Webkul\Tax\Models\TaxRate;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the tax rate index page', function () {
    $this->loginAsAdmin();

    get(route('admin.settings.taxes.rates.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.taxes.rates.index.title'))
        ->assertSeeText(trans('admin::app.settings.taxes.rates.index.button-title'));
});

it('should deny guest access to the tax rate index page', function () {
    get(route('admin.settings.taxes.rates.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Create
// ============================================================================

it('should return the tax rate create page', function () {
    $this->loginAsAdmin();

    get(route('admin.settings.taxes.rates.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.taxes.rates.create.title'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created tax rate', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.taxes.rates.store'), $data = [
        'identifier' => strtolower(fake()->unique()->word()),
        'country' => fake()->countryCode(),
        'state' => fake()->state(),
        'tax_rate' => rand(1, 50),
    ])
        ->assertRedirect(route('admin.settings.taxes.rates.index'));

    $this->assertDatabaseHas('tax_rates', [
        'identifier' => $data['identifier'],
        'country' => $data['country'],
        'tax_rate' => $data['tax_rate'],
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.taxes.rates.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('identifier')
        ->assertJsonValidationErrorFor('country')
        ->assertJsonValidationErrorFor('tax_rate');
});

// ============================================================================
// Edit
// ============================================================================

it('should return the tax rate edit page', function () {
    $taxRate = TaxRate::factory()->create();

    $this->loginAsAdmin();

    get(route('admin.settings.taxes.rates.edit', $taxRate->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.taxes.rates.edit.title'));
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing tax rate', function () {
    $taxRate = TaxRate::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.taxes.rates.update', $taxRate->id), $data = [
        'identifier' => strtolower(fake()->unique()->word()),
        'country' => fake()->countryCode(),
        'tax_rate' => $taxRate->tax_rate,
    ])
        ->assertRedirect(route('admin.settings.taxes.rates.index'));

    $this->assertDatabaseHas('tax_rates', [
        'id' => $taxRate->id,
        'identifier' => $data['identifier'],
        'country' => $data['country'],
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $taxRate = TaxRate::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.taxes.rates.update', $taxRate->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('identifier')
        ->assertJsonValidationErrorFor('country')
        ->assertJsonValidationErrorFor('tax_rate');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a tax rate', function () {
    $taxRate = TaxRate::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.settings.taxes.rates.delete', $taxRate->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.settings.taxes.rates.delete-success'));

    $this->assertDatabaseMissing('tax_rates', ['id' => $taxRate->id]);
});
