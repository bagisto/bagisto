<?php

use Webkul\Core\Models\Currency;
use Webkul\Core\Models\CurrencyExchangeRate;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the exchange rate index page', function () {
    $this->loginAsAdmin();

    get(route('admin.settings.exchange_rates.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.exchange-rates.index.title'))
        ->assertSeeText(trans('admin::app.settings.exchange-rates.index.create-btn'))
        ->assertSeeText(trans('admin::app.settings.exchange-rates.index.update-rates'));
});

it('should deny guest access to the exchange rate index page', function () {
    get(route('admin.settings.exchange_rates.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created exchange rate', function () {
    $currency = Currency::factory()->create([
        'code' => 'EUR',
        'name' => 'Euro',
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.settings.exchange_rates.store'), [
        'rate' => $rate = rand(1, 100),
        'target_currency' => $currency->id,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.exchange-rates.index.create-success'));

    $this->assertDatabaseHas('currency_exchange_rates', [
        'rate' => $rate,
        'target_currency' => $currency->id,
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.exchange_rates.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('target_currency')
        ->assertJsonValidationErrorFor('rate');
});

// ============================================================================
// Edit
// ============================================================================

it('should return exchange rate data for edit', function () {
    $exchangeRate = CurrencyExchangeRate::factory()->create([
        'target_currency' => Currency::factory()->create()->id,
    ]);

    $this->loginAsAdmin();

    get(route('admin.settings.exchange_rates.edit', $exchangeRate->id))
        ->assertOk()
        ->assertJsonPath('data.exchangeRate.id', $exchangeRate->id)
        ->assertJsonPath('data.exchangeRate.target_currency', $exchangeRate->target_currency);
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing exchange rate', function () {
    $exchangeRate = CurrencyExchangeRate::factory()->create([
        'target_currency' => Currency::factory()->create()->id,
    ]);

    $this->loginAsAdmin();

    putJson(route('admin.settings.exchange_rates.update'), [
        'id' => $exchangeRate->id,
        'rate' => $rate = rand(1, 100),
        'target_currency' => $exchangeRate->target_currency,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.settings.exchange-rates.index.update-success'));

    $this->assertDatabaseHas('currency_exchange_rates', [
        'id' => $exchangeRate->id,
        'rate' => $rate,
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $exchangeRate = CurrencyExchangeRate::factory()->create([
        'target_currency' => Currency::factory()->create()->id,
    ]);

    $this->loginAsAdmin();

    putJson(route('admin.settings.exchange_rates.update'), [
        'id' => $exchangeRate->id,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('target_currency')
        ->assertJsonValidationErrorFor('rate');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete an exchange rate', function () {
    $exchangeRate = CurrencyExchangeRate::factory()->create([
        'target_currency' => Currency::factory()->create()->id,
    ]);

    $this->loginAsAdmin();

    deleteJson(route('admin.settings.exchange_rates.delete', $exchangeRate->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.exchange-rates.index.delete-success'));

    $this->assertDatabaseMissing('currency_exchange_rates', ['id' => $exchangeRate->id]);
});
