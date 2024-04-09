<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Webkul\Admin\Mail\Order\CreatedNotification as AdminOrderCreatedNotification;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartItem;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\ProductInventoryIndex;
use Webkul\Product\Models\ProductOrderedInventory;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Shop\Mail\Order\CreatedNotification as ShopOrderCreatedNotification;

use function Pest\Laravel\postJson;

it('should handle certain validation errors when storing the guest user address for cart billing and shipping', function () {
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

    // Act and Assert.
    postJson(route('shop.checkout.onepage.addresses.store'), [
        'shipping' => [],
        'billing'  => [],
    ])
        ->assertJsonValidationErrorFor('billing.first_name')
        ->assertJsonValidationErrorFor('billing.last_name')
        ->assertJsonValidationErrorFor('billing.email')
        ->assertJsonValidationErrorFor('billing.address')
        ->assertJsonValidationErrorFor('billing.city')
        ->assertJsonValidationErrorFor('billing.phone')
        ->assertJsonValidationErrorFor('shipping.first_name')
        ->assertJsonValidationErrorFor('shipping.last_name')
        ->assertJsonValidationErrorFor('shipping.email')
        ->assertJsonValidationErrorFor('shipping.address')
        ->assertJsonValidationErrorFor('shipping.city')
        ->assertJsonValidationErrorFor('shipping.phone')
        ->assertUnprocessable();
});

it('should handle certain validation errors when storing the customer address for cart billing and shipping', function () {
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

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'shipping' => [],
        'billing'  => [],
    ])
        ->assertJsonValidationErrorFor('billing.first_name')
        ->assertJsonValidationErrorFor('billing.last_name')
        ->assertJsonValidationErrorFor('billing.email')
        ->assertJsonValidationErrorFor('billing.address')
        ->assertJsonValidationErrorFor('billing.city')
        ->assertJsonValidationErrorFor('billing.phone')
        ->assertJsonValidationErrorFor('shipping.first_name')
        ->assertJsonValidationErrorFor('shipping.last_name')
        ->assertJsonValidationErrorFor('shipping.email')
        ->assertJsonValidationErrorFor('shipping.address')
        ->assertJsonValidationErrorFor('shipping.city')
        ->assertJsonValidationErrorFor('shipping.phone')
        ->assertUnprocessable();
});

it('should store the shipping address as the billing address when use_for_shipping key is set to true in billing for guest user', function () {
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

    // Act and Assert.
    $response = postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => $billingAddress = [
            ...CustomerAddress::factory()->create()->toArray(),
            'use_for_shipping' => 1,
            'address'          => [fake()->address()],
        ],
    ])
        ->assertOk()
        ->assertJsonPath('redirect', false)
        ->assertJsonPath('data.shippingMethods.flatrate.carrier_title', 'Flat Rate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.carrier', 'flatrate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.carrier_title', 'Flat Rate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.method', 'flatrate_flatrate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.method_title', 'Flat Rate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.price', 10)
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.base_price', 10)
        ->assertJsonPath('data.shippingMethods.free.carrier_title', 'Free Shipping')
        ->assertJsonPath('data.shippingMethods.free.rates.0.carrier', 'free')
        ->assertJsonPath('data.shippingMethods.free.rates.0.carrier_title', 'Free Shipping')
        ->assertJsonPath('data.shippingMethods.free.carrier_title', 'Free Shipping')
        ->assertJsonPath('data.shippingMethods.free.rates.0.method', 'free_free')
        ->assertJsonPath('data.shippingMethods.free.rates.0.method_title', 'Free Shipping')
        ->assertJsonPath('data.shippingMethods.free.rates.0.price', 0)
        ->assertJsonPath('data.shippingMethods.free.rates.0.base_price', 0);

    $response->assertJsonPath('data.shippingMethods.flatrate.rates.0.cart_address_id', $cart->shipping_address->id);

    $response->assertJsonPath('data.shippingMethods.free.rates.0.cart_address_id', $cart->shipping_address->id);

    $this->assertModelWise([
        CartAddress::class => [
            [
                'address'          => implode("\n", $billingAddress['address']),
                'address_type'     => CartAddress::ADDRESS_TYPE_BILLING,
                'cart_id'          => $cart->id,
                'use_for_shipping' => $billingAddress['use_for_shipping'],
                ...Arr::only($billingAddress, ['first_name', 'last_name', 'company_name', 'city', 'state', 'country', 'email', 'postcode', 'phone']),
            ],
        ],

        CartAddress::class => [
            [
                'address'      => implode("\n", $billingAddress['address']),
                'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
                'cart_id'      => $cart->id,
                ...Arr::only($billingAddress, ['first_name', 'last_name', 'company_name', 'city', 'state', 'country', 'email', 'postcode', 'phone']),
            ],
        ],
    ]);
});

it('should store the billing and shipping address for guest user', function () {
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

    // Act and Assert.
    $response = postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => $billingAddress = [
            ...CustomerAddress::factory()->create()->toArray(),
            'address'          => [fake()->address()],
            'use_for_shipping' => 0,
        ],

        'shipping' => $shippingAddress = [
            ...CustomerAddress::factory()->create()->toArray(),
            'address' => [fake()->address()],
        ],
    ])
        ->assertOk()
        ->assertJsonPath('redirect', false)
        ->assertJsonPath('data.shippingMethods.flatrate.carrier_title', 'Flat Rate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.carrier', 'flatrate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.carrier_title', 'Flat Rate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.method', 'flatrate_flatrate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.method_title', 'Flat Rate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.price', 10)
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.base_price', 10)
        ->assertJsonPath('data.shippingMethods.free.carrier_title', 'Free Shipping')
        ->assertJsonPath('data.shippingMethods.free.rates.0.carrier', 'free')
        ->assertJsonPath('data.shippingMethods.free.rates.0.carrier_title', 'Free Shipping')
        ->assertJsonPath('data.shippingMethods.free.carrier_title', 'Free Shipping')
        ->assertJsonPath('data.shippingMethods.free.rates.0.method', 'free_free')
        ->assertJsonPath('data.shippingMethods.free.rates.0.method_title', 'Free Shipping')
        ->assertJsonPath('data.shippingMethods.free.rates.0.price', 0)
        ->assertJsonPath('data.shippingMethods.free.rates.0.base_price', 0);

    $response->assertJsonPath('data.shippingMethods.flatrate.rates.0.cart_address_id', $cart->shipping_address->id);
    $response->assertJsonPath('data.shippingMethods.free.rates.0.cart_address_id', $cart->shipping_address->id);

    $this->assertModelWise([
        CartAddress::class => [
            [
                'address'          => implode("\n", $billingAddress['address']),
                'address_type'     => CartAddress::ADDRESS_TYPE_BILLING,
                'cart_id'          => $cart->id,
                'use_for_shipping' => $billingAddress['use_for_shipping'],
                ...Arr::only($billingAddress, ['first_name', 'last_name', 'company_name', 'city', 'state', 'country', 'email', 'postcode', 'phone']),
            ],
        ],

        CartAddress::class => [
            [
                'address'      => implode("\n", $shippingAddress['address']),
                'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
                'cart_id'      => $cart->id,
                ...Arr::only($shippingAddress, ['first_name', 'last_name', 'company_name', 'city', 'state', 'country', 'email', 'postcode', 'phone']),
            ],
        ],
    ]);
});

it('should store the billing address for non stockable items for guest user', function () {
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
        ->getVirtualProductFactory()
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

    // Act and Assert.
    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => $billingAddress = [
            ...CustomerAddress::factory()->create()->toArray(),
            'address'          => [fake()->address()],
            'use_for_shipping' => 1,
        ],
    ])
        ->assertOk()
        ->assertJsonPath('data.payment_methods.0.method', 'paypal_smart_button')
        ->assertJsonPath('data.payment_methods.0.method_title', 'PayPal Smart Button')
        ->assertJsonPath('data.payment_methods.0.description', 'PayPal')
        ->assertJsonPath('data.payment_methods.0.sort', 0)
        ->assertJsonPath('data.payment_methods.1.method', 'moneytransfer')
        ->assertJsonPath('data.payment_methods.1.method_title', 'Money Transfer')
        ->assertJsonPath('data.payment_methods.1.description', 'Money Transfer')
        ->assertJsonPath('data.payment_methods.1.sort', 2)
        ->assertJsonPath('data.payment_methods.2.method', 'paypal_standard')
        ->assertJsonPath('data.payment_methods.2.method_title', 'PayPal Standard')
        ->assertJsonPath('data.payment_methods.2.description', 'PayPal Standard')
        ->assertJsonPath('data.payment_methods.2.sort', 3)
        ->assertJsonPath('redirect', false);

    $this->assertModelWise([
        CartAddress::class => [
            [
                'address'          => implode("\n", $billingAddress['address']),
                'address_type'     => CartAddress::ADDRESS_TYPE_BILLING,
                'cart_id'          => $cart->id,
                'use_for_shipping' => $billingAddress['use_for_shipping'],
                ...Arr::only($billingAddress, ['first_name', 'last_name', 'company_name', 'city', 'state', 'country', 'email', 'postcode', 'phone']),
            ],
        ],
    ]);
});

