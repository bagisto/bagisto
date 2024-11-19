<?php

use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartItem;
use Webkul\Core\Models\CoreConfig;
use Webkul\Customer\Models\Customer;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should display the cart items for a guest user', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
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

    $additional = [
        'product_id' => $product->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $product->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $response = get(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.is_guest', $cart->is_guest)
        ->assertJsonPath('data.customer_id', $cart->customer_id)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $cart->refresh();

    $response->assertJsonPath('data.formatted_discount_amount', core()->currency($cart->discount_amount));

    $this->assertPrice(! empty($cart->tax_total) ? $cart->tax_total : 0, $response['data']['tax_total']);

    $this->assertPrice(! empty($cart->discount_amount) ? $cart->discount_amount : 0, $response['data']['discount_amount']);

    $this->assertPrice($cart->grand_total, $response['data']['grand_total']);

    $this->assertPrice($cart->sub_total, $response['data']['sub_total']);

    foreach ($cart->items as $key => $cartItem) {
        $response->assertJsonPath('data.items.'.$key.'.id', $cartItem->id);
        $response->assertJsonPath('data.items.'.$key.'.quantity', $cartItem->quantity);
        $response->assertJsonPath('data.items.'.$key.'.type', $cartItem->type);
        $response->assertJsonPath('data.items.'.$key.'.name', $cartItem->name);
        $response->assertJsonPath('data.items.'.$key.'.price', $cartItem->price);
        $response->assertJsonPath('data.items.'.$key.'.formatted_price', core()->formatPrice($cartItem->price));
        $response->assertJsonPath('data.items.'.$key.'.total', $cartItem->total);
        $response->assertJsonPath('data.items.'.$key.'.formatted_total', core()->formatPrice($cartItem->total));
        $response->assertJsonPath('data.items.'.$key.'.options', $cartItem->options ?? []);
        $response->assertJsonPath('data.items.'.$key.'.product_url_key', $cartItem->product->url_key);
    }
});

it('should display the cart items for a customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
    ]);

    $additional = [
        'product_id' => $product->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $product->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = get(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.is_guest', $cart->is_guest)
        ->assertJsonPath('data.customer_id', $cart->customer_id)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $cart->refresh();

    $response->assertJsonPath('data.formatted_discount_amount', core()->currency($cart->discount_amount));

    $this->assertPrice(! empty($cart->tax_total) ? $cart->tax_total : 0, $response['data']['tax_total']);

    $this->assertPrice(! empty($cart->discount_amount) ? $cart->discount_amount : 0, $response['data']['discount_amount']);

    $this->assertPrice($cart->grand_total, $response['data']['grand_total']);

    $this->assertPrice($cart->sub_total, $response['data']['sub_total']);

    foreach ($cart->items as $key => $cartItem) {
        $response->assertJsonPath('data.items.'.$key.'.id', $cartItem->id);
        $response->assertJsonPath('data.items.'.$key.'.quantity', $cartItem->quantity);
        $response->assertJsonPath('data.items.'.$key.'.type', $cartItem->type);
        $response->assertJsonPath('data.items.'.$key.'.name', $cartItem->name);
        $response->assertJsonPath('data.items.'.$key.'.price', $cartItem->price);
        $response->assertJsonPath('data.items.'.$key.'.formatted_price', core()->formatPrice($cartItem->price));
        $response->assertJsonPath('data.items.'.$key.'.total', $cartItem->total);
        $response->assertJsonPath('data.items.'.$key.'.formatted_total', core()->formatPrice($cartItem->total));
        $response->assertJsonPath('data.items.'.$key.'.options', $cartItem->options ?? []);
        $response->assertJsonPath('data.items.'.$key.'.product_url_key', $cartItem->product->url_key);
    }
});

it('should fails the validation error when the cart item id not provided when remove product items into the cart for a guest user', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
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

    $additional = [
        'product_id' => $product->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $product->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    // Act and Assert.
    deleteJson(route('shop.api.checkout.cart.destroy'))
        ->assertJsonValidationErrorFor('cart_item_id')
        ->assertUnprocessable();
});

it('should fails the validation error when the cart item id not provided when remove product items into the cart for a customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
    ]);

    $additional = [
        'product_id' => $product->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $product->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    cart()->setCart($cart);

    // Act and Assert.
    $this->loginAsCustomer($customer);

    deleteJson(route('shop.api.checkout.cart.destroy'))
        ->assertJsonValidationErrorFor('cart_item_id')
        ->assertUnprocessable();
});

it('should fails the validation error when the wrong cart item id provided when remove product items to the cart for a guest user', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
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

    $additional = [
        'product_id' => $product->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $product->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    // Act and Assert.
    deleteJson(route('shop.api.checkout.cart.destroy'), [
        'cart_item_id' => 'WRONG_ID',
    ])
        ->assertJsonValidationErrorFor('cart_item_id')
        ->assertUnprocessable();
});

it('should fails the validation error when the wrong cart item id provided when remove product items to the cart for a customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
    ]);

    $additional = [
        'product_id' => $product->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $product->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    $this->loginAsCustomer($customer);

    // Act and Assert.
    deleteJson(route('shop.api.checkout.cart.destroy'), [
        'cart_item_id' => 'WRONG_ID',
    ])
        ->assertJsonValidationErrorFor('cart_item_id')
        ->assertUnprocessable();
});

it('should remove only one product item from the cart for the guest user', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
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

    $additional = [
        'product_id' => $product->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $cartItem = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $product->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    cart()->collectTotals();

    cart()->setCart($cart);

    // Act and Assert.
    deleteJson(route('shop.api.checkout.cart.destroy', [
        'cart_item_id' => $cartItem->id,
    ]))
        ->assertOk()
        ->assertJsonPath('data', null)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.success-remove'));

    $this->assertDatabaseMissing('cart_items', [
        'id' => $cartItem->id,
    ]);

    $this->assertDatabaseMissing('cart', [
        'id' => $cart->id,
    ]);
});

it('should remove only one product item from the cart for the customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
    ]);

    $additional = [
        'product_id' => $product->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $cartItem = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $product->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    cart()->collectTotals();

    cart()->setCart($cart);

    // Act and Assert.
    $this->loginAsCustomer($customer);

    deleteJson(route('shop.api.checkout.cart.destroy', [
        'cart_item_id' => $cartItem->id,
    ]))
        ->assertOk()
        ->assertJsonPath('data', null)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.success-remove'));

    $this->assertDatabaseMissing('cart_items', [
        'id' => $cartItem->id,
    ]);

    $this->assertDatabaseMissing('cart', [
        'id' => $cart->id,
    ]);
});

