<?php

use Webkul\Tax\Models\TaxRate;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

afterEach(function () {
    /**
     * Cleaning up rows which are created.
     */
    TaxRate::query()->delete();
});

it('should returns the tax rate index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.taxes.rates.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.taxes.rates.index.title'))
        ->assertSeeText(trans('admin::app.settings.taxes.rates.index.button-title'));
});

it('should returns the create page of tax rate', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.taxes.rates.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.taxes.rates.create.title'))
        ->assertSeeText(trans('admin::app.settings.taxes.rates.create.back-btn'));
});

it('should store the newly created tax rates', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.taxes.rates.store'), [
        'identifier' => $identifier = strtolower(fake()->name()),
        'country'    => $country = fake()->country(),
        'tax_rate'   => $taxRate = rand(1, 50),
    ])
        ->assertRedirect(route('admin.settings.taxes.rates.index'))
        ->isRedirection();

    $this->assertDAtabaseHas('tax_rates', [
        'identifier' => $identifier,
        'country'    => $country,
        'tax_rate'   => $taxRate,
    ]);
});

it('should returns the edit page of the tax rate', function () {
    // Arrange
    $taxRate = TaxRate::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.taxes.rates.edit', $taxRate->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.taxes.rates.edit.title'))
        ->assertSeeText(trans('admin::app.settings.taxes.rates.edit.back-btn'));
});

it('should update the tax rate', function () {
    // Arrange
    $taxRate = TaxRate::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.settings.taxes.rates.update', $taxRate->id), [
        'identifier' => $identifier = fake()->name(),
        'country'    => $country = fake()->country(),
        'tax_rate'   => $taxRate->tax_rate,
    ])
        ->assertRedirect(route('admin.settings.taxes.rates.index'))
        ->isRedirection();

    $this->assertDatabaseHas('tax_rates', [
        'identifier' => $identifier,
        'country'    => $country,
        'tax_rate'   => $taxRate->tax_rate,
    ]);
});

it('should delete the tax rate', function () {
    // Arrange
    $taxRate = TaxRate::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.settings.taxes.rates.delete', $taxRate->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.settings.taxes.rates.delete-success'));

    $this->assertDatabaseMissing('tax_rates', [
        'id' => $taxRate->id,
    ]);
});