it('should store the shipping address as the billing address when use_for_shipping key is set to true in billing for customer', function () {
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

    $response = postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => $billingAddress = [
            ...CustomerAddress::factory()->create()->toArray(),
            'address'          => [fake()->address()],
            'use_for_shipping' => 1,
        ],
    ])
        ->assertOk()
        ->assertJsonPath('redirect', false)
        ->assertJsonPath('data.shippingMethods.flatrate.carrier_title', 'Flat Rate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.carrier', 'flatrate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.carrier_title', 'Flat Rate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.method', 'flatrate_flatrate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.method_title', 'Flat Rate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.price', 10)
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.base_price', 10)
        ->assertJsonPath('data.shippingMethods.free.carrier_title', 'Free Shipping')
        ->assertJsonPath('data.shippingMethods.free.rates.0.carrier', 'free')
        ->assertJsonPath('data.shippingMethods.free.rates.0.carrier_title', 'Free Shipping')
        ->assertJsonPath('data.shippingMethods.free.carrier_title', 'Free Shipping')
        ->assertJsonPath('data.shippingMethods.free.rates.0.method', 'free_free')
        ->assertJsonPath('data.shippingMethods.free.rates.0.method_title', 'Free Shipping')
        ->assertJsonPath('data.shippingMethods.free.rates.0.price', 0)
        ->assertJsonPath('data.shippingMethods.free.rates.0.base_price', 0);

    $response->assertJsonPath('data.shippingMethods.flatrate.rates.0.cart_address_id', $cart->shipping_address->id);
    $response->assertJsonPath('data.shippingMethods.free.rates.0.cart_address_id', $cart->shipping_address->id);

    $this->assertModelWise([
        CartAddress::class => [
            [
                'address'          => implode("\n", $billingAddress['address']),
                'address_type'     => CartAddress::ADDRESS_TYPE_BILLING,
                'cart_id'          => $cart->id,
                'use_for_shipping' => $billingAddress['use_for_shipping'],
                ...Arr::only($billingAddress, ['first_name', 'last_name', 'company_name', 'city', 'state', 'country', 'email', 'postcode', 'phone']),
            ],
        ],

        CartAddress::class => [
            [
                'address'      => implode("\n", $billingAddress['address']),
                'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
                'cart_id'      => $cart->id,
                ...Arr::only($billingAddress, ['first_name', 'last_name', 'company_name', 'city', 'state', 'country', 'email', 'postcode', 'phone']),
            ],
        ],
    ]);
});

it('should store the billing and shipping address for customer', function () {
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

    $response = postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => $billingAddress = [
            ...CustomerAddress::factory()->create()->toArray(),
            'address'          => [fake()->address()],
            'use_for_shipping' => 0,
        ],

        'shipping' => $shippingAddress = [
            ...CustomerAddress::factory()->create()->toArray(),
            'address' => [fake()->address()],
        ],
    ])
        ->assertOk()
        ->assertJsonPath('redirect', false)
        ->assertJsonPath('data.shippingMethods.flatrate.carrier_title', 'Flat Rate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.carrier', 'flatrate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.carrier_title', 'Flat Rate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.method', 'flatrate_flatrate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.method_title', 'Flat Rate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.price', 10)
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.base_price', 10)
        ->assertJsonPath('data.shippingMethods.free.carrier_title', 'Free Shipping')
        ->assertJsonPath('data.shippingMethods.free.rates.0.carrier', 'free')
        ->assertJsonPath('data.shippingMethods.free.rates.0.carrier_title', 'Free Shipping')
        ->assertJsonPath('data.shippingMethods.free.carrier_title', 'Free Shipping')
        ->assertJsonPath('data.shippingMethods.free.rates.0.method', 'free_free')
        ->assertJsonPath('data.shippingMethods.free.rates.0.method_title', 'Free Shipping')
        ->assertJsonPath('data.shippingMethods.free.rates.0.price', 0)
        ->assertJsonPath('data.shippingMethods.free.rates.0.base_price', 0);

    $response->assertJsonPath('data.shippingMethods.flatrate.rates.0.cart_address_id', $cart->shipping_address->id);
    $response->assertJsonPath('data.shippingMethods.free.rates.0.cart_address_id', $cart->shipping_address->id);

    $this->assertModelWise([
        CartAddress::class => [
            [
                'address'          => implode("\n", $billingAddress['address']),
                'address_type'     => CartAddress::ADDRESS_TYPE_BILLING,
                'cart_id'          => $cart->id,
                'use_for_shipping' => $billingAddress['use_for_shipping'],
                ...Arr::only($billingAddress, ['first_name', 'last_name', 'company_name', 'city', 'state', 'country', 'email', 'postcode', 'phone']),
            ],
        ],

        CartAddress::class => [
            [
                'address'      => implode("\n", $shippingAddress['address']),
                'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
                'cart_id'      => $cart->id,
                ...Arr::only($shippingAddress, ['first_name', 'last_name', 'company_name', 'city', 'state', 'country', 'email', 'postcode', 'phone']),
            ],
        ],
    ]);
});

it('should store the billing address for non stockable items for customer', function () {
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
        ->getVirtualProductFactory()
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

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => $billingAddress = [
            ...CustomerAddress::factory()->create()->toArray(),
            'address'          => [fake()->address()],
            'use_for_shipping' => 1,
        ],
    ])
        ->assertOk()
        ->assertJsonPath('data.payment_methods.0.method', 'paypal_smart_button')
        ->assertJsonPath('data.payment_methods.0.method_title', 'PayPal Smart Button')
        ->assertJsonPath('data.payment_methods.0.description', 'PayPal')
        ->assertJsonPath('data.payment_methods.0.sort', 0)
        ->assertJsonPath('data.payment_methods.1.method', 'moneytransfer')
        ->assertJsonPath('data.payment_methods.1.method_title', 'Money Transfer')
        ->assertJsonPath('data.payment_methods.1.description', 'Money Transfer')
        ->assertJsonPath('data.payment_methods.1.sort', 2)
        ->assertJsonPath('data.payment_methods.2.method', 'paypal_standard')
        ->assertJsonPath('data.payment_methods.2.method_title', 'PayPal Standard')
        ->assertJsonPath('data.payment_methods.2.description', 'PayPal Standard')
        ->assertJsonPath('data.payment_methods.2.sort', 3)
        ->assertJsonPath('redirect', false);

    $this->assertModelWise([
        CartAddress::class => [
            [
                'address'          => implode("\n", $billingAddress['address']),
                'address_type'     => CartAddress::ADDRESS_TYPE_BILLING,
                'cart_id'          => $cart->id,
                'use_for_shipping' => $billingAddress['use_for_shipping'],
                ...Arr::only($billingAddress, ['first_name', 'last_name', 'company_name', 'city', 'state', 'country', 'email', 'postcode', 'phone']),
            ],
        ],
    ]);
});

it('should fails the certain validation errors when use for shipping is set to false in billing address and shipping address not provided for customer', function () {
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

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            ...CustomerAddress::factory()->create()->toArray(),
            'address'          => [fake()->address()],
            'use_for_shipping' => false,
        ],
    ])
        ->assertJsonValidationErrorFor('shipping.first_name')
        ->assertJsonValidationErrorFor('shipping.last_name')
        ->assertJsonValidationErrorFor('shipping.email')
        ->assertJsonValidationErrorFor('shipping.address')
        ->assertJsonValidationErrorFor('shipping.city')
        ->assertJsonValidationErrorFor('shipping.phone')
        ->assertUnprocessable();
});

it('should fails the certain validation errors when use for shipping is set to false in billing address and shipping address not provided for guest user', function () {
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

    // Act and Assert.
    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            ...CustomerAddress::factory()->create()->toArray(),
            'address'          => [fake()->address()],
            'use_for_shipping' => false,
        ],
    ])
        ->assertJsonValidationErrorFor('shipping.first_name')
        ->assertJsonValidationErrorFor('shipping.last_name')
        ->assertJsonValidationErrorFor('shipping.email')
        ->assertJsonValidationErrorFor('shipping.address')
        ->assertJsonValidationErrorFor('shipping.city')
        ->assertJsonValidationErrorFor('shipping.phone')
        ->assertUnprocessable();
});

it('should fails the validation error when shipping method not providing when store the shipping method for the guest user', function () {
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

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    cart()->setCart($cart);

    // Act and Assert.
    postJson(route('shop.checkout.onepage.shipping_methods.store'))
        ->assertJsonValidationErrorFor('shipping_method')
        ->assertUnprocessable();
});

it('should fails the validation error when shipping method not providing when store the shipping method for the customer', function () {
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

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    cart()->setCart($cart);

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.shipping_methods.store'))
        ->assertJsonValidationErrorFor('shipping_method')
        ->assertUnprocessable();
});

it('should store the shipping method for guest user', function () {
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

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    cart()->setCart($cart);

    // Act and Assert.
    postJson(route('shop.checkout.onepage.shipping_methods.store'), [
        'shipping_method' => 'free_free',
    ])
        ->assertOk()
        ->assertJsonPath('payment_methods.0.method', 'paypal_smart_button')
        ->assertJsonPath('payment_methods.0.method_title', 'PayPal Smart Button')
        ->assertJsonPath('payment_methods.0.description', 'PayPal')
        ->assertJsonPath('payment_methods.0.sort', 0)
        ->assertJsonPath('payment_methods.1.method', 'cashondelivery')
        ->assertJsonPath('payment_methods.1.method_title', 'Cash On Delivery')
        ->assertJsonPath('payment_methods.1.description', 'Cash On Delivery')
        ->assertJsonPath('payment_methods.1.sort', 1)
        ->assertJsonPath('payment_methods.2.method', 'moneytransfer')
        ->assertJsonPath('payment_methods.2.method_title', 'Money Transfer')
        ->assertJsonPath('payment_methods.2.description', 'Money Transfer')
        ->assertJsonPath('payment_methods.2.sort', 2)
        ->assertJsonPath('payment_methods.3.method', 'paypal_standard')
        ->assertJsonPath('payment_methods.3.method_title', 'PayPal Standard')
        ->assertJsonPath('payment_methods.3.description', 'PayPal Standard')
        ->assertJsonPath('payment_methods.3.sort', 3);
});