it('should only remove one product from the cart for now the cart will contains two products for a guest user', function () {
    // Arrange.
    $products = (new ProductFaker([
        'attributes' => [
            5  => 'new',
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
        ->count(2)
        ->create();

    [$product1, $product2] = $products;

    $cart = Cart::factory()->create();

    $additional1 = [
        'product_id' => $product1->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $additional2 = [
        'product_id' => $product2->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $cartItem1 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product1->id,
        'sku'               => $product1->sku,
        'quantity'          => $additional1['quantity'],
        'name'              => $product1->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product1->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional1['quantity'],
        'base_total'        => $price * $additional1['quantity'],
        'weight'            => $product1->weight ?? 0,
        'total_weight'      => ($product1->weight ?? 0) * $additional1['quantity'],
        'base_total_weight' => ($product1->weight ?? 0) * $additional1['quantity'],
        'type'              => $product1->type,
        'additional'        => $additional1,
    ]);

    $cartItem2 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product2->id,
        'sku'               => $product2->sku,
        'quantity'          => $additional2['quantity'],
        'name'              => $product2->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product2->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional2['quantity'],
        'base_total'        => $price * $additional2['quantity'],
        'weight'            => $product2->weight ?? 0,
        'total_weight'      => ($product2->weight ?? 0) * $additional2['quantity'],
        'base_total_weight' => ($product2->weight ?? 0) * $additional2['quantity'],
        'type'              => $product2->type,
        'additional'        => $additional2,
    ]);

    cart()->collectTotals();

    cart()->setCart($cart);

    // Act and Assert.
    $response = deleteJson(route('shop.api.checkout.cart.destroy'), [
        'cart_item_id' => $cartItem1->id,
    ])
        ->assertOk()
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.is_guest', $cart->is_guest)
        ->assertJsonPath('data.customer_id', $cart->customer_id)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.success-remove'));

    $cart->refresh();

    $cartItem2->refresh();

    $response->assertJsonPath('data.formatted_discount_amount', core()->currency($cart->discount_amount));

    $this->assertPrice(! empty($cart->tax_total) ? $cart->tax_total : 0, $response['data']['tax_total']);

    $this->assertPrice(! empty($cart->discount_amount) ? $cart->discount_amount : 0, $response['data']['discount_amount']);

    $this->assertPrice($cart->grand_total, $response['data']['grand_total']);

    $this->assertPrice($cart->sub_total, $response['data']['sub_total']);

    foreach ($cart->items as $key => $cartItem) {
        $response->assertJsonPath('data.items.'.$key.'.id', $cartItem->id);
        $response->assertJsonPath('data.items.'.$key.'.quantity', $cartItem->quantity);
        $response->assertJsonPath('data.items.'.$key.'.type', $cartItem->type);
        $response->assertJsonPath('data.items.'.$key.'.name', $cartItem->name);
        $response->assertJsonPath('data.items.'.$key.'.price', $cartItem->price);
        $response->assertJsonPath('data.items.'.$key.'.formatted_price', core()->formatPrice($cartItem->price));
        $response->assertJsonPath('data.items.'.$key.'.total', $cartItem->total);
        $response->assertJsonPath('data.items.'.$key.'.formatted_total', core()->formatPrice($cartItem->total));
        $response->assertJsonPath('data.items.'.$key.'.options', $cartItem->options ?? []);
        $response->assertJsonPath('data.items.'.$key.'.product_url_key', $cartItem->product->url_key);
    }

    $this->assertDatabaseMissing('cart_items', [
        'id' => $cartItem1->id,
    ]);

    $cart->refresh();

    $cartItem->refresh();

    $this->assertModelWise([
        Cart::class => [
            $this->prepareCart($cart),
        ],

        CartItem::class => [
            $this->prepareCartItem($cartItem),
        ],
    ]);
});

it('should only remove one product from the cart for now the cart will contains two products for a customer', function () {
    // Arrange.
    $products = (new ProductFaker([
        'attributes' => [
            5  => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->count(2)
        ->create();

    [$product1, $product2] = $products;

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
    ]);

    $additional1 = [
        'product_id' => $product1->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $additional2 = [
        'product_id' => $product2->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $cartItem1 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product1->id,
        'sku'               => $product1->sku,
        'quantity'          => $additional1['quantity'],
        'name'              => $product1->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product1->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional1['quantity'],
        'base_total'        => $price * $additional1['quantity'],
        'weight'            => $product1->weight ?? 0,
        'total_weight'      => ($product1->weight ?? 0) * $additional1['quantity'],
        'base_total_weight' => ($product1->weight ?? 0) * $additional1['quantity'],
        'type'              => $product1->type,
        'additional'        => $additional1,
    ]);

    $cartItem2 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product2->id,
        'sku'               => $product2->sku,
        'quantity'          => $additional2['quantity'],
        'name'              => $product2->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product2->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional2['quantity'],
        'base_total'        => $price * $additional2['quantity'],
        'weight'            => $product2->weight ?? 0,
        'total_weight'      => ($product2->weight ?? 0) * $additional2['quantity'],
        'base_total_weight' => ($product2->weight ?? 0) * $additional2['quantity'],
        'type'              => $product2->type,
        'additional'        => $additional2,
    ]);

    cart()->collectTotals();

    cart()->setCart($cart);

    // Act and Assert.
    $this->loginAsCustomer();

    $response = deleteJson(route('shop.api.checkout.cart.destroy'), [
        'cart_item_id' => $cartItem1->id,
    ])
        ->assertOk()
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.is_guest', $cart->is_guest)
        ->assertJsonPath('data.customer_id', $cart->customer_id)
        ->assertJsonPath('data.items_count', $cart->items_count)
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.success-remove'));

    $cart->refresh();

    $cartItem2->refresh();

    $response->assertJsonPath('data.formatted_discount_amount', core()->currency($cart->discount_amount));

    $this->assertPrice(! empty($cart->tax_total) ? $cart->tax_total : 0, $response['data']['tax_total']);

    $this->assertPrice(! empty($cart->discount_amount) ? $cart->discount_amount : 0, $response['data']['discount_amount']);

    $this->assertPrice($cart->grand_total, $response['data']['grand_total']);

    $this->assertPrice($cart->sub_total, $response['data']['sub_total']);

    foreach ($cart->items as $key => $cartItem) {
        $response->assertJsonPath('data.items.'.$key.'.id', $cartItem->id);
        $response->assertJsonPath('data.items.'.$key.'.quantity', $cartItem->quantity);
        $response->assertJsonPath('data.items.'.$key.'.type', $cartItem->type);
        $response->assertJsonPath('data.items.'.$key.'.name', $cartItem->name);
        $response->assertJsonPath('data.items.'.$key.'.price', $cartItem->price);
        $response->assertJsonPath('data.items.'.$key.'.formatted_price', core()->formatPrice($cartItem->price));
        $response->assertJsonPath('data.items.'.$key.'.total', $cartItem->total);
        $response->assertJsonPath('data.items.'.$key.'.formatted_total', core()->formatPrice($cartItem->total));
        $response->assertJsonPath('data.items.'.$key.'.options', $cartItem->options ?? []);
        $response->assertJsonPath('data.items.'.$key.'.product_url_key', $cartItem->product->url_key);
    }

    $this->assertDatabaseMissing('cart_items', [
        'id' => $cartItem1->id,
    ]);

    $cart->refresh();

    $cartItem2->refresh();

    $this->assertModelWise([
        Cart::class => [
            $this->prepareCart($cart),
        ],

        CartItem::class => [
            $this->prepareCartItem($cartItem2),
        ],
    ]);
});

it('should remove all products from the cart for a guest user', function () {
    // Arrange.
    $products = (new ProductFaker([
        'attributes' => [
            5  => 'new',
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
        ->count(2)
        ->create();

    [$product1, $product2] = $products;

    $cart = Cart::factory()->create();

    $additional1 = [
        'product_id' => $product1->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $additional2 = [
        'product_id' => $product2->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $cartItem1 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product1->id,
        'sku'               => $product1->sku,
        'quantity'          => $additional1['quantity'],
        'name'              => $product1->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product1->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional1['quantity'],
        'base_total'        => $price * $additional1['quantity'],
        'weight'            => $product1->weight ?? 0,
        'total_weight'      => ($product1->weight ?? 0) * $additional1['quantity'],
        'base_total_weight' => ($product1->weight ?? 0) * $additional1['quantity'],
        'type'              => $product1->type,
        'additional'        => $additional1,
    ]);

    $cartItem2 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product2->id,
        'sku'               => $product2->sku,
        'quantity'          => $additional2['quantity'],
        'name'              => $product2->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product2->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional2['quantity'],
        'base_total'        => $price * $additional2['quantity'],
        'weight'            => $product2->weight ?? 0,
        'total_weight'      => ($product2->weight ?? 0) * $additional2['quantity'],
        'base_total_weight' => ($product2->weight ?? 0) * $additional2['quantity'],
        'type'              => $product2->type,
        'additional'        => $additional2,
    ]);

    cart()->collectTotals();

    cart()->setCart($cart);

    // Act and Assert.
    deleteJson(route('shop.api.checkout.cart.destroy_selected'), [
        'ids' => [$cartItem1->id, $cartItem2->id],
    ]);

    $this->assertDatabaseMissing('cart_items', [
        'id' => $cartItem1->id,
    ]);

    $this->assertDatabaseMissing('cart_items', [
        'id' => $cartItem2->id,
    ]);
});

it('should remove all products from the cart for a customer', function () {
    // Arrange.
    $products = (new ProductFaker([
        'attributes' => [
            5  => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->count(2)
        ->create();

    [$product1, $product2] = $products;

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
    ]);

    $additional1 = [
        'product_id' => $product1->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $additional2 = [
        'product_id' => $product2->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $cartItem1 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product1->id,
        'sku'               => $product1->sku,
        'quantity'          => $additional1['quantity'],
        'name'              => $product1->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product1->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional1['quantity'],
        'base_total'        => $price * $additional1['quantity'],
        'weight'            => $product1->weight ?? 0,
        'total_weight'      => ($product1->weight ?? 0) * $additional1['quantity'],
        'base_total_weight' => ($product1->weight ?? 0) * $additional1['quantity'],
        'type'              => $product1->type,
        'additional'        => $additional1,
    ]);

    $cartItem2 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product2->id,
        'sku'               => $product2->sku,
        'quantity'          => $additional2['quantity'],
        'name'              => $product2->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product2->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional2['quantity'],
        'base_total'        => $price * $additional2['quantity'],
        'weight'            => $product2->weight ?? 0,
        'total_weight'      => ($product2->weight ?? 0) * $additional2['quantity'],
        'base_total_weight' => ($product2->weight ?? 0) * $additional2['quantity'],
        'type'              => $product2->type,
        'additional'        => $additional2,
    ]);

    cart()->collectTotals();

    cart()->setCart($cart);

    // Act and Assert.
    $this->loginAsCustomer();

    deleteJson(route('shop.api.checkout.cart.destroy_selected'), [
        'ids' => [$cartItem1->id, $cartItem2->id],
    ]);

    $this->assertDatabaseMissing('cart_items', [
        'id' => $cartItem1->id,
    ]);

    $this->assertDatabaseMissing('cart_items', [
        'id' => $cartItem2->id,
    ]);
});

it('should update cart quantities for guest user', function () {
    // Arrange.
    $products = (new ProductFaker([
        'attributes' => [
            5  => 'new',
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
        ->count(2)
        ->create();

    [$product1, $product2] = $products;

    $cart = Cart::factory()->create();

    $additional1 = [
        'product_id' => $product1->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $additional2 = [
        'product_id' => $product2->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $cartItem1 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product1->id,
        'sku'               => $product1->sku,
        'quantity'          => $additional1['quantity'],
        'name'              => $product1->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product1->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional1['quantity'],
        'base_total'        => $price * $additional1['quantity'],
        'weight'            => $product1->weight ?? 0,
        'total_weight'      => ($product1->weight ?? 0) * $additional1['quantity'],
        'base_total_weight' => ($product1->weight ?? 0) * $additional1['quantity'],
        'type'              => $product1->type,
        'additional'        => $additional1,
    ]);

    $cartItem2 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product2->id,
        'sku'               => $product2->sku,
        'quantity'          => $additional2['quantity'],
        'name'              => $product2->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product2->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional2['quantity'],
        'base_total'        => $price * $additional2['quantity'],
        'weight'            => $product2->weight ?? 0,
        'total_weight'      => ($product2->weight ?? 0) * $additional2['quantity'],
        'base_total_weight' => ($product2->weight ?? 0) * $additional2['quantity'],
        'type'              => $product2->type,
        'additional'        => $additional2,
    ]);

    cart()->collectTotals();

    cart()->setCart($cart);

    // Act and Assert.
    $response = putJson(route('shop.api.checkout.cart.update'), [
        'qty' => $data = [
            $cartItem1->id => rand(2, 10),
            $cartItem2->id => rand(2, 10),
        ],
    ])
        ->assertOk()
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.is_guest', $cart->is_guest)
        ->assertJsonPath('data.customer_id', $cart->customer_id)
        ->assertJsonPath('data.items_count', 2)
        ->assertJsonPath('data.items_qty', array_sum($data))
        ->assertJsonPath('message', trans('shop::app.checkout.cart.index.quantity-update'));

    $cart->refresh();

    $cartItem1->refresh();

    $cartItem2->refresh();

    $response->assertJsonPath('data.formatted_discount_amount', core()->currency($cart->discount_amount));

    $this->assertPrice(! empty($cart->tax_total) ? $cart->tax_total : 0, $response['data']['tax_total']);

    $this->assertPrice(! empty($cart->discount_amount) ? $cart->discount_amount : 0, $response['data']['discount_amount']);

    $this->assertPrice($cart->grand_total, $response['data']['grand_total']);

    $this->assertPrice($cart->sub_total, $response['data']['sub_total']);

    $cart->refresh();

    $cartItem1->refresh();

    $cartItem2->refresh();

    $this->assertModelWise([
        CartItem::class => [
            $this->prepareCartItem($cartItem1),

            $this->prepareCartItem($cartItem2),
        ],
    ]);

    foreach ($cart->items as $cartItem) {
        $this->assertModelWise([
            CartItem::class => [
                $this->prepareCartItem($cartItem),
            ],
        ]);
    }
});

it('should update cart quantities for customer', function () {
    // Arrange.
    $products = (new ProductFaker([
        'attributes' => [
            5  => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->count(2)
        ->create();

    [$product1, $product2] = $products;

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
    ]);

    $additional1 = [
        'product_id' => $product1->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $additional2 = [
        'product_id' => $product2->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $cartItem1 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product1->id,
        'sku'               => $product1->sku,
        'quantity'          => $additional1['quantity'],
        'name'              => $product1->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product1->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional1['quantity'],
        'base_total'        => $price * $additional1['quantity'],
        'weight'            => $product1->weight ?? 0,
        'total_weight'      => ($product1->weight ?? 0) * $additional1['quantity'],
        'base_total_weight' => ($product1->weight ?? 0) * $additional1['quantity'],
        'type'              => $product1->type,
        'additional'        => $additional1,
    ]);

    $cartItem2 = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product2->id,
        'sku'               => $product2->sku,
        'quantity'          => $additional2['quantity'],
        'name'              => $product2->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $product2->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional2['quantity'],
        'base_total'        => $price * $additional2['quantity'],
        'weight'            => $product2->weight ?? 0,
        'total_weight'      => ($product2->weight ?? 0) * $additional2['quantity'],
        'base_total_weight' => ($product2->weight ?? 0) * $additional2['quantity'],
        'type'              => $product2->type,
        'additional'        => $additional2,
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer();

    $response = putJson(route('shop.api.checkout.cart.update'), [
        'qty' => $data = [
            $cartItem1->id => rand(2, 10),
            $cartItem2->id => rand(2, 10),
        ],
    ])
        ->assertOk()
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.is_guest', $cart->is_guest)
        ->assertJsonPath('data.customer_id', $cart->customer_id)
        ->assertJsonPath('data.items_count', 2)
        ->assertJsonPath('data.items_qty', array_sum($data))
        ->assertJsonPath('message', trans('shop::app.checkout.cart.index.quantity-update'));

    $cart->refresh();

    $cartItem1->refresh();

    $cartItem2->refresh();

    $response->assertJsonPath('data.formatted_discount_amount', core()->currency($cart->discount_amount));

    $this->assertPrice(! empty($cart->tax_total) ? $cart->tax_total : 0, $response['data']['tax_total']);

    $this->assertPrice(! empty($cart->discount_amount) ? $cart->discount_amount : 0, $response['data']['discount_amount']);

    $this->assertPrice($cart->grand_total, $response['data']['grand_total']);

    $this->assertPrice($cart->sub_total, $response['data']['sub_total']);

    $this->assertModelWise([
        CartItem::class => [
            $this->prepareCartItem($cartItem1),

            $this->prepareCartItem($cartItem2),
        ],
    ]);

    foreach ($cart->items as $cartItem) {
        $this->assertModelWise([
            CartItem::class => [
                $this->prepareCartItem($cartItem),
            ],
        ]);
    }
});

it('should fails the validation error when the product id not provided when add a simple product to the cart', function () {
    // Arrange.
    (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.store', [
        'quantity' => rand(1, 10),
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a simple product to the cart for guest user', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => $quantity = rand(1, 10),
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.is_guest', 1)
        ->assertJsonPath('data.customer_id', null)
        ->assertJsonPath('data.items_qty', $quantity)
        ->assertJsonPath('data.tax_total', 0)
        ->assertJsonPath('data.discount_amount', 0)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.items.0.quantity', $quantity)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'));

    $this->assertPrice($product->price, $response['data']['items'][0]['price']);

    $this->assertPrice($product->price * $quantity, $response['data']['grand_total']);

    $this->assertPrice($product->price * $quantity, $response['data']['sub_total']);
});

it('should add a simple product to the cart for customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    // Act and Assert.
    $customer = $this->loginAsCustomer();

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => $quantity = rand(1, 10),
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.is_guest', 0)
        ->assertJsonPath('data.customer_id', $customer->id)
        ->assertJsonPath('data.items_qty', $quantity)
        ->assertJsonPath('data.tax_total', 0)
        ->assertJsonPath('data.discount_amount', 0)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.items.0.quantity', $quantity)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'));

    $this->assertPrice($product->price, $response['data']['items'][0]['price']);

    $this->assertPrice($product->price * $quantity, $response['data']['grand_total']);

    $this->assertPrice($product->price * $quantity, $response['data']['sub_total']);
});

it('should fails the validation error when the product id not provided add a bundle product to the cart', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.store', [
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a bundle product to the cart for guest user', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.quantity', 1)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.is_guest', 1)
        ->assertJsonPath('data.customer_id', null)
        ->assertJsonPath('data.tax_total', 0)
        ->assertJsonPath('data.discount_amount', 0)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'));

    $this->assertPrice($grandTotal, $response['data']['grand_total']);

    $this->assertPrice($grandTotal, $response['data']['sub_total']);
});

it('should add a bundle product to the cart for customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
        ],
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert.
    $customer = $this->loginAsCustomer();

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.quantity', 1)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.is_guest', 0)
        ->assertJsonPath('data.customer_id', $customer->id)
        ->assertJsonPath('data.tax_total', 0)
        ->assertJsonPath('data.discount_amount', 0)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'));

    $this->assertPrice($grandTotal, $response['data']['grand_total']);

    $this->assertPrice($grandTotal, $response['data']['sub_total']);
});

