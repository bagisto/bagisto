<?php

use Webkul\Core\Models\Currency;
use Webkul\Core\Models\CurrencyExchangeRate;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should returns the exchange rate index page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.settings.exchange_rates.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.exchange-rates.index.title'))
        ->assertSeeText(trans('admin::app.settings.exchange-rates.index.create-btn'))
        ->assertSeeText(trans('admin::app.settings.exchange-rates.index.update-rates'));
});

it('should fail the validation with errors when certain field not provided when store the exchange rates', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.settings.exchange_rates.store'))
        ->assertJsonValidationErrorFor('target_currency')
        ->assertJsonValidationErrorFor('rate')
        ->assertUnprocessable();
});

it('should store the newly created exchange rates', function () {
    // Arrange.
    $currency = Currency::first();

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.settings.exchange_rates.store'), [
        'rate'            => $rate = rand(1, 100),
        'target_currency' => $currency->id,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.exchange-rates.index.create-success'));

    $this->assertModelWise([
        CurrencyExchangeRate::class => [
            [
                'rate'            => $rate,
                'target_currency' => $currency->id,
            ],
        ],
    ]);
});

it('should return the exchange rate data for edit', function () {
    // Arrange.
    $exchangeRate = CurrencyExchangeRate::factory()->create([
        'target_currency' => Currency::factory()->create()->id,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.settings.exchange_rates.edit', $exchangeRate->id))
        ->assertOk()
        ->assertJsonPath('data.exchangeRate.id', $exchangeRate->id)
        ->assertJsonPath('data.exchangeRate.target_currency', $exchangeRate->target_currency);
});

it('should fail the validation with errors when certain field not provided when update the exchange rates', function () {
    // Arrange.
    $exchangeRate = CurrencyExchangeRate::factory()->create([
        'target_currency' => Currency::factory()->create()->id,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.settings.exchange_rates.update'), [
        'id' => $exchangeRate->id,
    ])
        ->assertJsonValidationErrorFor('target_currency')
        ->assertJsonValidationErrorFor('rate')
        ->assertUnprocessable();
});

it('should update the currency exchange rate', function () {
    // Arrange.
    $exchangeRate = CurrencyExchangeRate::factory()->create([
        'target_currency' => Currency::factory()->create()->id,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.settings.exchange_rates.update'), [
        'id'              => $exchangeRate->id,
        'rate'            => $rate = rand(1, 100),
        'target_currency' => $exchangeRate->target_currency,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.settings.exchange-rates.index.update-success'));

    $this->assertModelWise([
        CurrencyExchangeRate::class => [
            [
                'rate'            => $rate,
                'target_currency' => $exchangeRate->target_currency,
            ],
        ],
    ]);
});

it('should delete the exchange rate', function () {
    // Arrange.
    $currencyExchangeRate = CurrencyExchangeRate::factory()->create([
        'target_currency' => Currency::factory()->create()->id,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.settings.exchange_rates.delete', $currencyExchangeRate->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.exchange-rates.index.delete-success'));

    $this->assertDatabaseMissing('locales', [
        'id' => $currencyExchangeRate->id,
    ]);
});