it('should store the shipping method for customer', function () {
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

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    cart()->setCart($cart);

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.shipping_methods.store'), [
        'shipping_method' => 'free_free',
    ])
        ->assertOk()
        ->assertJsonPath('payment_methods.0.method', 'paypal_smart_button')
        ->assertJsonPath('payment_methods.0.method_title', 'PayPal Smart Button')
        ->assertJsonPath('payment_methods.0.description', 'PayPal')
        ->assertJsonPath('payment_methods.0.sort', 0)
        ->assertJsonPath('payment_methods.1.method', 'cashondelivery')
        ->assertJsonPath('payment_methods.1.method_title', 'Cash On Delivery')
        ->assertJsonPath('payment_methods.1.description', 'Cash On Delivery')
        ->assertJsonPath('payment_methods.1.sort', 1)
        ->assertJsonPath('payment_methods.2.method', 'moneytransfer')
        ->assertJsonPath('payment_methods.2.method_title', 'Money Transfer')
        ->assertJsonPath('payment_methods.2.description', 'Money Transfer')
        ->assertJsonPath('payment_methods.2.sort', 2)
        ->assertJsonPath('payment_methods.3.method', 'paypal_standard')
        ->assertJsonPath('payment_methods.3.method_title', 'PayPal Standard')
        ->assertJsonPath('payment_methods.3.description', 'PayPal Standard')
        ->assertJsonPath('payment_methods.3.sort', 3);
});

it('should fails the validation error when store the payment method for guest user', function () {
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

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    cart()->setCart($cart);

    // Act and Assert.
    postJson(route('shop.checkout.onepage.payment_methods.store'))
        ->assertJsonValidationErrorFor('payment')
        ->assertUnprocessable();
});

it('should store the payment method for guest user', function () {
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

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $cartShippingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $response = postJson(route('shop.checkout.onepage.payment_methods.store'), [
        'payment' => [
            'method'       => 'cashondelivery',
            'method_title' => 'Cash On Delivery',
            'description'  => 'Cash On Delivery',
            'sort'         => 1,
        ],
    ])
        ->assertJsonPath('cart.id', $cart->id)
        ->assertJsonPath('cart.is_guest', 1)
        ->assertJsonPath('cart.items_count', 1)
        ->assertJsonPath('cart.customer_id', null)
        ->assertJsonPath('cart.items_count', 1)
        ->assertJsonPath('cart.items_qty', 1)
        ->assertJsonPath('cart.base_sub_total', core()->formatBasePrice($product->price))
        ->assertJsonPath('cart.items.0.id', $cartItem->id)
        ->assertJsonPath('cart.items.0.quantity', 1)
        ->assertJsonPath('cart.items.0.type', $cartItem->type)
        ->assertJsonPath('cart.items.0.name', $cartItem->name)
        ->assertJsonPath('cart.items.0.price', $cartItem->price)
        ->assertJsonPath('cart.items.0.formatted_price', core()->formatPrice($cartItem->price))
        ->assertJsonPath('cart.items.0.formatted_total', core()->formatPrice($cartItem->total))
        ->assertJsonPath('cart.items.0.options', array_values($cartItem->additional['attributes'] ?? []))
        ->assertJsonPath('cart.items.0.base_image', $cartItem->getTypeInstance()->getBaseImage($cartItem))
        ->assertJsonPath('cart.items.0.product_url_key', $cartItem->product->url_key)
        ->assertJsonPath('cart.billing_address.id', $cartBillingAddress->id)
        ->assertJsonPath('cart.billing_address.address_type', $cartBillingAddress->address_type)
        ->assertJsonPath('cart.billing_address.parent_address_id', $cartBillingAddress->parent_address_id)
        ->assertJsonPath('cart.billing_address.customer_id', $cartBillingAddress->customer_id)
        ->assertJsonPath('cart.billing_address.cart_id', $cart->id)
        ->assertJsonPath('cart.billing_address.order_id', null)
        ->assertJsonPath('cart.billing_address.first_name', $cartBillingAddress->first_name)
        ->assertJsonPath('cart.billing_address.last_name', $cartBillingAddress->last_name)
        ->assertJsonPath('cart.billing_address.gender', $cartBillingAddress->gender)
        ->assertJsonPath('cart.billing_address.company_name', $cartBillingAddress->company_name)
        ->assertJsonPath('cart.billing_address.address', explode("\n", $cartBillingAddress->address))
        ->assertJsonPath('cart.billing_address.city', $cartBillingAddress->city)
        ->assertJsonPath('cart.billing_address.state', $cartBillingAddress->state)
        ->assertJsonPath('cart.billing_address.country', $cartBillingAddress->country)
        ->assertJsonPath('cart.billing_address.postcode', $cartBillingAddress->postcode)
        ->assertJsonPath('cart.billing_address.email', $cartBillingAddress->email)
        ->assertJsonPath('cart.billing_address.phone', $cartBillingAddress->phone)
        ->assertJsonPath('cart.billing_address.vat_id', $cartBillingAddress->vat_id)
        ->assertJsonPath('cart.shipping_address.id', $cartShippingAddress->id)
        ->assertJsonPath('cart.shipping_address.address_type', $cartShippingAddress->address_type)
        ->assertJsonPath('cart.shipping_address.parent_address_id', $cartShippingAddress->parent_address_id)
        ->assertJsonPath('cart.shipping_address.customer_id', $cartShippingAddress->customer_id)
        ->assertJsonPath('cart.shipping_address.cart_id', $cart->id)
        ->assertJsonPath('cart.shipping_address.order_id', null)
        ->assertJsonPath('cart.shipping_address.first_name', $cartShippingAddress->first_name)
        ->assertJsonPath('cart.shipping_address.last_name', $cartShippingAddress->last_name)
        ->assertJsonPath('cart.shipping_address.gender', $cartShippingAddress->gender)
        ->assertJsonPath('cart.shipping_address.company_name', $cartShippingAddress->company_name)
        ->assertJsonPath('cart.shipping_address.address', explode("\n", $cartShippingAddress->address))
        ->assertJsonPath('cart.shipping_address.city', $cartShippingAddress->city)
        ->assertJsonPath('cart.shipping_address.state', $cartShippingAddress->state)
        ->assertJsonPath('cart.shipping_address.country', $cartShippingAddress->country)
        ->assertJsonPath('cart.shipping_address.postcode', $cartShippingAddress->postcode)
        ->assertJsonPath('cart.shipping_address.email', $cartShippingAddress->email)
        ->assertJsonPath('cart.shipping_address.phone', $cartShippingAddress->phone)
        ->assertJsonPath('cart.shipping_address.vat_id', $cartShippingAddress->vat_id)
        ->assertJsonPath('cart.have_stockable_items', true)
        ->assertJsonPath('cart.payment_method', 'cashondelivery')
        ->assertJsonPath('cart.payment_method_title', 'Cash On Delivery')
        ->assertOk();

    $this->assertPrice($product->price, $response['cart']['grand_total']);

    $this->assertPrice($cartItem->total, $response['cart']['items']['0']['total']);

    $this->assertPrice($product->price, $response['cart']['sub_total']);
});