it('should fails the validation when the product id not provided when add a configurable product to the cart', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 2000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a configurable product to the cart for guest user', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 2000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.quantity', 1)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.is_guest', 1)
        ->assertJsonPath('data.discount_amount', 0)
        ->assertJsonPath('data.tax_total', 0)
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('data.customer_id', null)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'));

    $this->assertPrice($childProduct->price, $response['data']['grand_total']);

    $this->assertPrice($childProduct->price, $response['data']['sub_total']);
});

it('should add a configurable product to the cart for customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 2000),
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert.
    $customer = $this->loginAsCustomer();

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.quantity', 1)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.is_guest', 0)
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('data.customer_id', $customer->id)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.tax_total', 0)
        ->assertJsonPath('data.discount_amount', 0);

    $this->assertPrice($childProduct->price, $response['data']['grand_total']);

    $this->assertPrice($childProduct->price, $response['data']['sub_total']);
});

it('should fails the validation error when the product id not provided when add a downloadable product to the cart', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getDownloadableProductFactory()->create();

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.store', [
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'links'      => $product->downloadable_links()->pluck('id')->toArray(),
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a downloadable product to the cart for guest user', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getDownloadableProductFactory()->create();

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'links'      => $product->downloadable_links()->pluck('id')->toArray(),
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.quantity', 1)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.is_guest', 1)
        ->assertJsonPath('data.have_stockable_items', false)
        ->assertJsonPath('data.customer_id', null)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.tax_total', 0)
        ->assertJsonPath('data.discount_amount', 0);

    $this->assertPrice($product->price, $response['data']['items'][0]['price']);

    $this->assertPrice($product->price, $response['data']['grand_total']);
});

