<?php

use Illuminate\Support\Str;
use Webkul\Core\Enums\CurrencyPositionEnum;
use Webkul\Core\Models\Currency;

use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Validation
// ============================================================================

it('should refuse a currency symbol carrying markup when a currency is created', function () {
    $name = 'Testing '.Str::random(10);

    $this->loginAsAdmin();

    postJson(route('admin.settings.currencies.store'), [
        'code' => Str::upper(Str::random(3)),
        'name' => $name,
        'symbol' => '<img src=x onerror=alert(1)>',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('symbol');

    $this->assertDatabaseMissing('currencies', ['name' => $name]);
});

it('should refuse a currency format that is not one the storefront can render when a currency is updated', function () {
    $currency = Currency::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.currencies.update'), [
        'id' => $currency->id,
        'name' => 'Testing',
        'symbol' => '"><script>alert(1)</script>',
        'decimal' => 'two',
        'group_separator' => '<b>',
        'decimal_separator' => '<i>',
        'currency_position' => 'middle',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['symbol', 'decimal', 'group_separator', 'decimal_separator', 'currency_position']);

    $this->assertDatabaseHas('currencies', [
        'id' => $currency->id,
        'name' => $currency->name,
        'group_separator' => $currency->group_separator,
        'currency_position' => $currency->currency_position,
    ]);
});

it('should keep accepting an ordinary currency format', function () {
    $currency = Currency::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.currencies.update'), [
        'id' => $currency->id,
        'name' => 'Swiss Franc',
        'symbol' => 'CHF',
        'decimal' => 2,
        'group_separator' => "'",
        'decimal_separator' => '.',
        'currency_position' => CurrencyPositionEnum::RIGHT_WITH_SPACE->value,
    ])
        ->assertOk();

    $this->assertDatabaseHas('currencies', [
        'id' => $currency->id,
        'name' => 'Swiss Franc',
        'symbol' => 'CHF',
        'group_separator' => "'",
        'decimal_separator' => '.',
        'currency_position' => CurrencyPositionEnum::RIGHT_WITH_SPACE->value,
    ]);
});

// ============================================================================
// Storefront
// ============================================================================

it('should escape the currency symbol in a customer group price offer line', function () {
    $product = $this->createSimpleProduct();

    $currency = core()->getCurrentCurrency();

    $currency->symbol = '<img src=x onerror=alert(1)>';

    $currency->currency_position = CurrencyPositionEnum::LEFT->value;

    expect($product->getTypeInstance()->getOfferLines((object) ['qty' => 2]))
        ->not->toContain('<img')
        ->toContain('&lt;img src=x onerror=alert(1)&gt;');
});