it('should store the payment method for customer', function () {
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

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $cartShippingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    cart()->setCart($cart);

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.checkout.onepage.payment_methods.store'), [
        'payment' => [
            'method'       => 'cashondelivery',
            'method_title' => 'Cash On Delivery',
            'description'  => 'Cash On Delivery',
            'sort'         => 1,
        ],
    ])
        ->assertJsonPath('cart.id', $cart->id)
        ->assertJsonPath('cart.is_guest', 0)
        ->assertJsonPath('cart.items_count', 1)
        ->assertJsonPath('cart.customer_id', $customer->id)
        ->assertJsonPath('cart.items_count', 1)
        ->assertJsonPath('cart.items_qty', 1)
        ->assertJsonPath('cart.base_sub_total', core()->formatBasePrice($product->price))
        ->assertJsonPath('cart.items.0.id', $cartItem->id)
        ->assertJsonPath('cart.items.0.quantity', 1)
        ->assertJsonPath('cart.items.0.type', $cartItem->type)
        ->assertJsonPath('cart.items.0.name', $cartItem->name)
        ->assertJsonPath('cart.items.0.price', $cartItem->price)
        ->assertJsonPath('cart.items.0.formatted_price', core()->formatPrice($cartItem->price))
        ->assertJsonPath('cart.items.0.formatted_total', core()->formatPrice($cartItem->total))
        ->assertJsonPath('cart.items.0.options', array_values($cartItem->additional['attributes'] ?? []))
        ->assertJsonPath('cart.items.0.base_image', $cartItem->getTypeInstance()->getBaseImage($cartItem))
        ->assertJsonPath('cart.items.0.product_url_key', $cartItem->product->url_key)
        ->assertJsonPath('cart.billing_address.id', $cartBillingAddress->id)
        ->assertJsonPath('cart.billing_address.address_type', $cartBillingAddress->address_type)
        ->assertJsonPath('cart.billing_address.parent_address_id', $cartBillingAddress->parent_address_id)
        ->assertJsonPath('cart.billing_address.customer_id', $cartBillingAddress->customer_id)
        ->assertJsonPath('cart.billing_address.cart_id', $cart->id)
        ->assertJsonPath('cart.billing_address.order_id', null)
        ->assertJsonPath('cart.billing_address.first_name', $cartBillingAddress->first_name)
        ->assertJsonPath('cart.billing_address.last_name', $cartBillingAddress->last_name)
        ->assertJsonPath('cart.billing_address.gender', $cartBillingAddress->gender)
        ->assertJsonPath('cart.billing_address.company_name', $cartBillingAddress->company_name)
        ->assertJsonPath('cart.billing_address.address', explode("\n", $cartBillingAddress->address))
        ->assertJsonPath('cart.billing_address.city', $cartBillingAddress->city)
        ->assertJsonPath('cart.billing_address.state', $cartBillingAddress->state)
        ->assertJsonPath('cart.billing_address.country', $cartBillingAddress->country)
        ->assertJsonPath('cart.billing_address.postcode', $cartBillingAddress->postcode)
        ->assertJsonPath('cart.billing_address.email', $cartBillingAddress->email)
        ->assertJsonPath('cart.billing_address.phone', $cartBillingAddress->phone)
        ->assertJsonPath('cart.billing_address.vat_id', $cartBillingAddress->vat_id)
        ->assertJsonPath('cart.shipping_address.id', $cartShippingAddress->id)
        ->assertJsonPath('cart.shipping_address.address_type', $cartShippingAddress->address_type)
        ->assertJsonPath('cart.shipping_address.parent_address_id', $cartShippingAddress->parent_address_id)
        ->assertJsonPath('cart.shipping_address.customer_id', $cartShippingAddress->customer_id)
        ->assertJsonPath('cart.shipping_address.cart_id', $cart->id)
        ->assertJsonPath('cart.shipping_address.order_id', null)
        ->assertJsonPath('cart.shipping_address.first_name', $cartShippingAddress->first_name)
        ->assertJsonPath('cart.shipping_address.last_name', $cartShippingAddress->last_name)
        ->assertJsonPath('cart.shipping_address.gender', $cartShippingAddress->gender)
        ->assertJsonPath('cart.shipping_address.company_name', $cartShippingAddress->company_name)
        ->assertJsonPath('cart.shipping_address.address', explode("\n", $cartShippingAddress->address))
        ->assertJsonPath('cart.shipping_address.city', $cartShippingAddress->city)
        ->assertJsonPath('cart.shipping_address.state', $cartShippingAddress->state)
        ->assertJsonPath('cart.shipping_address.country', $cartShippingAddress->country)
        ->assertJsonPath('cart.shipping_address.postcode', $cartShippingAddress->postcode)
        ->assertJsonPath('cart.shipping_address.email', $cartShippingAddress->email)
        ->assertJsonPath('cart.shipping_address.phone', $cartShippingAddress->phone)
        ->assertJsonPath('cart.shipping_address.vat_id', $cartShippingAddress->vat_id)
        ->assertJsonPath('cart.have_stockable_items', true)
        ->assertJsonPath('cart.payment_method', 'cashondelivery')
        ->assertJsonPath('cart.payment_method_title', 'Cash On Delivery')
        ->assertOk();

    $this->assertPrice($product->price, $response['cart']['grand_total']);

    $this->assertPrice($cartItem->total, $response['cart']['items']['0']['total']);

    $this->assertPrice($product->price, $response['cart']['sub_total']);
});

it('should place a simple product order for a guest user', function () {
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

    $cart = Cart::factory()->create(['shipping_method' => 'free_free']);

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

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $cartShippingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $cartPayment = CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => $methodTitle = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    $cartShippingRate = CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartShippingAddress->id,
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $cart->refresh();

    $cartItem->refresh();

    $cartPayment->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $this->assertModelWise([
        Cart::class => [
            $this->prepareCart($cart),
        ],

        CartItem::class => [
            $this->prepareCartItem($cartItem),
        ],

        CartPayment::class => [
            $this->prepareCartPayment($cartPayment),
        ],

        CartAddress::class => [
            $this->prepareAddress($cartBillingAddress),

            $this->prepareAddress($cartShippingAddress),
        ],

        CartShippingRate::class => [
            $this->prepareCartShippingRate($cartShippingRate),
        ],

        Order::class => [
            $this->prepareOrderUsingCart($cart),
        ],

        OrderItem::class => [
            $this->prepareOrderItemUsingCartItem($cartItem),
        ],

        OrderPayment::class => [
            $this->prepareOrderPaymentUsingCartPayment($cart->payment),
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $cartItem->quantity,
                'product_id' => $product->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $product->inventory_source_qty(1) - $cartItem->quantity,
                'product_id' => $product->id,
            ],
        ],
    ]);

    $cartBillingAddress->address_type = OrderAddress::ADDRESS_TYPE_BILLING;

    $cartShippingAddress->address_type = OrderAddress::ADDRESS_TYPE_SHIPPING;

    $this->assertModelWise([
        OrderAddress::class => [
            $this->prepareAddress($cartBillingAddress, OrderAddress::ADDRESS_TYPE_BILLING),

            $this->prepareAddress($cartShippingAddress, OrderAddress::ADDRESS_TYPE_SHIPPING),
        ],
    ]);
});

it('should place a simple product order for a guest user and send mail to guest and admin', function () {
    // Arrange.
    Mail::fake();

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

    $cart = Cart::factory()->create(['shipping_method' => 'free_free']);

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

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $cartShippingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $cartPayment = CartPayment::factory()->create([
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => $methodTitle = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
        'cart_id'      => $cart->id,
    ]);

    $cartShippingRate = CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'cart_address_id'    => $cartShippingAddress->id,
        'method'             => 'free_free',
        'method_description' => 'Free Shipping',
        'method_title'       => 'Free Shipping',
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $cart->refresh();

    $cartItem->refresh();

    $cartPayment->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $this->assertModelWise([
        Cart::class => [
            $this->prepareCart($cart),
        ],

        CartItem::class => [
            $this->prepareCartItem($cartItem),
        ],

        CartPayment::class => [
            $this->prepareCartPayment($cartPayment),
        ],

        CartAddress::class => [
            $this->prepareAddress($cartBillingAddress),

            $this->prepareAddress($cartShippingAddress),
        ],

        CartShippingRate::class => [
            $this->prepareCartShippingRate($cartShippingRate),
        ],

        Order::class => [
            $this->prepareOrderUsingCart($cart),
        ],

        OrderItem::class => [
            $this->prepareOrderItemUsingCartItem($cartItem),
        ],

        OrderPayment::class => [
            $this->prepareOrderPaymentUsingCartPayment($cart->payment),
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $cartItem->quantity,
                'product_id' => $product->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $product->inventory_source_qty(1) - $cartItem->quantity,
                'product_id' => $product->id,
            ],
        ],
    ]);

    $cartBillingAddress->address_type = OrderAddress::ADDRESS_TYPE_BILLING;

    $cartShippingAddress->address_type = OrderAddress::ADDRESS_TYPE_SHIPPING;

    $this->assertModelWise([
        OrderAddress::class => [
            $this->prepareAddress($cartBillingAddress, OrderAddress::ADDRESS_TYPE_BILLING),

            $this->prepareAddress($cartShippingAddress, OrderAddress::ADDRESS_TYPE_SHIPPING),
        ],
    ]);

    Mail::assertQueued(AdminOrderCreatedNotification::class);

    Mail::assertQueued(ShopOrderCreatedNotification::class);

    Mail::assertQueuedCount(2);
});