it('should add a downloadable product to the cart for customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
        ],
    ]))->getDownloadableProductFactory()->create();

    // Act and Assert.
    $customer = $this->loginAsCustomer();

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'links'      => $product->downloadable_links()->pluck('id')->toArray(),
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.quantity', 1)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.is_guest', 0)
        ->assertJsonPath('data.have_stockable_items', false)
        ->assertJsonPath('data.customer_id', $customer->id)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.tax_total', 0)
        ->assertJsonPath('data.discount_amount', 0);

    $this->assertPrice($product->price, $response['data']['items'][0]['price']);

    $this->assertPrice($product->price, $response['data']['grand_total']);
});

it('should fails the validation error when the product id not provided when add a grouped product to the cart', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities'  => [],
        'prices'      => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.store'), [
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a grouped product to the cart for guest user', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities'  => [],
        'prices'      => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']))
        ->assertJsonPath('data.items_count', 4)
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('data.is_guest', 1)
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('data.customer_id', null)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.tax_total', 0)
        ->assertJsonPath('data.discount_amount', 0);

    foreach ($groupedProducts as $key => $groupedProduct) {
        $response->assertJsonPath('data.items.'.$key.'.quantity', $groupedProduct->qty)
            ->assertJsonPath('data.items.'.$key.'.type', $groupedProduct->associated_product->type)
            ->assertJsonPath('data.items.'.$key.'.name', $groupedProduct->associated_product->name);
    }

    $this->assertEquals(round(array_sum($data['prices']), 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round(array_sum($data['prices']), 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should add a grouped product to the cart for customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities'  => [],
        'prices'      => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    // Act and Assert.
    $customer = $this->loginAsCustomer();

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']))
        ->assertJsonPath('data.items_count', 4)
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('data.is_guest', 0)
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('data.customer_id', $customer->id)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.tax_total', 0)
        ->assertJsonPath('data.discount_amount', 0);

    foreach ($groupedProducts as $key => $groupedProduct) {
        $response->assertJsonPath('data.items.'.$key.'.quantity', $groupedProduct->qty)
            ->assertJsonPath('data.items.'.$key.'.type', $groupedProduct->associated_product->type)
            ->assertJsonPath('data.items.'.$key.'.name', $groupedProduct->associated_product->name);
    }

    $this->assertEquals(round(array_sum($data['prices']), 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round(array_sum($data['prices']), 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the product id not provided when add a virtual product to the cart', function () {
    // Arrange.
    (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getVirtualProductFactory()->create();

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.store', [
        'quantity' => rand(1, 10),
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a virtual product to the cart for guest user', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getVirtualProductFactory()->create();

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => $quantity = rand(1, 10),
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', $quantity)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.quantity', $quantity)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('data.is_guest', 1)
        ->assertJsonPath('data.have_stockable_items', false)
        ->assertJsonPath('data.customer_id', null)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.tax_total', 0)
        ->assertJsonPath('data.discount_amount', 0);

    $this->assertPrice($product->price, $response['data']['items'][0]['price']);

    $this->assertPrice($product->price * $quantity, $response['data']['grand_total']);
});

it('should add a virtual product to the cart for customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
        ],
    ]))->getVirtualProductFactory()->create();

    // Act and Assert.
    $customer = $this->loginAsCustomer();

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => $quantity = rand(1, 10),
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', $quantity)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.quantity', $quantity)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('data.is_guest', 0)
        ->assertJsonPath('data.have_stockable_items', false)
        ->assertJsonPath('data.customer_id', $customer->id)
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.tax_total', 0)
        ->assertJsonPath('data.discount_amount', 0);

    $this->assertPrice($product->price, $response['data']['items'][0]['price']);

    $this->assertPrice($product->price * $quantity, $response['data']['grand_total']);
});

