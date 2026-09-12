<?php

use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Faker\Helpers\Customer as CustomerFaker;
use Webkul\Faker\Helpers\Product as ProductFaker;

use function Pest\Laravel\postJson;

it('should fail the validation when the billing state does not belong to the selected country when storing the cart address', function (string $state) {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = (new CustomerFaker)->factory()->create();

    $cart = Cart::factory()->create([
        'customer_id' => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
        'customer_email' => $customer->email,
        'is_guest' => 0,
        'is_active' => 0,
        'items_count' => null,
    ]);

    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'sku' => $product->sku,
        'quantity' => 1,
        'name' => $product->name,
        'price' => $convertedPrice = core()->convertPrice($product->price),
        'base_price' => $product->price,
        'total' => $convertedPrice,
        'base_total' => $product->price,
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

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.sales.cart.addresses.store', $cart->id), [
        'billing' => [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(),
            'address' => [fake()->streetAddress()],
            'city' => fake()->city(),
            'country' => 'IN',
            'state' => $state,
            'postcode' => '110001',
            'phone' => fake()->e164PhoneNumber(),
            'use_for_shipping' => 1,
        ],
    ])
        ->assertJsonValidationErrorFor('billing.state')
        ->assertUnprocessable();
})->with([
    'state of another country' => 'CA',
    'unknown state code' => 'XX',
]);