it('should place a simple product order for a customer', function () {
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
        'shipping_method'     => 'free_free',
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

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'          => $cart->id,
        'customer_id'      => $customer->id,
        'address_type'     => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $cartShippingAddress = CartAddress::factory()->create([
        'cart_id'          => $cart->id,
        'customer_id'      => $customer->id,
        'address_type'     => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $cartPayment = CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => $methodTitle = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    $cartShippingRate = CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartShippingAddress->id,
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $cart->refresh();

    $cartItem->refresh();

    $cartPayment->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $this->assertModelWise([
        Cart::class => [
            $this->prepareCart($cart),
        ],

        CartItem::class => [
            $this->prepareCartItem($cartItem),
        ],

        CartPayment::class => [
            $this->prepareCartPayment($cartPayment),
        ],

        CartAddress::class => [
            $this->prepareAddress($cartBillingAddress),

            $this->prepareAddress($cartShippingAddress),
        ],

        CartShippingRate::class => [
            $this->prepareCartShippingRate($cartShippingRate),
        ],

        Order::class => [
            $this->prepareOrderUsingCart($cart),
        ],

        OrderItem::class => [
            $this->prepareOrderItemUsingCartItem($cartItem),
        ],

        OrderPayment::class => [
            $this->prepareOrderPaymentUsingCartPayment($cart->payment),
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $cartItem->quantity,
                'product_id' => $product->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $product->inventory_source_qty(1) - $cartItem->quantity,
                'product_id' => $product->id,
            ],
        ],
    ]);

    $this->assertModelWise([
        OrderAddress::class => [
            $this->prepareAddress($cartBillingAddress, OrderAddress::ADDRESS_TYPE_BILLING),

            $this->prepareAddress($cartShippingAddress, OrderAddress::ADDRESS_TYPE_SHIPPING),
        ],
    ]);
});

it('should place a simple product order for a customer and send email to the customer', function () {
    // Arrange.
    Mail::fake();

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
        'shipping_method'     => 'free_free',
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

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'          => $cart->id,
        'customer_id'      => $customer->id,

        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $cartShippingAddress = CartAddress::factory()->create([
        'cart_id'          => $cart->id,
        'customer_id'      => $customer->id,
        'address_type'     => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $cartPayment = CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => $methodTitle = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    $cartShippingRate = CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartShippingAddress->id,
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $cart->refresh();

    $cartItem->refresh();

    $cartPayment->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $this->assertModelWise([
        Cart::class => [
            $this->prepareCart($cart),
        ],

        CartItem::class => [
            $this->prepareCartItem($cartItem),
        ],

        CartPayment::class => [
            $this->prepareCartPayment($cartPayment),
        ],

        CartAddress::class => [
            $this->prepareAddress($cartBillingAddress),

            $this->prepareAddress($cartShippingAddress),
        ],

        CartShippingRate::class => [
            $this->prepareCartShippingRate($cartShippingRate),
        ],

        Order::class => [
            $this->prepareOrderUsingCart($cart),
        ],

        OrderItem::class => [
            $this->prepareOrderItemUsingCartItem($cartItem),
        ],

        OrderPayment::class => [
            $this->prepareOrderPaymentUsingCartPayment($cart->payment),
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $cartItem->quantity,
                'product_id' => $product->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $product->inventory_source_qty(1) - $cartItem->quantity,
                'product_id' => $product->id,
            ],
        ],
    ]);

    $cartBillingAddress->address_type = OrderAddress::ADDRESS_TYPE_BILLING;

    $cartShippingAddress->address_type = OrderAddress::ADDRESS_TYPE_SHIPPING;

    $this->assertModelWise([
        OrderAddress::class => [
            $this->prepareAddress($cartBillingAddress, OrderAddress::ADDRESS_TYPE_BILLING),

            $this->prepareAddress($cartShippingAddress, OrderAddress::ADDRESS_TYPE_SHIPPING),
        ],
    ]);

    Mail::assertQueued(AdminOrderCreatedNotification::class);

    Mail::assertQueued(ShopOrderCreatedNotification::class);

    Mail::assertQueuedCount(2);
});

it('should place a configurable product order for a guest user', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            26 => 'guest_checkout',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],

            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getConfigurableProductFactory()
        ->create();

    $childProduct = $product->variants()->first();

    foreach ($product->super_attributes as $attribute) {
        foreach ($attribute->options as $option) {
            $superAttributes[$option->attribute_id] = $option->id;
        }
    }

    $additional = [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => $superAttributes ?? [],
    ];

    $cart = Cart::factory()->create(['shipping_method' => 'free_free']);

    $cartItem = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $childProduct->id,
        'sku'               => $childProduct->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $childProduct->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $childProduct->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $childProduct->weight ?? 0,
        'total_weight'      => ($childProduct->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($childProduct->weight ?? 0) * $additional['quantity'],
        'type'              => $childProduct->type,
        'additional'        => $additional,
    ]);

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $cartShippingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $cartShippingRate = CartShippingRate::factory()->create([
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartShippingAddress->id,
        'carrier_title'      => 'Free shipping',
        'method_title'       => 'Free Shipping',
        'carrier'            => 'free',
        'method'             => 'free_free',
    ]);

    $cartPayment = CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => $methodTitle = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $cart->refresh();

    $cartItem->refresh();

    $cartPayment->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $this->assertModelWise([
        Cart::class => [
            $this->prepareCart($cart),
        ],

        CartItem::class => [
            $this->prepareCartItem($cartItem),
        ],

        CartPayment::class => [
            $this->prepareCartPayment($cartPayment),
        ],

        CartAddress::class => [
            $this->prepareAddress($cartBillingAddress),

            $this->prepareAddress($cartShippingAddress),
        ],

        CartShippingRate::class => [
            $this->prepareCartShippingRate($cartShippingRate),
        ],

        Order::class => [
            $this->prepareOrderUsingCart($cart),
        ],

        OrderItem::class => [
            $this->prepareOrderItemUsingCartItem($cartItem),
        ],

        OrderPayment::class => [
            $this->prepareOrderPaymentUsingCartPayment($cart->payment),
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $additional['quantity'],
                'product_id' => $childProduct->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $childProduct->inventory_source_qty(1) - $additional['quantity'],
                'product_id' => $childProduct->id,
            ],
        ],
    ]);

    $cartBillingAddress->address_type = OrderAddress::ADDRESS_TYPE_BILLING;

    $cartShippingAddress->address_type = OrderAddress::ADDRESS_TYPE_SHIPPING;

    $this->assertModelWise([
        OrderAddress::class => [
            $this->prepareAddress($cartBillingAddress, OrderAddress::ADDRESS_TYPE_BILLING),

            $this->prepareAddress($cartShippingAddress, OrderAddress::ADDRESS_TYPE_SHIPPING),
        ],
    ]);
});

it('should place a configurable product order for a guest user and send email to the guest user', function () {
    // Arrange.
    Mail::fake();

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            26 => 'guest_checkout',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],

            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getConfigurableProductFactory()
        ->create();

    $childProduct = $product->variants()->first();

    foreach ($product->super_attributes as $attribute) {
        foreach ($attribute->options as $option) {
            $superAttributes[$option->attribute_id] = $option->id;
        }
    }

    $additional = [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => $superAttributes ?? [],
    ];

    $cart = Cart::factory()->create(['shipping_method' => 'free_free']);

    $cartItem = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $childProduct->id,
        'sku'               => $childProduct->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $childProduct->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $childProduct->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $childProduct->weight ?? 0,
        'total_weight'      => ($childProduct->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($childProduct->weight ?? 0) * $additional['quantity'],
        'type'              => $childProduct->type,
        'additional'        => $additional,
    ]);

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $cartShippingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $cartShippingRate = CartShippingRate::factory()->create([
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartShippingAddress->id,
        'carrier_title'      => 'Free shipping',
        'method_title'       => 'Free Shipping',
        'carrier'            => 'free',
        'method'             => 'free_free',
    ]);

    $cartPayment = CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => $methodTitle = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $cart->refresh();

    $cartItem->refresh();

    $cartPayment->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $this->assertModelWise([
        Cart::class => [
            $this->prepareCart($cart),
        ],

        CartItem::class => [
            $this->prepareCartItem($cartItem),
        ],

        CartPayment::class => [
            $this->prepareCartPayment($cartPayment),
        ],

        CartAddress::class => [
            $this->prepareAddress($cartBillingAddress),

            $this->prepareAddress($cartShippingAddress),
        ],

        CartShippingRate::class => [
            $this->prepareCartShippingRate($cartShippingRate),
        ],

        Order::class => [
            $this->prepareOrderUsingCart($cart),
        ],

        OrderItem::class => [
            $this->prepareOrderItemUsingCartItem($cartItem),
        ],

        OrderPayment::class => [
            $this->prepareOrderPaymentUsingCartPayment($cart->payment),
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $additional['quantity'],
                'product_id' => $childProduct->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $childProduct->inventory_source_qty(1) - $additional['quantity'],
                'product_id' => $childProduct->id,
            ],
        ],
    ]);

    $cartBillingAddress->address_type = OrderAddress::ADDRESS_TYPE_BILLING;

    $cartShippingAddress->address_type = OrderAddress::ADDRESS_TYPE_SHIPPING;

    $this->assertModelWise([
        OrderAddress::class => [
            $this->prepareAddress($cartBillingAddress, OrderAddress::ADDRESS_TYPE_BILLING),

            $this->prepareAddress($cartShippingAddress, OrderAddress::ADDRESS_TYPE_SHIPPING),
        ],
    ]);

    Mail::assertQueued(AdminOrderCreatedNotification::class);

    Mail::assertQueued(ShopOrderCreatedNotification::class);

    Mail::assertQueuedCount(2);
});

it('should place a configurable product order for a customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getConfigurableProductFactory()
        ->create();

    $childProduct = $product->variants()->first();

    foreach ($product->super_attributes as $attribute) {
        foreach ($attribute->options as $option) {
            $superAttributes[$option->attribute_id] = $option->id;
        }
    }

    $additional = [
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => $superAttributes ?? [],
        'selected_configurable_option' => $childProduct->id,
    ];

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'shipping_method'     => 'free_free',
    ]);

    $cartItem = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $childProduct->id,
        'sku'               => $childProduct->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $childProduct->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $childProduct->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $childProduct->weight ?? 0,
        'total_weight'      => ($childProduct->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($childProduct->weight ?? 0) * $additional['quantity'],
        'type'              => $childProduct->type,
        'additional'        => $additional,
    ]);

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
        'customer_id'  => $customer->id,
    ]);

    $cartShippingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
        'customer_id'  => $customer->id,
    ]);

    $cartShippingRate = CartShippingRate::factory()->create([
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartShippingAddress->id,
        'carrier_title'      => 'Free shipping',
        'method_title'       => 'Free Shipping',
        'carrier'            => 'free',
        'method'             => 'free_free',
    ]);

    $cartPayment = CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => $methodTitle = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $cart->refresh();

    $cartItem->refresh();

    $cartPayment->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $this->assertModelWise([
        Cart::class => [
            $this->prepareCart($cart),
        ],

        CartItem::class => [
            $this->prepareCartItem($cartItem),
        ],

        CartPayment::class => [
            $this->prepareCartPayment($cartPayment),
        ],

        CartAddress::class => [
            $this->prepareAddress($cartBillingAddress),

            $this->prepareAddress($cartShippingAddress),
        ],

        CartShippingRate::class => [
            $this->prepareCartShippingRate($cartShippingRate),
        ],

        Order::class => [
            $this->prepareOrderUsingCart($cart),
        ],

        OrderItem::class => [
            $this->prepareOrderItemUsingCartItem($cartItem),
        ],

        OrderPayment::class => [
            $this->prepareOrderPaymentUsingCartPayment($cart->payment),
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $additional['quantity'],
                'product_id' => $childProduct->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $childProduct->inventory_source_qty(1) - $additional['quantity'],
                'product_id' => $childProduct->id,
            ],
        ],
    ]);

    $cartBillingAddress->address_type = OrderAddress::ADDRESS_TYPE_BILLING;

    $cartShippingAddress->address_type = OrderAddress::ADDRESS_TYPE_SHIPPING;

    $this->assertModelWise([
        OrderAddress::class => [
            $this->prepareAddress($cartBillingAddress, OrderAddress::ADDRESS_TYPE_BILLING),

            $this->prepareAddress($cartShippingAddress, OrderAddress::ADDRESS_TYPE_SHIPPING),
        ],
    ]);
});

