<?php

use Webkul\Core\Enums\CurrencyPositionEnum;
use Webkul\Core\Models\Currency;
use Webkul\Faker\Helpers\Product as ProductFaker;

use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should refuse a currency symbol carrying markup when a currency is created', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.currencies.store'), [
        'code' => 'XTS',
        'name' => 'Testing',
        'symbol' => '<img src=x onerror=alert(1)>',
    ])
        ->assertJsonValidationErrorFor('symbol');

    expect(Currency::where('code', 'XTS')->exists())->toBeFalse();
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
        ->assertJsonValidationErrorFor('symbol')
        ->assertJsonValidationErrorFor('decimal')
        ->assertJsonValidationErrorFor('group_separator')
        ->assertJsonValidationErrorFor('decimal_separator')
        ->assertJsonValidationErrorFor('currency_position');
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

    expect($currency->fresh()->group_separator)->toBe("'");
});

it('should escape the currency symbol in a customer group price offer line', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $currency = core()->getCurrentCurrency();

    $currency->symbol = '<img src=x onerror=alert(1)>';

    $currency->currency_position = CurrencyPositionEnum::LEFT->value;

    $offerLine = $product->getTypeInstance()->getOfferLines((object) ['qty' => 2]);

    expect($offerLine)->not->toContain('<img');

    expect($offerLine)->toContain('&lt;img src=x onerror=alert(1)&gt;');
});