it('should check including tax rate when add a product to the cart based on shipping address', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => 100,
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    $taxRate = TaxRate::factory()->create([
        'country' => 'IN',
        'state'   => fake()->randomElement(['UP', 'DL', 'HR', 'PB', 'RJ']),
    ]);

    $taxCategory = TaxCategory::factory()->create();

    TaxMap::factory()->create([
        'tax_category_id' => $taxCategory->id,
        'tax_rate_id'     => $taxRate->id,
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.taxes.categories.shipping',
        'value' => $taxCategory->id,
    ])->create([
        'code'  => 'sales.taxes.categories.product',
        'value' => $taxCategory->id,
    ])->create([
        'code'  => 'sales.taxes.calculation.based_on',
        'value' => 'shipping_address',
    ])->create([
        'code'  => 'sales.taxes.calculation.product_prices',
        'value' => 'including_tax',
    ])->create([
        'code'  => 'sales.taxes.calculation.shipping_prices',
        'value' => 'including_tax',
    ]);

    $cart = cart()->addProduct($product, [
        'product_id' => $product->id,
        'quantity'   => 1,
    ]);

    $inclTax = $product->price - ($product->price / (1 + ($taxRate->tax_rate / 100)));

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.estimate_shipping'), [
        'country'  => $taxRate->country,
        'state'    => $taxRate->state,
        'postcode' => fake()->postcode(),
    ])
        ->assertOk()
        ->assertJsonPath('data.cart.id', $cart->id)
        ->assertJsonPath('data.cart.formatted_tax_total', core()->formatPrice($inclTax))
        ->assertJsonPath('data.cart.formatted_sub_total_incl_tax', core()->formatPrice($product->price))
        ->assertJsonPath('data.cart.formatted_grand_total', core()->formatPrice($product->price))
        ->assertJsonPath('data.cart.formatted_sub_total', core()->formatPrice($product->price - $inclTax))
        ->assertJsonPath('data.cart.items.0.id', $cart->items->first()->id)
        ->assertJsonPath('data.cart.items.0.quantity', 1)
        ->assertJsonPath('data.cart.items.0.type', $product->type);

    $this->assertPrice($inclTax, $response->json('data.cart.tax_total'));

    $this->assertPrice($product->price, $response->json('data.cart.sub_total_incl_tax'));

    $this->assertPrice($product->price - $inclTax, $response->json('data.cart.sub_total'));

    $this->assertPrice($product->price, $response->json('data.cart.grand_total'));

    $this->assertPrice($product->price, $response->json('data.cart.items.0.price_incl_tax'));

    $this->assertPrice($product->price - $inclTax, $response->json('data.cart.items.0.price'));
});

