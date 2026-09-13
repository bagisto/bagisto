<?php

use Illuminate\Support\Str;
use Webkul\Core\Core;
use Webkul\Core\Enums\CurrencyPositionEnum;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Currency;
use Webkul\Core\Models\CurrencyExchangeRate;
use Webkul\Core\Models\Locale;

/**
 * A currency code no row carries yet, so that a lookup by code finds the currency the test creates.
 */
function unusedCurrencyCode(): string
{
    do {
        $code = Str::upper(Str::random(3));
    } while (Currency::query()->where('code', $code)->exists());

    return $code;
}

/**
 * Create a currency with the given symbol and position, and a channel that uses it as its base currency.
 */
function currencyOfNewCurrentChannel(?string $symbol, ?string $position, ?string $code = null): Currency
{
    $currency = Currency::factory()->create([
        'code' => $code ?? unusedCurrencyCode(),
        'symbol' => $symbol,
        'currency_position' => $position,
    ]);

    $channel = Channel::factory()->create(['base_currency_id' => $currency->id]);

    $channel->currencies()->sync([$currency->id]);

    core()->setCurrentChannel($channel);

    return $currency;
}

dataset('currency positions', [
    'left' => [CurrencyPositionEnum::LEFT, '%1$s%2$s'],
    'left with space' => [CurrencyPositionEnum::LEFT_WITH_SPACE, '%1$s %2$s'],
    'right' => [CurrencyPositionEnum::RIGHT, '%2$s%1$s'],
    'right with space' => [CurrencyPositionEnum::RIGHT_WITH_SPACE, '%2$s %1$s'],
]);

// ============================================================================
// Channels
// ============================================================================

it('should list every channel', function () {
    $channel = Channel::factory()->create();

    expect(core()->getAllChannels()->pluck('id')->all())
        ->toContain($channel->id)
        ->toHaveCount(Channel::query()->count());
});

it('should resolve the current channel from the host that serves it', function () {
    $channel = Channel::factory()->create(['hostname' => 'https://'.($host = Str::lower(Str::random(8)).'.test')]);

    expect(app(Core::class)->getCurrentChannel($host)->is($channel))->toBeTrue();
});

it('should fall back to the first channel when no channel serves the host', function () {
    expect(app(Core::class)->getCurrentChannel(Str::lower(Str::random(8)).'.test')->is(Channel::query()->first()))->toBeTrue();
});

it('should return the channel set as the current one along with its code', function () {
    $channel = Channel::factory()->create();

    core()->setCurrentChannel($channel);

    expect(core()->getCurrentChannel()->is($channel))->toBeTrue()
        ->and(core()->getCurrentChannelCode())->toBe($channel->code);
});

it('should return the channel configured as the default one', function () {
    $channel = Channel::factory()->create();

    config()->set('app.channel', $channel->code);

    expect(core()->getDefaultChannel()->is($channel))->toBeTrue()
        ->and(core()->getDefaultChannelCode())->toBe($channel->code);
});

it('should fall back to the first channel when the configured default channel does not exist', function () {
    config()->set('app.channel', 'wrong_channel_code');

    expect(core()->getDefaultChannel()->is(Channel::query()->first()))->toBeTrue();
});

it('should return the channel set as the default one', function () {
    $channel = Channel::factory()->create();

    core()->setDefaultChannel($channel);

    expect(core()->getDefaultChannel()->is($channel))->toBeTrue()
        ->and(core()->getDefaultChannelCode())->toBe($channel->code);
});

it('should return the channel named in the request', function () {
    $channel = Channel::factory()->create();

    request()->merge(['channel' => $channel->code]);

    expect(core()->getRequestedChannel()->is($channel))->toBeTrue()
        ->and(core()->getRequestedChannelCode())->toBe($channel->code);
});

it('should fall back to the current channel when the request names none', function () {
    $channel = Channel::factory()->create();

    core()->setCurrentChannel($channel);

    expect(core()->getRequestedChannel()->is($channel))->toBeTrue()
        ->and(core()->getRequestedChannelCode())->toBe($channel->code)
        ->and(core()->getRequestedChannelCode(false))->toBeNull();
});

// ============================================================================
// Locales
// ============================================================================

it('should list every locale sorted by name', function () {
    $locale = Locale::factory()->create();

    $locales = core()->getAllLocales();

    expect($locales->pluck('id')->all())->toContain($locale->id)
        ->and($locales->pluck('name')->values()->all())->toBe($locales->pluck('name')->sort()->values()->all());
});

it('should return the locale of the application as the current one', function () {
    $locale = Locale::factory()->create();

    app()->setLocale($locale->code);

    expect(core()->getCurrentLocale()->is($locale))->toBeTrue();
});

