<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartItem;
use Webkul\Core\Models\Currency;
use Webkul\Core\Models\CurrencyExchangeRate;
use Webkul\Shipping\Carriers\FlatRate;
use Webkul\Shipping\Carriers\Free;
use Webkul\Shipping\Facades\Shipping;

/**
 * Save the given settings of a carrier, keyed without their `sales.carriers.{code}.` prefix.
 */
function configureCarrier(string $code, array $settings): void
{
    test()->setConfig(collect($settings)->mapWithKeys(fn ($value, $key) => ["sales.carriers.{$code}.{$key}" => $value])->all());
}

/**
 * Make a cart holding the given products and quantities the current one.
 */
function currentCartHolding(array $lines): CartModel
{
    $cart = CartModel::factory()->create();

    CartAddress::factory()->create([
        'cart_id' => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    foreach ($lines as [$product, $quantity]) {
        CartItem::factory()->adjustProduct()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'sku' => $product->sku,
            'name' => $product->name,
            'type' => $product->type,
            'quantity' => $quantity,
            'additional' => ['product_id' => $product->id, 'quantity' => $quantity],
        ]);
    }

    Cart::setCart($cart->fresh());

    return $cart;
}

beforeEach(function () {
    configureCarrier('flatrate', [
        'active' => '1',
        'title' => 'Flat Rate',
        'description' => 'Flat Rate Shipping',
        'default_rate' => '10',
        'type' => 'per_unit',
    ]);

    configureCarrier('free', [
        'active' => '1',
        'title' => 'Free Shipping',
        'description' => 'Free Shipping',
    ]);
});

// ============================================================================
// Flat Rate
// ============================================================================

it('charges the flat rate once per unit of every item that ships', function () {
    currentCartHolding([
        [$this->createSimpleProduct(), 3],
        [$this->createVirtualProduct(), 2],
    ]);

    $rate = (new FlatRate)->calculate();

    expect($rate)
        ->carrier->toBe('flatrate')
        ->method->toBe('flatrate_flatrate')
        ->carrier_title->toBe('Flat Rate')
        ->and((float) $rate->base_price)->toBePrice(30)
        ->and((float) $rate->price)->toBePrice(30);
});

it('charges the flat rate once per order when it is set up that way', function () {
    configureCarrier('flatrate', ['type' => 'per_order']);

    currentCartHolding([
        [$this->createSimpleProduct(), 3],
    ]);

    expect((float) (new FlatRate)->calculate()->base_price)->toBePrice(10);
});

it('converts the flat rate into the currency the shopper browses in', function () {
    $product = $this->createSimpleProduct();

    $currency = Currency::factory()->create();

    CurrencyExchangeRate::factory()->create([
        'target_currency' => $currency->id,
        'rate' => 2,
    ]);

    core()->getCurrentChannel()->currencies()->attach($currency->id);

    core()->setCurrentCurrency($currency->code);

    currentCartHolding([
        [$product, 1],
    ]);

    $rate = (new FlatRate)->calculate();

    expect((float) $rate->base_price)->toBePrice(10)
        ->and((float) $rate->price)->toBePrice(20);
});

it('offers no flat rate while the carrier is switched off', function () {
    configureCarrier('flatrate', ['active' => '0']);

    currentCartHolding([
        [$this->createSimpleProduct(), 1],
    ]);

    expect((new FlatRate)->calculate())->toBeFalse();
});

// ============================================================================
// Free Shipping
// ============================================================================

it('ships for nothing with the free carrier', function () {
    currentCartHolding([
        [$this->createSimpleProduct(), 3],
    ]);

    $rate = (new Free)->calculate();

    expect($rate)
        ->carrier->toBe('free')
        ->method->toBe('free_free')
        ->and((float) $rate->price)->toBePrice(0)
        ->and((float) $rate->base_price)->toBePrice(0);
});

it('offers no free shipping while the carrier is switched off', function () {
    configureCarrier('free', ['active' => '0']);

    currentCartHolding([
        [$this->createSimpleProduct(), 1],
    ]);

    expect((new Free)->calculate())->toBeFalse();
});

// ============================================================================
// Rate Collection
// ============================================================================

it('collects and stores the rates of every active carrier for the cart', function () {
    $cart = currentCartHolding([
        [$this->createSimpleProduct(), 2],
    ]);

    $methods = collect(Shipping::collectRates()['shippingMethods']);

    expect($methods->keys()->all())->toContain('flatrate', 'free');

    foreach (['flatrate_flatrate', 'free_free'] as $method) {
        $this->assertDatabaseHas('cart_shipping_rates', [
            'cart_id' => $cart->id,
            'method' => $method,
        ]);
    }

    expect(Shipping::isMethodCodeExists('flatrate_flatrate'))->toBeTrue()
        ->and(Shipping::isMethodCodeExists('courier_overnight'))->toBeFalse();
});

it('leaves a switched off carrier out of the collected rates', function () {
    configureCarrier('flatrate', ['active' => '0']);

    currentCartHolding([
        [$this->createSimpleProduct(), 1],
    ]);

    $methods = collect(Shipping::collectRates()['shippingMethods']);

    expect($methods->keys()->all())->toBe(['free']);
});

it('collects nothing without a cart', function () {
    expect(Shipping::collectRates())->toBeFalse();
});

it('lists the active shipping methods with their titles', function () {
    $methods = collect(Shipping::getShippingMethods());

    expect($methods->firstWhere('code', 'flatrate'))
        ->method->toBe('flatrate_flatrate')
        ->method_title->toBe('Flat Rate')
        ->and($methods->firstWhere('code', 'free'))
        ->method->toBe('free_free');
});