it('should check including tax rate when add a product to the cart based on billing address', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => 100,
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    $taxRate = TaxRate::factory()->create([
        'country' => 'IN',
        'state'   => fake()->randomElement(['UP', 'DL', 'HR', 'PB', 'RJ']),
    ]);

    $taxCategory = TaxCategory::factory()->create();

    TaxMap::factory()->create([
        'tax_category_id' => $taxCategory->id,
        'tax_rate_id'     => $taxRate->id,
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.taxes.categories.shipping',
        'value' => $taxCategory->id,
    ])->create([
        'code'  => 'sales.taxes.categories.product',
        'value' => $taxCategory->id,
    ])->create([
        'code'  => 'sales.taxes.calculation.based_on',
        'value' => 'billing_address',
    ])->create([
        'code'  => 'sales.taxes.calculation.product_prices',
        'value' => 'including_tax',
    ])->create([
        'code'  => 'sales.taxes.calculation.shipping_prices',
        'value' => 'including_tax',
    ]);

    $cart = cart()->addProduct($product, [
        'product_id' => $product->id,
        'quantity'   => 1,
    ]);

    CartAddress::factory()->create([
        'cart_id'          => $cart->id,
        'country'          => $taxRate->country,
        'state'            => $taxRate->state,
        'address_type'     => CartAddress::ADDRESS_TYPE_BILLING,
        'use_for_shipping' => true,
    ]);

    CartAddress::factory()->create([
        'cart_id'          => $cart->id,
        'country'          => $taxRate->country,
        'state'            => $taxRate->state,
        'address_type'     => CartAddress::ADDRESS_TYPE_BILLING,
        'use_for_shipping' => true,
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    $inclTax = $product->price - ($product->price / (1 + ($taxRate->tax_rate / 100)));

    // Act and Assert.
    $response = getJson(route('shop.checkout.onepage.summary'))
        ->assertOk()
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.formatted_tax_total', core()->formatPrice($inclTax))
        ->assertJsonPath('data.formatted_sub_total_incl_tax', core()->formatPrice($product->price))
        ->assertJsonPath('data.formatted_grand_total', core()->formatPrice($product->price))
        ->assertJsonPath('data.formatted_sub_total', core()->formatPrice($product->price - $inclTax))
        ->assertJsonPath('data.items.0.id', $cart->items->first()->id)
        ->assertJsonPath('data.items.0.quantity', 1)
        ->assertJsonPath('data.items.0.type', $product->type);

    $this->assertPrice($inclTax, $response->json('data.tax_total'));

    $this->assertPrice($product->price, $response->json('data.sub_total_incl_tax'));

    $this->assertPrice($product->price - $inclTax, $response->json('data.sub_total'));

    $this->assertPrice($product->price, $response->json('data.grand_total'));

    $this->assertPrice($product->price, $response->json('data.items.0.price_incl_tax'));

    $this->assertPrice($product->price - $inclTax, $response->json('data.items.0.price'));
});

it('should check including tax rate when add a product to the cart based on shipping origin', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => 100,
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    $taxRate = TaxRate::factory()->create([
        'country' => 'IN',
        'state'   => fake()->randomElement(['UP', 'DL', 'HR', 'PB', 'RJ']),
    ]);

    $taxCategory = TaxCategory::factory()->create();

    TaxMap::factory()->create([
        'tax_category_id' => $taxCategory->id,
        'tax_rate_id'     => $taxRate->id,
    ]);

    CoreConfig::factory()->create([
        'code'         => 'sales.shipping.origin.country',
        'value'        => $taxRate->country,
        'channel_code' => 'default',
        'locale_code'  => 'en',
    ])->create([
        'code'         => 'sales.shipping.origin.state',
        'value'        => $taxRate->state,
        'channel_code' => 'default',
        'locale_code'  => 'en',
    ])->create([
        'code'         => 'sales.shipping.origin.city',
        'value'        => fake()->city(),
        'channel_code' => 'default',
    ])->create([
        'code'         => 'sales.shipping.origin.address',
        'value'        => fake()->address(),
        'channel_code' => 'default',
    ])->create([
        'code'         => 'sales.shipping.origin.store_name',
        'value'        => 'DEMO STORE',
        'channel_code' => 'default',
    ])->create([
        'code'         => 'sales.shipping.origin.contact',
        'value'        => '1234567890',
        'channel_code' => 'default',
    ])->create([
        'code'         => 'sales.shipping.origin.bank_details',
        'value'        => 'TEST BANK',
        'channel_code' => 'default',
    ])->create([
        'code'         => 'sales.shipping.origin.zipcode',
        'value'        => '123456',
        'channel_code' => 'default',
    ])->create([
        'code'         => 'sales.taxes.categories.shipping',
        'value'        => $taxCategory->id,
    ])->create([
        'code'         => 'sales.taxes.categories.product',
        'value'        => $taxCategory->id,
    ])->create([
        'code'         => 'sales.taxes.calculation.based_on',
        'value'        => 'shipping_origin',
    ])->create([
        'code'         => 'sales.taxes.calculation.product_prices',
        'value'        => 'including_tax',
    ])->create([
        'code'         => 'sales.taxes.calculation.shipping_prices',
        'value'        => 'including_tax',
    ]);

    $cart = cart()->addProduct($product, [
        'product_id' => $product->id,
        'quantity'   => 1,
    ]);

    $inclTax = $product->price - ($product->price / (1 + ($taxRate->tax_rate / 100)));

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.estimate_shipping'), [
        'country'  => $taxRate->country,
        'state'    => $taxRate->state,
        'postcode' => '123456',
    ]);

    $response->assertOk()
        ->assertJsonPath('data.cart.id', $cart->id)
        ->assertJsonPath('data.cart.formatted_tax_total', core()->formatPrice($inclTax))
        ->assertJsonPath('data.cart.formatted_sub_total_incl_tax', core()->formatPrice($product->price))
        ->assertJsonPath('data.cart.formatted_grand_total', core()->formatPrice($product->price))
        ->assertJsonPath('data.cart.formatted_sub_total', core()->formatPrice($product->price - $inclTax))
        ->assertJsonPath('data.cart.items.0.id', $cart->items->first()->id)
        ->assertJsonPath('data.cart.items.0.quantity', 1)
        ->assertJsonPath('data.cart.items.0.type', $product->type);

    $this->assertPrice($inclTax, $response->json('data.cart.tax_total'));

    $this->assertPrice($product->price, $response->json('data.cart.sub_total_incl_tax'));

    $this->assertPrice($product->price - $inclTax, $response->json('data.cart.sub_total'));

    $this->assertPrice($product->price, $response->json('data.cart.grand_total'));

    $this->assertPrice($product->price, $response->json('data.cart.items.0.price_incl_tax'));

    $this->assertPrice($product->price - $inclTax, $response->json('data.cart.items.0.price'));
});

