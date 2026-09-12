<?php

use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Faker\Helpers\Product as ProductFaker;

use function Pest\Laravel\postJson;

beforeEach(function () {
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            26 => 'guest_checkout',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $cart = Cart::factory()->create();

    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'sku' => $product->sku,
        'quantity' => 1,
        'name' => $product->name,
        'price' => $convertedPrice = core()->convertPrice($price = $product->price),
        'price_incl_tax' => $convertedPrice,
        'base_price' => $price,
        'base_price_incl_tax' => $price,
        'total' => $convertedPrice,
        'total_incl_tax' => $convertedPrice,
        'base_total' => $price,
        'weight' => $product->weight ?? 0,
        'total_weight' => $product->weight ?? 0,
        'base_total_weight' => $product->weight ?? 0,
        'type' => $product->type,
        'additional' => [
            'product_id' => $product->id,
            'rating' => '0',
            'is_buy_now' => '0',
            'quantity' => '1',
        ],
    ]);

    cart()->setCart($cart);

    $this->address = fn (string $country, string $state): array => [
        'first_name' => fake()->firstName(),
        'last_name' => fake()->lastName(),
        'email' => fake()->safeEmail(),
        'address' => [fake()->streetAddress()],
        'city' => fake()->city(),
        'country' => $country,
        'state' => $state,
        'postcode' => '110001',
        'phone' => fake()->e164PhoneNumber(),
    ];
});

it('should fail the validation when the billing state belongs to another country', function () {
    // Act and Assert.
    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            ...($this->address)('IN', 'CA'),
            'use_for_shipping' => 1,
        ],
    ])
        ->assertJsonValidationErrors(['billing.state' => trans('validation.in', ['attribute' => 'billing.state'])])
        ->assertUnprocessable();
});

it('should fail the validation when the shipping state belongs to another country', function () {
    // Act and Assert.
    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            ...($this->address)('US', 'CA'),
            'use_for_shipping' => 0,
        ],
        'shipping' => ($this->address)('IN', 'CA'),
    ])
        ->assertJsonValidationErrorFor('shipping.state')
        ->assertJsonMissingValidationErrors('billing.state')
        ->assertUnprocessable();
});

it('should fail the validation when the state is not in the state list of the selected country', function () {
    // Act and Assert.
    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            ...($this->address)('IN', 'XX'),
            'use_for_shipping' => 1,
        ],
    ])
        ->assertJsonValidationErrorFor('billing.state')
        ->assertUnprocessable();
});

it('should store the address when the state belongs to the selected country or the country has no state list', function (string $country, string $state) {
    // Act and Assert.
    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            ...($this->address)($country, $state),
            'use_for_shipping' => 1,
        ],
    ])
        ->assertOk();
})->with([
    'state from the country list' => ['US', 'CA'],
    'free-form state for a country without a list' => ['GB', 'Greater London'],
]);