it('should place a configurable product order for a customer and send email to the user', function () {
    // Arrange.
    Mail::fake();

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getConfigurableProductFactory()
        ->create();

    $childProduct = $product->variants()->first();

    foreach ($product->super_attributes as $attribute) {
        foreach ($attribute->options as $option) {
            $superAttributes[$option->attribute_id] = $option->id;
        }
    }

    $additional = [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => $superAttributes ?? [],
    ];

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'shipping_method'     => 'free_free',
    ]);

    $cartItem = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $childProduct->id,
        'sku'               => $childProduct->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $childProduct->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $childProduct->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => $price * $additional['quantity'],
        'weight'            => $childProduct->weight ?? 0,
        'total_weight'      => ($childProduct->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($childProduct->weight ?? 0) * $additional['quantity'],
        'type'              => $childProduct->type,
        'additional'        => $additional,
    ]);

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
        'customer_id'  => $customer->id,
    ]);

    $cartShippingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
        'customer_id'  => $customer->id,
    ]);

    $cartShippingRate = CartShippingRate::factory()->create([
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartShippingAddress->id,
        'carrier_title'      => 'Free shipping',
        'method_title'       => 'Free Shipping',
        'carrier'            => 'free',
        'method'             => 'free_free',
    ]);

    $cartPayment = CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => $methodTitle = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $cart->refresh();

    $cartItem->refresh();

    $cartPayment->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $this->assertModelWise([
        Cart::class => [
            $this->prepareCart($cart),
        ],

        CartItem::class => [
            $this->prepareCartItem($cartItem),
        ],

        CartPayment::class => [
            $this->prepareCartPayment($cartPayment),
        ],

        CartAddress::class => [
            $this->prepareAddress($cartBillingAddress),

            $this->prepareAddress($cartShippingAddress),
        ],

        CartShippingRate::class => [
            $this->prepareCartShippingRate($cartShippingRate),
        ],

        Order::class => [
            $this->prepareOrderUsingCart($cart),
        ],

        OrderItem::class => [
            $this->prepareOrderItemUsingCartItem($cartItem),
        ],

        OrderPayment::class => [
            $this->prepareOrderPaymentUsingCartPayment($cart->payment),
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $additional['quantity'],
                'product_id' => $childProduct->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $childProduct->inventory_source_qty(1) - $additional['quantity'],
                'product_id' => $childProduct->id,
            ],
        ],
    ]);

    $cartBillingAddress->address_type = OrderAddress::ADDRESS_TYPE_BILLING;

    $cartShippingAddress->address_type = OrderAddress::ADDRESS_TYPE_SHIPPING;

    $this->assertModelWise([
        OrderAddress::class => [
            $this->prepareAddress($cartBillingAddress, OrderAddress::ADDRESS_TYPE_BILLING),

            $this->prepareAddress($cartShippingAddress, OrderAddress::ADDRESS_TYPE_SHIPPING),
        ],
    ]);

    Mail::assertQueued(AdminOrderCreatedNotification::class);

    Mail::assertQueued(ShopOrderCreatedNotification::class);

    Mail::assertQueuedCount(2);
});

it('should place a virtual product order for a guest user', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            26 => 'guest_checkout',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],

            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getVirtualProductFactory()
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

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $cartPayment = CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => $methodTitle = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $cart->refresh();

    $cartItem->refresh();

    $cartPayment->refresh();

    $cartBillingAddress->refresh();

    $this->assertModelWise([
        Cart::class => [
            $this->prepareCart($cart),
        ],

        CartItem::class => [
            $this->prepareCartItem($cartItem),
        ],

        CartPayment::class => [
            $this->prepareCartPayment($cartPayment),
        ],

        CartAddress::class => [
            $this->prepareAddress($cartBillingAddress),
        ],

        Order::class => [
            $this->prepareOrderUsingCart($cart),
        ],

        OrderItem::class => [
            $this->prepareOrderItemUsingCartItem($cartItem),
        ],

        OrderPayment::class => [
            $this->prepareOrderPaymentUsingCartPayment($cart->payment),
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $cartItem->quantity,
                'product_id' => $product->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $product->inventory_source_qty(1) - $cartItem->quantity,
                'product_id' => $product->id,
            ],
        ],
    ]);

    $this->assertModelWise([
        OrderAddress::class => [
            $this->prepareAddress($cartBillingAddress, OrderAddress::ADDRESS_TYPE_BILLING),
        ],
    ]);
});

it('should place a virtual product order for a guest user and send email to the guest user', function () {
    // Arrange.
    Mail::fake();

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            26 => 'guest_checkout',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],

            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getVirtualProductFactory()
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

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $cartPayment = CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => $methodTitle = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $cart->refresh();

    $cartItem->refresh();

    $cartPayment->refresh();

    $cartBillingAddress->refresh();

    $this->assertModelWise([
        Cart::class => [
            $this->prepareCart($cart),
        ],

        CartItem::class => [
            $this->prepareCartItem($cartItem),
        ],

        CartPayment::class => [
            $this->prepareCartPayment($cartPayment),
        ],

        CartAddress::class => [
            $this->prepareAddress($cartBillingAddress),
        ],

        Order::class => [
            $this->prepareOrderUsingCart($cart),
        ],

        OrderItem::class => [
            $this->prepareOrderItemUsingCartItem($cartItem),
        ],

        OrderPayment::class => [
            $this->prepareOrderPaymentUsingCartPayment($cart->payment),
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $cartItem->quantity,
                'product_id' => $product->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $product->inventory_source_qty(1) - $cartItem->quantity,
                'product_id' => $product->id,
            ],
        ],
    ]);

    $cartBillingAddress->address_type = OrderAddress::ADDRESS_TYPE_BILLING;

    $this->assertModelWise([
        OrderAddress::class => [
            $this->prepareAddress($cartBillingAddress, OrderAddress::ADDRESS_TYPE_BILLING),
        ],
    ]);

    Mail::assertQueued(AdminOrderCreatedNotification::class);

    Mail::assertQueued(ShopOrderCreatedNotification::class);

    Mail::assertQueuedCount(2);
});

it('should place a virtual product order for a customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getVirtualProductFactory()
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

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
        'customer_id'  => $cart->customer_id,
    ]);

    $cartPayment = CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => $methodTitle = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $cart->refresh();

    $cartItem->refresh();

    $cartPayment->refresh();

    $cartBillingAddress->refresh();

    $this->assertModelWise([
        Cart::class => [
            $this->prepareCart($cart),
        ],

        CartItem::class => [
            $this->prepareCartItem($cartItem),
        ],

        CartPayment::class => [
            $this->prepareCartPayment($cartPayment),
        ],

        CartAddress::class => [
            $this->prepareAddress($cartBillingAddress),
        ],

        Order::class => [
            $this->prepareOrderUsingCart($cart),
        ],

        OrderItem::class => [
            $this->prepareOrderItemUsingCartItem($cartItem),
        ],

        OrderPayment::class => [
            $this->prepareOrderPaymentUsingCartPayment($cart->payment),
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $cartItem->quantity,
                'product_id' => $product->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $product->inventory_source_qty(1) - $cartItem->quantity,
                'product_id' => $product->id,
            ],
        ],
    ]);

    $cartBillingAddress->address_type = OrderAddress::ADDRESS_TYPE_BILLING;

    $this->assertModelWise([
        OrderAddress::class => [
            $this->prepareAddress($cartBillingAddress, OrderAddress::ADDRESS_TYPE_BILLING),
        ],
    ]);
});

it('should place a virtual product order for a customer and send email to the user', function () {
    // Arrange.
    Mail::fake();

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getVirtualProductFactory()
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

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
        'customer_id'  => $cart->customer_id,
    ]);

    $cartPayment = CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => $methodTitle = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $cart->refresh();

    $cartItem->refresh();

    $cartPayment->refresh();

    $cartBillingAddress->refresh();

    $this->assertModelWise([
        Cart::class => [
            $this->prepareCart($cart),
        ],

        CartItem::class => [
            $this->prepareCartItem($cartItem),
        ],

        CartPayment::class => [
            $this->prepareCartPayment($cartPayment),
        ],

        CartAddress::class => [
            $this->prepareAddress($cartBillingAddress),
        ],

        Order::class => [
            $this->prepareOrderUsingCart($cart),
        ],

        OrderItem::class => [
            $this->prepareOrderItemUsingCartItem($cartItem),
        ],

        OrderPayment::class => [
            $this->prepareOrderPaymentUsingCartPayment($cart->payment),
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $cartItem->quantity,
                'product_id' => $product->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $product->inventory_source_qty(1) - $cartItem->quantity,
                'product_id' => $product->id,
            ],
        ],
    ]);

    $cartBillingAddress->address_type = OrderAddress::ADDRESS_TYPE_BILLING;

    $this->assertModelWise([
        OrderAddress::class => [
            $this->prepareAddress($cartBillingAddress, OrderAddress::ADDRESS_TYPE_BILLING),
        ],
    ]);

    Mail::assertQueued(AdminOrderCreatedNotification::class);

    Mail::assertQueued(ShopOrderCreatedNotification::class);

    Mail::assertQueuedCount(2);
});