it('should check excluding tax rate when add a product to the cart based on billing address', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => 100,
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    $taxRate = TaxRate::factory()->create([
        'country' => 'IN',
        'state'   => fake()->randomElement(['UP', 'DL', 'HR', 'PB', 'RJ']),
    ]);

    $taxCategory = TaxCategory::factory()->create();

    TaxMap::factory()->create([
        'tax_category_id' => $taxCategory->id,
        'tax_rate_id'     => $taxRate->id,
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.taxes.categories.shipping',
        'value' => $taxCategory->id,
    ])->create([
        'code'  => 'sales.taxes.categories.product',
        'value' => $taxCategory->id,
    ])->create([
        'code'  => 'sales.taxes.calculation.based_on',
        'value' => 'billing_address',
    ])->create([
        'code'  => 'sales.taxes.calculation.product_prices',
        'value' => 'excluding_tax',
    ])->create([
        'code'  => 'sales.taxes.calculation.shipping_prices',
        'value' => 'excluding_tax',
    ]);

    $cart = cart()->addProduct($product, [
        'product_id' => $product->id,
        'quantity'   => 1,
    ]);

    CartAddress::factory()->create([
        'cart_id'          => $cart->id,
        'country'          => $taxRate->country,
        'state'            => $taxRate->state,
        'address_type'     => CartAddress::ADDRESS_TYPE_BILLING,
        'use_for_shipping' => true,
    ]);

    CartAddress::factory()->create([
        'cart_id'          => $cart->id,
        'country'          => $taxRate->country,
        'state'            => $taxRate->state,
        'address_type'     => CartAddress::ADDRESS_TYPE_BILLING,
        'use_for_shipping' => true,
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    $exclTax = ($taxRate->tax_rate / 100) * $product->price;

    // Act and Assert.
    $response = getJson(route('shop.checkout.onepage.summary'))
        ->assertOk()
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.formatted_tax_total', core()->formatPrice($exclTax))
        ->assertJsonPath('data.formatted_sub_total_incl_tax', core()->formatPrice($product->price + $exclTax))
        ->assertJsonPath('data.formatted_grand_total', core()->formatPrice($product->price + $exclTax))
        ->assertJsonPath('data.formatted_sub_total', core()->formatPrice($product->price))
        ->assertJsonPath('data.items.0.id', $cart->items->first()->id)
        ->assertJsonPath('data.items.0.quantity', 1)
        ->assertJsonPath('data.items.0.type', $product->type);

    $this->assertPrice($exclTax, $response->json('data.tax_total'));

    $this->assertPrice($product->price + $exclTax, $response->json('data.sub_total_incl_tax'));

    $this->assertPrice($product->price, $response->json('data.sub_total'));

    $this->assertPrice($product->price + $exclTax, $response->json('data.grand_total'));

    $this->assertPrice($product->price + $exclTax, $response->json('data.items.0.price_incl_tax'));

    $this->assertPrice($product->price, $response->json('data.items.0.price'));
});

it('should check excluding tax rate when add a product to the cart based on shipping address', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => 100,
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    $taxRate = TaxRate::factory()->create([
        'country' => 'IN',
        'state'   => fake()->randomElement(['UP', 'DL', 'HR', 'PB', 'RJ']),
    ]);

    $taxCategory = TaxCategory::factory()->create();

    TaxMap::factory()->create([
        'tax_category_id' => $taxCategory->id,
        'tax_rate_id'     => $taxRate->id,
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.taxes.categories.shipping',
        'value' => $taxCategory->id,
    ])->create([
        'code'  => 'sales.taxes.categories.product',
        'value' => $taxCategory->id,
    ])->create([
        'code'  => 'sales.taxes.calculation.based_on',
        'value' => 'shipping_address',
    ])->create([
        'code'  => 'sales.taxes.calculation.product_prices',
        'value' => 'excluding_tax',
    ])->create([
        'code'  => 'sales.taxes.calculation.shipping_prices',
        'value' => 'excluding_tax',
    ]);

    $cart = cart()->addProduct($product, [
        'product_id' => $product->id,
        'quantity'   => 1,
    ]);

    $exclTax = ($taxRate->tax_rate / 100) * $product->price;

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.estimate_shipping'), [
        'country'  => $taxRate->country,
        'state'    => $taxRate->state,
        'postcode' => fake()->postcode(),
    ])
        ->assertOk()
        ->assertJsonPath('data.cart.id', $cart->id)
        ->assertJsonPath('data.cart.formatted_tax_total', core()->formatPrice($exclTax))
        ->assertJsonPath('data.cart.formatted_sub_total_incl_tax', core()->formatPrice($product->price + $exclTax))
        ->assertJsonPath('data.cart.formatted_grand_total', core()->formatPrice($product->price + $exclTax))
        ->assertJsonPath('data.cart.formatted_sub_total', core()->formatPrice($product->price))
        ->assertJsonPath('data.cart.items.0.id', $cart->items->first()->id)
        ->assertJsonPath('data.cart.items.0.quantity', 1)
        ->assertJsonPath('data.cart.items.0.type', $product->type);

    $this->assertPrice($exclTax, $response->json('data.cart.tax_total'));

    $this->assertPrice($product->price + $exclTax, $response->json('data.cart.sub_total_incl_tax'));

    $this->assertPrice($product->price, $response->json('data.cart.sub_total'));

    $this->assertPrice($product->price + $exclTax, $response->json('data.cart.grand_total'));

    $this->assertPrice($product->price + $exclTax, $response->json('data.cart.items.0.price_incl_tax'));

    $this->assertPrice($product->price, $response->json('data.cart.items.0.price'));
});