it('should return the locale named in the request and fall back to the application locale', function () {
    $locale = Locale::factory()->create();

    expect(core()->getRequestedLocaleCode())->toBe(app()->getLocale())
        ->and(core()->getRequestedLocaleCode('locale', false))->toBeNull()
        ->and(core()->getRequestedLocaleCodes())->toBe([app()->getLocale()]);

    request()->merge(['locale' => $locale->code]);

    expect(core()->getRequestedLocale()->is($locale))->toBeTrue()
        ->and(core()->getRequestedLocaleCode())->toBe($locale->code);
});

it('should expand the all option of the locale switcher to every locale code', function () {
    $locale = Locale::factory()->create();

    request()->merge(['locale' => 'all']);

    expect(core()->getRequestedLocaleCodes())->toContain($locale->code)
        ->toHaveCount(Locale::query()->count());
});

// ============================================================================
// Price Formatting
// ============================================================================

it('should place the symbol of the current currency by its position', function (CurrencyPositionEnum $position, string $pattern) {
    currencyOfNewCurrentChannel('₹', $position->value);

    expect(core()->formatPrice(1234.5))->toBe(sprintf($pattern, '₹', '1,234.50'));
})->with('currency positions');

it('should place the code of the current currency by its position when it has no symbol', function (CurrencyPositionEnum $position, string $pattern) {
    $currency = currencyOfNewCurrentChannel('', $position->value);

    expect(core()->formatPrice(1234.5))->toBe(sprintf($pattern, $currency->code, '1,234.50'));
})->with('currency positions');

it('should format a price in the currency named by its code', function () {
    currencyOfNewCurrentChannel('₹', CurrencyPositionEnum::LEFT->value);

    $currency = Currency::factory()->create([
        'code' => unusedCurrencyCode(),
        'symbol' => '£',
        'currency_position' => CurrencyPositionEnum::RIGHT_WITH_SPACE->value,
    ]);

    expect(core()->formatPrice(1234.5, $currency->code))->toBe('1,234.50 £');
});

it('should format a base price in the currency configured as the base one', function () {
    $currency = Currency::factory()->create([
        'code' => unusedCurrencyCode(),
        'symbol' => '₹',
        'currency_position' => CurrencyPositionEnum::LEFT_WITH_SPACE->value,
    ]);

    config()->set('app.currency', $currency->code);

    expect(core()->getBaseCurrencyCode())->toBe($currency->code)
        ->and(core()->formatBasePrice(1234.5))->toBe('₹ 1,234.50');
});

it('should format a missing price as zero', function () {
    currencyOfNewCurrentChannel('₹', CurrencyPositionEnum::LEFT->value);

    expect(core()->formatPrice(null))->toBe('₹0.00');
});

it('should use the separators of the currency', function () {
    $currency = currencyOfNewCurrentChannel('€', CurrencyPositionEnum::RIGHT_WITH_SPACE->value);

    $currency->update([
        'group_separator' => '.',
        'decimal_separator' => ',',
    ]);

    core()->setCurrentChannel(core()->getCurrentChannel()->fresh());

    expect(core()->formatPrice(1234.5))->toBe('1.234,50 €');
});

it('should fall back to the locale formatter when the currency has no position', function () {
    currencyOfNewCurrentChannel(null, null, 'EUR');

    expect(core()->formatPrice(1234.5))->toBe('€1,234.50');
});

it('should hand a custom symbol to the locale formatter when the currency has no position', function () {
    currencyOfNewCurrentChannel('€', null, 'USD');

    expect(core()->formatPrice(1234.5))->toBe('€1,234.50');
});

// ============================================================================
// Currency Conversion
// ============================================================================

it('should convert prices to and from the current currency at its exchange rate', function () {
    currencyOfNewCurrentChannel('$', CurrencyPositionEnum::LEFT->value);

    $currency = Currency::factory()->create([
        'code' => unusedCurrencyCode(),
        'symbol' => '₹',
        'currency_position' => CurrencyPositionEnum::LEFT->value,
    ]);

    CurrencyExchangeRate::factory()->create([
        'target_currency' => $currency->id,
        'rate' => 2,
    ]);

    core()->setCurrentCurrency($currency->code);

    expect(core()->getCurrentCurrencyCode())->toBe($currency->code)
        ->and(core()->convertPrice(100))->toBePrice(200)
        ->and(core()->convertToBasePrice(200))->toBePrice(100)
        ->and(core()->currency(100))->toBe('₹200.00');
});

it('should leave a price unchanged when the currency has no exchange rate', function () {
    $currency = Currency::factory()->create(['code' => unusedCurrencyCode()]);

    expect(core()->convertPrice(100, $currency->code))->toBePrice(100)
        ->and(core()->convertToBasePrice(100, $currency->code))->toBePrice(100)
        ->and(core()->convertPrice(100, 'NOPE'))->toBePrice(100);
});

it('should fall back to the base currency of the channel when the current currency does not exist', function () {
    $currency = currencyOfNewCurrentChannel('₹', CurrencyPositionEnum::LEFT->value);

    core()->setCurrentCurrency('NOPE');

    expect(core()->getCurrentCurrency()->is($currency))->toBeTrue();
});