it('should place a downloadable product order for a customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getDownloadableProductFactory()
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
        'is_buy_now' => '0',
        'rating'     => '0',
        'quantity'   => '1',
        'links'      => [
            '1',
        ],
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

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $cartPayment = CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => $methodTitle = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $cart->refresh();

    $cartItem->refresh();

    $cartPayment->refresh();

    $cartBillingAddress->refresh();

    $this->assertModelWise([
        Cart::class => [
            $this->prepareCart($cart),
        ],

        CartItem::class => [
            $this->prepareCartItem($cartItem),
        ],

        CartPayment::class => [
            $this->prepareCartPayment($cartPayment),
        ],

        CartAddress::class => [
            $this->prepareAddress($cartBillingAddress),
        ],

        Order::class => [
            $this->prepareOrderUsingCart($cart),
        ],

        OrderItem::class => [
            $this->prepareOrderItemUsingCartItem($cartItem),
        ],

        OrderPayment::class => [
            $this->prepareOrderPaymentUsingCartPayment($cart->payment),
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => 0,
                'product_id' => $product->id,
            ],
        ],
    ]);

    $cartBillingAddress->address_type = OrderAddress::ADDRESS_TYPE_BILLING;

    $this->assertModelWise([
        OrderAddress::class => [
            $this->prepareAddress($cartBillingAddress, OrderAddress::ADDRESS_TYPE_BILLING),
        ],
    ]);
});

it('should place a downloadable product order for a customer and send email to the user', function () {
    // Arrange.
    Mail::fake();

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getDownloadableProductFactory()
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
        'is_buy_now' => '0',
        'rating'     => '0',
        'quantity'   => '1',
        'links'      => [
            '1',
        ],
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

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $cartPayment = CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => $methodTitle = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $cart->refresh();

    $cartItem->refresh();

    $cartPayment->refresh();

    $cartBillingAddress->refresh();

    $this->assertModelWise([
        Cart::class => [
            $this->prepareCart($cart),
        ],

        CartItem::class => [
            $this->prepareCartItem($cartItem),
        ],

        CartPayment::class => [
            $this->prepareCartPayment($cartPayment),
        ],

        CartAddress::class => [
            $this->prepareAddress($cartBillingAddress),
        ],

        Order::class => [
            $this->prepareOrderUsingCart($cart),
        ],

        OrderItem::class => [
            $this->prepareOrderItemUsingCartItem($cartItem),
        ],

        OrderPayment::class => [
            $this->prepareOrderPaymentUsingCartPayment($cart->payment),
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => 0,
                'product_id' => $product->id,
            ],
        ],
    ]);

    $cartBillingAddress->address_type = OrderAddress::ADDRESS_TYPE_BILLING;

    $this->assertModelWise([
        OrderAddress::class => [
            $this->prepareAddress($cartBillingAddress, OrderAddress::ADDRESS_TYPE_BILLING),
        ],
    ]);

    Mail::assertQueued(AdminOrderCreatedNotification::class);

    Mail::assertQueued(ShopOrderCreatedNotification::class);

    Mail::assertQueuedCount(2);
});

it('should not return the cash on delivery payment method if product is downloadable', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getDownloadableProductFactory()
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
        'is_buy_now' => '0',
        'rating'     => '0',
        'quantity'   => '1',
        'links'      => [
            '1',
        ],
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

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => $methodTitle = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            ...CustomerAddress::factory()->create()->toArray(),
            'address'          => [fake()->address()],
            'use_for_shipping' => false,
        ],

        'shipping' => [
            ...CustomerAddress::factory()->create()->toArray(),
            'address' => [fake()->address()],
        ],
    ])
        ->assertOk()
        ->assertJsonPath('redirect', false)
        ->assertJsonCount(3, 'data.payment_methods')
        ->assertDontSeeText('cashondelivery');
});

it('should not return the shipping methods if product is downloadable', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getDownloadableProductFactory()
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
        'is_buy_now' => '0',
        'rating'     => '0',
        'quantity'   => '1',
        'links'      => [
            '1',
        ],
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

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => $methodTitle = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            ...CustomerAddress::factory()->create()->toArray(),
            'address'          => [fake()->address()],
            'use_for_shipping' => false,
        ],

        'shipping' => [
            ...CustomerAddress::factory()->create()->toArray(),
            'address' => [fake()->address()],
        ],
    ])
        ->assertOk()
        ->assertJsonPath('redirect', false)
        ->assertJsonMissingPath('data.shippingMethods');
});

it('should not return the cash on delivery payment method if product is virtual', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getVirtualProductFactory()
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
        'is_buy_now' => '0',
        'rating'     => '0',
        'quantity'   => '1',
        'links'      => [
            '1',
        ],
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

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => $methodTitle = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            ...CustomerAddress::factory()->create()->toArray(),
            'address'          => [fake()->address()],
            'use_for_shipping' => 0,
        ],

        'shipping' => [
            ...CustomerAddress::factory()->create()->toArray(),
            'address' => [fake()->address()],
        ],
    ])
        ->assertOk()
        ->assertJsonPath('redirect', false)
        ->assertJsonCount(3, 'data.payment_methods')
        ->assertDontSeeText('cashondelivery');
});

it('should not return the shipping methods if product is virtual', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getVirtualProductFactory()
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
        'is_buy_now' => '0',
        'rating'     => '0',
        'quantity'   => '1',
        'links'      => [
            '1',
        ],
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

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => $methodTitle = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            ...CustomerAddress::factory()->create()->toArray(),
            'address'          => [fake()->address()],
            'use_for_shipping' => fake()->boolean(),
        ],

        'shipping' => [
            ...CustomerAddress::factory()->create()->toArray(),
            'address' => [fake()->address()],
        ],
    ])
        ->assertOk()
        ->assertJsonPath('redirect', false)
        ->assertJsonMissingPath('data.shippingMethods');
});

it('should place order with two products with simple and configurable product type', function () {
    // Arrange.
    $simpleProduct = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $configurableProduct = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getConfigurableProductFactory()
        ->create();

    foreach ($configurableProduct->super_attributes as $attribute) {
        foreach ($attribute->options as $option) {
            $super_attributes[$option->attribute_id] = $option->id;
        }
    }

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'shipping_method'     => 'free_free',
    ]);

    $childProduct = $configurableProduct->variants()->first();

    $simpleProductAdditional = [
        'product_id' => $simpleProduct->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $configurableProductAdditional = [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $configurableProduct->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => $super_attributes ?? [],
    ];

    $configurableProductCartItem = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $childProduct->id,
        'sku'               => $childProduct->sku,
        'quantity'          => $configurableProductAdditional['quantity'],
        'name'              => $childProduct->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $childProduct->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $configurableProductAdditional['quantity'],
        'base_total'        => $price * $configurableProductAdditional['quantity'],
        'weight'            => $childProduct->weight ?? 0,
        'total_weight'      => ($childProduct->weight ?? 0) * $configurableProductAdditional['quantity'],
        'base_total_weight' => ($childProduct->weight ?? 0) * $configurableProductAdditional['quantity'],
        'type'              => $childProduct->type,
        'additional'        => $configurableProductAdditional,
    ]);

    $simpleProductCartItem = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $simpleProduct->id,
        'sku'               => $simpleProduct->sku,
        'quantity'          => $simpleProductAdditional['quantity'],
        'name'              => $simpleProduct->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $simpleProduct->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $simpleProductAdditional['quantity'],
        'base_total'        => $price * $simpleProductAdditional['quantity'],
        'weight'            => $simpleProduct->weight ?? 0,
        'total_weight'      => ($simpleProduct->weight ?? 0) * $simpleProductAdditional['quantity'],
        'base_total_weight' => ($simpleProduct->weight ?? 0) * $simpleProductAdditional['quantity'],
        'type'              => $simpleProduct->type,
        'additional'        => $simpleProductAdditional,
    ]);

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $cartShippingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $cartShippingRate = CartShippingRate::factory()->create([
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartShippingAddress->id,
        'carrier_title'      => 'Free shipping',
        'method_title'       => 'Free Shipping',
        'carrier'            => 'free',
        'method'             => 'free_free',
    ]);

    $cartPayment = CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $cart->refresh();

    $configurableProductCartItem->refresh();

    $simpleProductCartItem->refresh();

    $cartPayment->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $this->assertModelWise([
        Cart::class => [
            $this->prepareCart($cart),
        ],

        CartItem::class => [
            $this->prepareCartItem($configurableProductCartItem),

            $this->prepareCartItem($simpleProductCartItem),
        ],

        CartPayment::class => [
            $this->prepareCartPayment($cartPayment),
        ],

        CartAddress::class => [
            $this->prepareAddress($cartBillingAddress),

            $this->prepareAddress($cartShippingAddress),
        ],

        CartShippingRate::class => [
            $this->prepareCartShippingRate($cartShippingRate),
        ],

        Order::class => [
            $this->prepareOrderUsingCart($cart),
        ],

        OrderItem::class => [
            $this->prepareOrderItemUsingCartItem($configurableProductCartItem),

            $this->prepareOrderItemUsingCartItem($simpleProductCartItem),
        ],

        OrderPayment::class => [
            $this->prepareOrderPaymentUsingCartPayment($cart->payment),
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $simpleProductCartItem->quantity,
                'product_id' => $simpleProduct->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $simpleProduct->inventory_source_qty(1) - $simpleProduct->quantity,
                'product_id' => $simpleProduct->id,
            ],
        ],

        ProductOrderedInventory::class => [
            [
                'product_id' => $childProduct->id,
                'qty'        => $configurableProductCartItem->quantity,
            ],
        ],

        ProductOrderedInventory::class => [
            [
                'product_id' => $simpleProduct->id,
                'qty'        => $simpleProductCartItem->quantity,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $childProduct->inventory_source_qty(1) - $configurableProductCartItem->quantity,
                'product_id' => $childProduct->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $simpleProduct->inventory_source_qty(1) - $configurableProductCartItem->quantity,
                'product_id' => $simpleProduct->id,
            ],
        ],
    ]);
});

