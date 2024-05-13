<?php

use Illuminate\Support\Arr;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartItem;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Customer\Models\CompareItem;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Customer\Models\Wishlist;
use Webkul\Faker\Helpers\Customer as CustomerFaker;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should search the customers via email or name', function () {
    // Arrange.
    $customer = (new CustomerFaker())->factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    getJson(route('admin.customers.customers.search'), [
        'query' => fake()->randomElement([$customer->name, $customer->email]),
    ])
        ->assertOk()
        ->assertJsonPath('data.0.id', $customer->id)
        ->assertJsonPath('data.0.first_name', $customer->first_name)
        ->assertJsonPath('data.0.last_name', $customer->last_name)
        ->assertJsonPath('data.0.gender', $customer->gender)
        ->assertJsonPath('data.0.status', $customer->status)
        ->assertJsonPath('data.0.customer_group_id', $customer->customer_group_id)
        ->assertJsonPath('data.0.email', $customer->email);
});

it('should create the customer if none exists when creating an order', function () {
    // Arrange.
    $customer = (new CustomerFaker())->factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'is_active'           => 0,
        'items_count'         => null,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    getJson(route('admin.sales.orders.create', $cart->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.orders.create.title', ['name' => $cart->customer->name]))
        ->assertSeeText(trans('admin::app.sales.orders.create.cart.items.add-product'))
        ->assertSeeText(trans('admin::app.sales.orders.create.wishlist-items.title'))
        ->assertSeeText(trans('admin::app.sales.orders.create.compare-items.title'))
        ->assertSeeText(trans('admin::app.sales.orders.create.recent-order-items.title'))
        ->assertSeeText(trans('admin::app.sales.orders.create.cart.items.title'));
});

it('should search the products via name for adding products to the cart', function () {
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

    // Act and Assert.
    $this->loginAsAdmin();

    getJson(route('admin.catalog.products.search'), [
        'query' => $product->name,
    ])
        ->assertOk()
        ->assertJsonPath('data.0.id', $product->id)
        ->assertJsonPath('data.0.name', $product->name)
        ->assertJsonPath('data.0.type', $product->type)
        ->assertJsonPath('data.0.sku', $product->sku);
});

it('should add product to the cart after search the product', function () {
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

    $customer = (new CustomerFaker())->factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'is_active'           => 0,
        'items_count'         => null,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    $response = postJson(route('admin.sales.cart.items.store', $cart->id), $data = [
        'product_id' => $product->id,
        'quantity'   => rand(1, 10),
    ])
        ->assertOk()
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.is_guest', $cart->is_guest)
        ->assertJsonPath('data.customer_id', $cart->customer_id)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', $data['quantity']);

    $this->assertPrice($price = $product->price * $data['quantity'], $response->json('data.grand_total'));

    $this->assertPrice($price, $response->json('data.sub_total'));

    $this->assertModelWise([
        CartItem::class => [
            [
                'cart_id'    => $cart->id,
                'product_id' => $data['product_id'],
                'sku'        => $product->sku,
                'type'       => $product->type,
                'name'       => $product->name,
                'price'      => $product->price,
                'total'      => $price,
            ],
        ],
    ]);
});

it('should update the cart item after add product to the cart', function () {
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

    $customer = (new CustomerFaker())->factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'is_active'           => 0,
        'items_count'         => null,
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
        'price'             => $convertedPrice = core()->convertPrice($product->price),
        'base_price'        => $product->price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => ($product->price) * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.sales.cart.items.update', $cart->id), [
        'qty' => [
            $cartItem->id => $qty = rand(2, 10),
        ],
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.orders.create.cart.success-update'));

    $cart->refresh();

    $cartItem->refresh();

    $this->assertPrice($product->price * $qty, $cart->grand_total);

    $this->assertPrice($product->price * $qty, $cart->sub_total);

    $this->assertModelWise([
        Cart::class => [
            [
                'customer_id' => $customer->id,
                'items_count' => 1,
                'items_qty'   => number_format($qty, 4),
            ],
        ],

        CartItem::class => [
            [
                'id'         => $cartItem->id,
                'cart_id'    => $cart->id,
                'product_id' => $product->id,
                'sku'        => $product->sku,
                'type'       => $product->type,
                'name'       => $product->name,
                'price'      => $product->price,
                'total'      => $product->price * $qty,
            ],
        ],
    ]);
});

it('should fails the validation error if billing and shipping address is not provided', function () {
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

    $customer = (new CustomerFaker())->factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'is_active'           => 0,
        'items_count'         => null,
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
        'price'             => $convertedPrice = core()->convertPrice($product->price),
        'base_price'        => $product->price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => ($product->price) * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.sales.cart.addresses.store', $cart->id), [
        'billing'  => [],
        'shipping' => [],
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

it('should add billing address after add item to the cart', function () {
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

    $customer = (new CustomerFaker())->factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'is_active'           => 0,
        'items_count'         => null,
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
        'price'             => $convertedPrice = core()->convertPrice($product->price),
        'base_price'        => $product->price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => ($product->price) * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    $customerAddress = CustomerAddress::factory()->create()->toArray();

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsAdmin();

    $response = postJson(route('admin.sales.cart.addresses.store', $cart->id), [
        'billing' => $billingAddress = [
            ...$customerAddress,
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

it('should add billing and shipping address after add item to the cart', function () {
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

    $customer = (new CustomerFaker())->factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'is_active'           => 0,
        'items_count'         => null,
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
        'price'             => $convertedPrice = core()->convertPrice($product->price),
        'base_price'        => $product->price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => ($product->price) * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    $customerAddress = CustomerAddress::factory()->create()->toArray();

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsAdmin();

    $response = postJson(route('admin.sales.cart.addresses.store', $cart->id), [
        'billing' => $billingAddress = [
            ...$customerAddress,
            'use_for_shipping' => 0,
            'address'          => [fake()->address()],
        ],

        'shipping' => $shippingAddress = [
            ...$customerAddress,
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
                'address'      => implode("\n", $shippingAddress['address']),
                'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
                'cart_id'      => $cart->id,
                ...Arr::only($shippingAddress, ['first_name', 'last_name', 'company_name', 'city', 'state', 'country', 'email', 'postcode', 'phone']),
            ],
        ],
    ]);
});

it('should the shipping rates after storing address', function () {
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

    $customer = (new CustomerFaker())->factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'is_active'           => 0,
        'items_count'         => null,
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
        'customer_Id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_Id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    cart()->setCart($cart);

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.sales.cart.shipping_methods.store', $cart->id), [
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

it('should store the payment method after storing the shipping method', function () {
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

    $customer = (new CustomerFaker())->factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'is_active'           => 0,
        'items_count'         => null,
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
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $cartShippingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    cart()->setCart($cart);

    // Act and Assert.
    $this->loginAsAdmin();

    $response = postJson(route('admin.sales.cart.payment_methods.store', $cart->id), [
        'payment' => [
            'description'  => 'Cash On Delivery',
            'method'       => 'cashondelivery',
            'method_title' => 'Cash On Delivery',
            'sort'         => 1,
        ],
    ])
        ->assertJsonPath('cart.id', $cart->id)
        ->assertJsonPath('cart.is_guest', 0)
        ->assertJsonPath('cart.items_count', 1)
        ->assertJsonPath('cart.customer_id', $customer->id)
        ->assertJsonPath('cart.items_count', 1)
        ->assertJsonPath('cart.items_qty', 1)
        ->assertJsonPath('cart.items.0.id', $cartItem->id)
        ->assertJsonPath('cart.items.0.quantity', 1)
        ->assertJsonPath('cart.items.0.type', $cartItem->type)
        ->assertJsonPath('cart.items.0.name', $cartItem->name)
        ->assertJsonPath('cart.items.0.price', $cartItem->price)
        ->assertJsonPath('cart.items.0.formatted_price', core()->formatPrice($cartItem->price))
        ->assertJsonPath('cart.items.0.formatted_total', core()->formatPrice($cartItem->total))
        ->assertJsonPath('cart.items.0.options', array_values($cartItem->additional['attributes'] ?? []))
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

it('should place order via admin', function () {
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

    $customer = (new CustomerFaker())->factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'shipping_method'     => 'free_free',
        'is_active'           => 0,
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

    $cartShippingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartShippingAddress->id,
        'cart_id'            => $cart->id,
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.sales.orders.store', $cart->id))
        ->assertOk()
        ->assertJsonPath('data.redirect', true);

    $this->assertDatabaseMissing('cart', [
        'id' => $cart->id,
    ]);
});

it('should lists the all wishlist items related to the customer', function () {
    // Arrange.
    $products = (new ProductFaker([
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
        ->count(5)
        ->create();

    $customer = (new CustomerFaker())->factory()->create();

    $wishlists = [];

    foreach ($products as $product) {
        $wishlists[] = Wishlist::factory()->create([
            'channel_id'  => core()->getDefaultChannel()->id,
            'product_id'  => $product->id,
            'customer_id' => $customer->id,
        ]);
    }

    // Act and assert.
    $this->loginAsAdmin();

    $response = getJson(route('admin.customers.customers.wishlist.items', $customer->id));

    foreach ($wishlists as $key => $wishlist) {
        $response->assertJsonPath('data.'.$key.'.id', $wishlist->id)
            ->assertJsonPath('data.'.$key.'.product.id', $wishlist->product_id)
            ->assertJsonPath('data.'.$key.'.product.name', $wishlist->product->name)
            ->assertJsonPath('data.'.$key.'.product.sku', $wishlist->product->sku)
            ->assertJsonPath('data.'.$key.'.product.type', $wishlist->product->type)
            ->assertJsonPath('data.'.$key.'.product.price', $wishlist->product->price)
            ->assertJsonPath('data.'.$key.'.product.formatted_price', core()->formatPrice($wishlist->product->price));
    }
});

it('should remove item from the wishlist', function () {
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

    $customer = (new CustomerFaker())->factory()->create();

    $wishlist = Wishlist::factory()->create([
        'channel_id'  => core()->getDefaultChannel()->id,
        'product_id'  => $product->id,
        'customer_id' => $customer->id,
    ]);

    // Act and assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.customers.customers.wishlist.items.delete', $customer->id), [
        'data' => [
            'item_id' => $wishlist->id,
        ],
    ]);

    $this->assertDatabaseMissing('wishlist', [
        'id' => $wishlist->id,
    ]);
});

it('should add a simple product wishlisted item to the cart', function () {
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

    $customer = (new CustomerFaker())->factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
    ]);

    $wishlist = Wishlist::factory()->create([
        'channel_id'  => core()->getDefaultChannel()->id,
        'product_id'  => $product->id,
        'customer_id' => $customer->id,
    ]);

    // Act and assert.
    $this->loginAsAdmin();

    $response = postJson(route('admin.sales.cart.items.store', $cart->id), [
        'data' => [
            'item_id' => $wishlist->id,
        ],
        'product_id' => $product->id,
        'quantity'   => 1,
    ])
        ->assertJsonPath('data.customer_id', $customer->id)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items.0.id', $cart->items->first()->id)
        ->assertJsonPath('data.items.0.cart_id', $cart->id)
        ->assertJsonPath('data.items.0.product_id', $product->id)
        ->assertJsonPath('data.items.0.sku', $product->sku)
        ->assertJsonPath('data.items.0.type', 'simple')
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('message', trans('admin::app.sales.orders.create.cart.success-add-to-cart'));

    $this->assertPrice($product->price, $response->json('data.items.0.price'));

    $this->assertPrice($product->price, $response->json('data.items.0.total'));

    $this->assertPrice($product->price, $response->json('data.grand_total'));

    $this->assertPrice($product->price, $response->json('data.sub_total'));
});

it('should add a configurable product wishlisted item to the cart', function () {
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
        ->getConfigurableProductFactory()
        ->create();

    $customer = (new CustomerFaker())->factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
    ]);

    $wishlist = Wishlist::factory()->create([
        'channel_id'  => core()->getDefaultChannel()->id,
        'product_id'  => $product->id,
        'customer_id' => $customer->id,
    ]);

    $childProduct = $product->variants()->first();

    // Act and assert.
    $this->loginAsAdmin();

    $response = postJson(route('admin.sales.cart.items.store', $cart->id), [
        'data' => [
            'item_id' => $wishlist->id,
        ],
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
        ->assertJsonPath('data.customer_id', $customer->id)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items.0.id', $cart->items->first()->id)
        ->assertJsonPath('data.items.0.cart_id', $cart->id)
        ->assertJsonPath('data.items.0.product_id', $product->id)
        ->assertJsonPath('data.items.0.sku', $product->sku)
        ->assertJsonPath('data.items.0.type', 'configurable')
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('message', trans('admin::app.sales.orders.create.cart.success-add-to-cart'));

    $this->assertPrice($childProduct->price, $response->json('data.items.0.price'));

    $this->assertPrice($childProduct->price, $response->json('data.items.0.total'));

    $this->assertPrice($childProduct->price, $response->json('data.grand_total'));

    $this->assertPrice($childProduct->price, $response->json('data.sub_total'));
});

it('should return all the compare items related to the customer', function () {
    // Arrange.
    $products = (new ProductFaker([
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
        ->count(5)
        ->create();

    $customer = (new CustomerFaker())->factory()->create();

    $compares = [];

    foreach ($products as $product) {
        $compares[] = CompareItem::factory()->create([
            'product_id'  => $product->id,
            'customer_id' => $customer->id,
        ]);
    }

    // Act and assert.
    $this->loginAsAdmin();

    $response = getJson(route('admin.customers.customers.compare.items', $customer->id));

    foreach ($compares as $key => $compare) {
        $response->assertJsonPath('data.'.$key.'.id', $compare->id)
            ->assertJsonPath('data.'.$key.'.product.id', $compare->product_id)
            ->assertJsonPath('data.'.$key.'.product.name', $compare->product->name)
            ->assertJsonPath('data.'.$key.'.product.sku', $compare->product->sku)
            ->assertJsonPath('data.'.$key.'.product.type', $compare->product->type)
            ->assertJsonPath('data.'.$key.'.product.price', $compare->product->price)
            ->assertJsonPath('data.'.$key.'.product.formatted_price', core()->formatPrice($compare->product->price));
    }
});

it('should remove compare items from the compared list item', function () {
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

    $customer = (new CustomerFaker())->factory()->create();

    $compare = CompareItem::factory()->create([
        'product_id'  => $product->id,
        'customer_id' => $customer->id,
    ]);

    // Act and assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.customers.customers.compare.items.delete', $customer->id), [
        'item_id' => $compare->id,
    ]);

    $this->assertDatabaseMissing('compare_items', [
        'id'          => $compare->id,
        'product_id'  => $compare->product_id,
        'customer_id' => $compare->customer_id,
    ]);
});

it('show add a simple product to the cart from compared items', function () {
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

    $customer = (new CustomerFaker())->factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
    ]);

    $compare = CompareItem::factory()->create([
        'product_id'  => $product->id,
        'customer_id' => $customer->id,
    ]);

    // Act and assert.
    $this->loginAsAdmin();

    $response = postJson(route('admin.sales.cart.items.store', $cart->id), [
        'data' => [
            'item_id' => $compare->id,
        ],
        'product_id' => $product->id,
        'quantity'   => 1,
    ])
        ->assertJsonPath('data.customer_id', $customer->id)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items.0.id', $cart->items->first()->id)
        ->assertJsonPath('data.items.0.cart_id', $cart->id)
        ->assertJsonPath('data.items.0.product_id', $product->id)
        ->assertJsonPath('data.items.0.sku', $product->sku)
        ->assertJsonPath('data.items.0.type', 'simple')
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('message', trans('admin::app.sales.orders.create.cart.success-add-to-cart'));

    $this->assertPrice($product->price, $response->json('data.items.0.price'));

    $this->assertPrice($product->price, $response->json('data.items.0.total'));

    $this->assertPrice($product->price, $response->json('data.grand_total'));

    $this->assertPrice($product->price, $response->json('data.sub_total'));
});

it('show add a configurable product to the cart from compared items', function () {
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
        ->getConfigurableProductFactory()
        ->create();

    $customer = (new CustomerFaker())->factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
    ]);

    $compare = CompareItem::factory()->create([
        'product_id'  => $product->id,
        'customer_id' => $customer->id,
    ]);

    $childProduct = $product->variants()->first();

    // Act and assert.
    $this->loginAsAdmin();

    $response = postJson(route('admin.sales.cart.items.store', $cart->id), [
        'data' => [
            'item_id' => $compare->id,
        ],
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
        ->assertJsonPath('data.customer_id', $customer->id)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items.0.id', $cart->items->first()->id)
        ->assertJsonPath('data.items.0.cart_id', $cart->id)
        ->assertJsonPath('data.items.0.product_id', $product->id)
        ->assertJsonPath('data.items.0.sku', $product->sku)
        ->assertJsonPath('data.items.0.type', 'configurable')
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('message', trans('admin::app.sales.orders.create.cart.success-add-to-cart'));

    $this->assertPrice($childProduct->price, $response->json('data.items.0.price'));

    $this->assertPrice($childProduct->price, $response->json('data.items.0.total'));

    $this->assertPrice($childProduct->price, $response->json('data.grand_total'));

    $this->assertPrice($childProduct->price, $response->json('data.sub_total'));
});

it('should return the list of the recent orders', function () {
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

    CustomerAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $cartShippingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartShippingAddress->id,
    ]);

    $order = Order::factory()->create([
        'cart_id'             => $cart->id,
        'customer_id'         => $customer->id,
        'customer_email'      => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
    ]);

    OrderItem::factory()->create([
        'product_id' => $product->id,
        'order_id'   => $order->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
    ]);

    OrderAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    OrderAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    // Act and assert.
    $this->loginAsAdmin();

    $response = getJson(route('admin.customers.customers.orders.recent_items', $cart->customer_id))
        ->assertJsonPath('data.0.id', $order->id)
        ->assertJsonPath('data.0.order_id', $order->id)
        ->assertJsonPath('data.0.product.id', $product->id)
        ->assertJsonPath('data.0.product.sku', $product->sku)
        ->assertJsonPath('data.0.product.name', $product->name);

    $this->assertPrice($product->price, $response->json('data.0.product.price'));
});