it('should check excluding tax rate when add a product to the cart based on shipping origin', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => 100,
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    $taxRate = TaxRate::factory()->create([
        'country' => 'IN',
        'state'   => fake()->randomElement(['UP', 'DL', 'HR', 'PB', 'RJ']),
    ]);

    $taxCategory = TaxCategory::factory()->create();

    TaxMap::factory()->create([
        'tax_category_id' => $taxCategory->id,
        'tax_rate_id'     => $taxRate->id,
    ]);

    CoreConfig::factory()->create([
        'code'         => 'sales.shipping.origin.country',
        'value'        => $taxRate->country,
        'channel_code' => 'default',
        'locale_code'  => 'en',
    ])->create([
        'code'         => 'sales.shipping.origin.state',
        'value'        => $taxRate->state,
        'channel_code' => 'default',
        'locale_code'  => 'en',
    ])->create([
        'code'         => 'sales.shipping.origin.city',
        'value'        => fake()->city(),
        'channel_code' => 'default',
    ])->create([
        'code'         => 'sales.shipping.origin.address',
        'value'        => fake()->address(),
        'channel_code' => 'default',
    ])->create([
        'code'         => 'sales.shipping.origin.store_name',
        'value'        => 'DEMO STORE',
        'channel_code' => 'default',
    ])->create([
        'code'         => 'sales.shipping.origin.contact',
        'value'        => '1234567890',
        'channel_code' => 'default',
    ])->create([
        'code'         => 'sales.shipping.origin.bank_details',
        'value'        => 'TEST BANK',
        'channel_code' => 'default',
    ])->create([
        'code'         => 'sales.shipping.origin.zipcode',
        'value'        => '123456',
        'channel_code' => 'default',
    ])->create([
        'code'         => 'sales.taxes.categories.shipping',
        'value'        => $taxCategory->id,
    ])->create([
        'code'         => 'sales.taxes.categories.product',
        'value'        => $taxCategory->id,
    ])->create([
        'code'         => 'sales.taxes.calculation.based_on',
        'value'        => 'shipping_origin',
    ])->create([
        'code'         => 'sales.taxes.calculation.product_prices',
        'value'        => 'excluding_tax',
    ])->create([
        'code'         => 'sales.taxes.calculation.shipping_prices',
        'value'        => 'excluding_tax',
    ]);

    $cart = cart()->addProduct($product, [
        'product_id' => $product->id,
        'quantity'   => 1,
    ]);

    $exclTax = ($taxRate->tax_rate / 100) * $product->price;

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.estimate_shipping'), [
        'country'  => $taxRate->country,
        'state'    => $taxRate->state,
        'postcode' => '123456',
    ])
        ->assertOk()
        ->assertJsonPath('data.cart.id', $cart->id)
        ->assertJsonPath('data.cart.formatted_tax_total', core()->formatPrice($exclTax))
        ->assertJsonPath('data.cart.formatted_sub_total_incl_tax', core()->formatPrice($product->price + $exclTax))
        ->assertJsonPath('data.cart.formatted_grand_total', core()->formatPrice($product->price + $exclTax))
        ->assertJsonPath('data.cart.formatted_sub_total', core()->formatPrice($product->price))
        ->assertJsonPath('data.cart.items.0.id', $cart->items->first()->id)
        ->assertJsonPath('data.cart.items.0.quantity', 1)
        ->assertJsonPath('data.cart.items.0.type', $product->type);

    $this->assertPrice($exclTax, $response->json('data.cart.tax_total'));

    $this->assertPrice($product->price + $exclTax, $response->json('data.cart.sub_total_incl_tax'));

    $this->assertPrice($product->price, $response->json('data.cart.sub_total'));

    $this->assertPrice($product->price + $exclTax, $response->json('data.cart.grand_total'));

    $this->assertPrice($product->price + $exclTax, $response->json('data.cart.items.0.price_incl_tax'));

    $this->assertPrice($product->price, $response->json('data.cart.items.0.price'));
});