it('should place order with two products with simple and grouped product type', function () {
    // Arrange.
    $simpleProduct = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $groupedProduct = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getGroupedProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    $data = [
        'prices'      => [],
        'qty'         => [],
        'grand_total' => [],
    ];

    $bundleProducts = $groupedProduct->grouped_products(['associated_product'])->get();

    foreach ($bundleProducts as $bundleProduct) {
        $data['prices'][] = $price = $bundleProduct->associated_product->price;
        $data['qty'][] = $qty = $bundleProduct->qty;
        $data['grand_total'][] = $price * $qty;
    }

    $data['prices'][] = $simpleProduct->price;
    $data['qty'][] = 1;
    $data['grand_total'][] = (float) $simpleProduct->price;

    $cart = Cart::factory()->create([
        'channel_id'               => core()->getCurrentChannel()->id,
        'global_currency_code'     => $baseCurrencyCode = core()->getBaseCurrencyCode(),
        'base_currency_code'       => $baseCurrencyCode,
        'channel_currency_code'    => core()->getChannelBaseCurrencyCode(),
        'cart_currency_code'       => core()->getCurrentCurrencyCode(),
        'items_count'              => 5,
        'items_qty'                => array_sum($data['qty']),
        'grand_total'              => $price = array_sum($data['grand_total']),
        'base_grand_total'         => $price,
        'sub_total'                => $price,
        'base_sub_total'           => $price,
        'shipping_method'          => 'free_free',
        'customer_id'              => $customer->id,
        'is_active'                => 1,
        'customer_email'           => $customer->email,
        'customer_first_name'      => $customer->first_name,
        'customer_last_name'       => $customer->last_name,
    ]);

    $bundleProductCartItems = [];

    foreach ($bundleProducts as $bundleProduct) {
        $bundleProductCartItems[] = CartItem::factory()->create([
            'quantity'          => $bundleProduct->qty,
            'product_id'        => $bundleProduct->associated_product->id,
            'sku'               => $bundleProduct->associated_product->sku,
            'name'              => $bundleProduct->associated_product->name,
            'type'              => $bundleProduct->associated_product->type,
            'weight'            => 1,
            'total_weight'      => 1,
            'base_total_weight' => 1,
            'cart_id'           => $cart->id,
        ]);
    }

    $simpleProductCartItem = CartItem::factory()->create([
        'quantity'          => 1,
        'product_id'        => $simpleProduct->id,
        'sku'               => $simpleProduct->sku,
        'name'              => $simpleProduct->name,
        'type'              => $simpleProduct->type,
        'weight'            => 1,
        'total_weight'      => 1,
        'base_total_weight' => 1,
        'cart_id'           => $cart->id,
    ]);

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $cartShippingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $cartPayment = CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    $cartShippingRate = CartShippingRate::factory()->create([
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartShippingAddress->id,
        'carrier_title'      => 'Free shipping',
        'method_title'       => 'Free Shipping',
        'carrier'            => 'free',
        'method'             => 'free_free',
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $cart->refresh();

    $simpleProductCartItem->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $this->assertModelWise([
        Cart::class => [
            $this->prepareCart($cart),
        ],

        CartItem::class => [
            $this->prepareCartItem($simpleProductCartItem),
        ],

        CartPayment::class => [
            $this->prepareCartPayment($cartPayment),
        ],

        CartAddress::class => [
            $this->prepareAddress($cartBillingAddress),

            $this->prepareAddress($cartShippingAddress),
        ],

        CartShippingRate::class => [
            $this->prepareCartShippingRate($cartShippingRate),
        ],

        Order::class => [
            $this->prepareOrderUsingCart($cart),
        ],

        OrderItem::class => [
            $this->prepareOrderItemUsingCartItem($simpleProductCartItem),
        ],

        OrderPayment::class => [
            $this->prepareOrderPaymentUsingCartPayment($cart->payment),
        ],

        ProductOrderedInventory::class => [
            [
                'product_id' => $simpleProduct->id,
                'qty'        => 1,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $simpleProduct->inventory_source_qty(1) - 1,
                'product_id' => $simpleProduct->id,
            ],
        ],
    ]);

    foreach ($bundleProductCartItems as $bundleProductCartItem) {
        $bundleProductCartItem->refresh();

        $this->assertModelWise([
            CartItem::class => [
                $this->prepareCartItem($bundleProductCartItem),
            ],

            OrderItem::class => [
                $this->prepareOrderItemUsingCartItem($bundleProductCartItem),
            ],
        ]);
    }

    foreach ($bundleProducts as $bundleProduct) {
        $this->assertModelWise([
            ProductOrderedInventory::class => [
                [
                    'product_id' => $bundleProduct->associated_product->id,
                    'qty'        => $bundleProduct->qty,
                ],
            ],

            ProductInventoryIndex::class => [
                [
                    'qty'        => $bundleProduct->associated_product->inventory_source_qty(1) - $bundleProduct->qty,
                    'product_id' => $bundleProduct->associated_product->id,
                ],
            ],
        ]);
    }
});

it('should place order with two products with simple and downloadable product type', function () {
    // Arrange.
    $simpleProduct = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $downloadableProduct = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'featured' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getDownloadableProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'shipping_method'     => 'free_free',
    ]);

    $downloadAdditional = [
        'product_id' => $downloadableProduct->id,
        'is_buy_now' => '0',
        'rating'     => '0',
        'quantity'   => '1',
        'links'      => [
            '1',
        ],
    ];

    $simpleAdditional = [
        'product_id' => $simpleProduct->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $simpleProductCartItem = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $simpleProduct->id,
        'sku'               => $simpleProduct->sku,
        'quantity'          => $simpleAdditional['quantity'],
        'name'              => $simpleProduct->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $simpleProduct->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $simpleAdditional['quantity'],
        'base_total'        => $price * $simpleAdditional['quantity'],
        'weight'            => $simpleProduct->weight ?? 0,
        'total_weight'      => ($simpleProduct->weight ?? 0) * $simpleAdditional['quantity'],
        'base_total_weight' => ($simpleProduct->weight ?? 0) * $simpleAdditional['quantity'],
        'type'              => $simpleProduct->type,
        'additional'        => $simpleAdditional,
    ]);

    $downloadableProductCartItem = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $downloadableProduct->id,
        'sku'               => $downloadableProduct->sku,
        'quantity'          => $downloadAdditional['quantity'],
        'name'              => $downloadableProduct->name,
        'price'             => $convertedPrice = core()->convertPrice($price = $downloadableProduct->price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $downloadAdditional['quantity'],
        'base_total'        => $price * $downloadAdditional['quantity'],
        'weight'            => $downloadableProduct->weight ?? 0,
        'total_weight'      => ($downloadableProduct->weight ?? 0) * $downloadAdditional['quantity'],
        'base_total_weight' => ($downloadableProduct->weight ?? 0) * $downloadAdditional['quantity'],
        'type'              => $downloadableProduct->type,
        'additional'        => $downloadAdditional,
    ]);

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $cartShippingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $cartShippingRate = CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartShippingAddress->id,
    ]);

    $cartPayment = CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $cart->refresh();

    $simpleProductCartItem->refresh();

    $downloadableProductCartItem->refresh();

    $cartPayment->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $this->assertModelWise([
        Cart::class => [
            $this->prepareCart($cart),
        ],

        CartItem::class => [
            $this->prepareCartItem($simpleProductCartItem),

            $this->prepareCartItem($downloadableProductCartItem),
        ],

        CartPayment::class => [
            $this->prepareCartPayment($cartPayment),
        ],

        CartAddress::class => [
            $this->prepareAddress($cartBillingAddress),

            $this->prepareAddress($cartShippingAddress),
        ],

        CartShippingRate::class => [
            $this->prepareCartShippingRate($cartShippingRate),
        ],

        Order::class => [
            $this->prepareOrderUsingCart($cart),
        ],

        OrderItem::class => [
            $this->prepareOrderItemUsingCartItem($simpleProductCartItem),

            $this->prepareOrderItemUsingCartItem($downloadableProductCartItem),
        ],

        OrderPayment::class => [
            $this->prepareOrderPaymentUsingCartPayment($cart->payment),
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $simpleProductCartItem->quantity,
                'product_id' => $simpleProduct->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $simpleProduct->inventory_source_qty(1) - $simpleProductCartItem->quantity,
                'product_id' => $simpleProduct->id,
            ],
        ],

        ProductOrderedInventory::class => [
            [
                'product_id' => $simpleProduct->id,
                'qty'        => 1,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $simpleProduct->inventory_source_qty(1) - 1,
                'product_id' => $simpleProduct->id,
            ],
        ],
    ]);
});
