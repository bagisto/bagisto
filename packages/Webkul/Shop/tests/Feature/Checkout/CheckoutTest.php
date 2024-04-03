<?php

use Illuminate\Support\Facades\Mail;
use Webkul\Admin\Mail\Order\CreatedNotification as AdminOrderCreatedNotification;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartItem;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Customer\Models\Customer;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\ProductInventoryIndex;
use Webkul\Product\Models\ProductOrderedInventory;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Shop\Mail\Order\CreatedNotification as ShopOrderCreatedNotification;

use function Pest\Laravel\postJson;

it('should handle certain validation errors when storing the guest user address for cart billing and shipping', function () {
    // Arrange
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

    cart()->putCart($cart);

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
        ->assertJsonValidationErrorFor('billing.country')
        ->assertJsonValidationErrorFor('billing.state')
        ->assertJsonValidationErrorFor('billing.postcode')
        ->assertJsonValidationErrorFor('billing.phone')
        ->assertJsonValidationErrorFor('shipping.first_name')
        ->assertJsonValidationErrorFor('shipping.last_name')
        ->assertJsonValidationErrorFor('shipping.email')
        ->assertJsonValidationErrorFor('shipping.address')
        ->assertJsonValidationErrorFor('shipping.city')
        ->assertJsonValidationErrorFor('shipping.country')
        ->assertJsonValidationErrorFor('shipping.state')
        ->assertJsonValidationErrorFor('shipping.postcode')
        ->assertJsonValidationErrorFor('shipping.phone')
        ->assertUnprocessable();
});

it('should handle certain validation errors when storing the customer address for cart billing and shipping', function () {
    // Arrange
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
        ->assertJsonValidationErrorFor('billing.country')
        ->assertJsonValidationErrorFor('billing.state')
        ->assertJsonValidationErrorFor('billing.postcode')
        ->assertJsonValidationErrorFor('billing.phone')
        ->assertJsonValidationErrorFor('shipping.first_name')
        ->assertJsonValidationErrorFor('shipping.last_name')
        ->assertJsonValidationErrorFor('shipping.email')
        ->assertJsonValidationErrorFor('shipping.address')
        ->assertJsonValidationErrorFor('shipping.city')
        ->assertJsonValidationErrorFor('shipping.country')
        ->assertJsonValidationErrorFor('shipping.state')
        ->assertJsonValidationErrorFor('shipping.postcode')
        ->assertJsonValidationErrorFor('shipping.phone')
        ->assertUnprocessable();
});

it('should store the shipping address as the billing address when use_for_shipping key is set to true in billing for guest user', function () {
    // Arrange
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

    cart()->putCart($cart);

    // Act and Assert.
    $response = postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            'address' => $address = [
                fake()->address(),
                fake()->address(),
            ],
            'company_name'     => $companyName = fake()->company(),
            'first_name'       => $firstName = fake()->firstName(),
            'last_name'        => $lastName = fake()->lastName(),
            'email'            => $email = fake()->email(),
            'country'          => $country = fake()->countryCode(),
            'state'            => $state = fake()->state(),
            'city'             => $city = fake()->city(),
            'postcode'         => $postCode = rand(111111, 999999),
            'phone'            => $phone = fake()->e164PhoneNumber(),
            'use_for_shipping' => true,
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
                'address_type'      => CartAddress::ADDRESS_TYPE_BILLING,
                'parent_address_id' => null,
                'customer_id'       => null,
                'cart_id'           => $cart->id,
                'order_id'          => null,
                'first_name'        => $firstName,
                'last_name'         => $lastName,
                'gender'            => null,
                'company_name'      => $companyName,
                'address'           => implode("\n", $address),
                'city'              => $city,
                'state'             => $state,
                'country'           => $country,
                'email'             => $email,
                'postcode'          => $postCode,
                'phone'             => $phone,
                'use_for_shipping'  => 1,
            ],
        ],

        CartAddress::class => [
            [
                'address_type'      => CartAddress::ADDRESS_TYPE_SHIPPING,
                'parent_address_id' => null,
                'customer_id'       => null,
                'cart_id'           => $cart->id,
                'order_id'          => null,
                'first_name'        => $firstName,
                'last_name'         => $lastName,
                'gender'            => null,
                'company_name'      => $companyName,
                'address'           => implode("\n", $address),
                'city'              => $city,
                'state'             => $state,
                'country'           => $country,
                'email'             => $email,
                'postcode'          => $postCode,
                'phone'             => $phone,
                'use_for_shipping'  => 0,
            ],
        ],
    ]);
});

it('should store the billing and shipping address for guest user', function () {
    // Arrange
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

    cart()->putCart($cart);

    // Act and Assert.
    $response = postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            'address' => $billingAddress = [
                fake()->address(),
                fake()->address(),
            ],
            'company_name'     => $billingCompanyName = fake()->company(),
            'first_name'       => $billingFirstName = fake()->firstName(),
            'last_name'        => $billingLastName = fake()->lastName(),
            'email'            => $billingEmail = fake()->email(),
            'country'          => $billingCountry = fake()->countryCode(),
            'state'            => $billingState = fake()->state(),
            'city'             => $billingCity = fake()->city(),
            'postcode'         => $billingPostCode = rand(111111, 999999),
            'phone'            => $billingPhone = fake()->e164PhoneNumber(),
        ],

        'shipping' => [
            'address' => $shippingAddress = [
                fake()->address(),
                fake()->address(),
            ],
            'company_name'     => $shippingCompanyName = fake()->company(),
            'first_name'       => $shippingFirstName = fake()->firstName(),
            'last_name'        => $shippingLastName = fake()->lastName(),
            'email'            => $shippingEmail = fake()->email(),
            'country'          => $shippingCountry = fake()->countryCode(),
            'state'            => $shippingState = fake()->state(),
            'city'             => $shippingCity = fake()->city(),
            'postcode'         => $shippingPostCode = rand(111111, 999999),
            'phone'            => $shippingPhone = fake()->e164PhoneNumber(),
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
                'address_type'      => CartAddress::ADDRESS_TYPE_BILLING,
                'parent_address_id' => null,
                'customer_id'       => null,
                'cart_id'           => $cart->id,
                'order_id'          => null,
                'first_name'        => $billingFirstName,
                'last_name'         => $billingLastName,
                'gender'            => null,
                'company_name'      => $billingCompanyName,
                'address'           => implode("\n", $billingAddress),
                'city'              => $billingCity,
                'state'             => $billingState,
                'country'           => $billingCountry,
                'email'             => $billingEmail,
                'postcode'          => $billingPostCode,
                'phone'             => $billingPhone,
            ],
        ],

        CartAddress::class => [
            [
                'address_type'      => CartAddress::ADDRESS_TYPE_SHIPPING,
                'parent_address_id' => null,
                'customer_id'       => null,
                'cart_id'           => $cart->id,
                'order_id'          => null,
                'first_name'        => $shippingFirstName,
                'last_name'         => $shippingLastName,
                'gender'            => null,
                'company_name'      => $shippingCompanyName,
                'address'           => implode("\n", $shippingAddress),
                'city'              => $shippingCity,
                'state'             => $shippingState,
                'country'           => $shippingCountry,
                'email'             => $shippingEmail,
                'postcode'          => $shippingPostCode,
                'phone'             => $shippingPhone,
            ],
        ],
    ]);
});

it('should store the billing address for non stockable items for guest user', function () {
    // Arrange
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

    cart()->putCart($cart);

    // Act and Assert.
    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            'address' => $billingAddress = [
                fake()->address(),
                fake()->address(),
            ],
            'company_name'     => $billingCompanyName = fake()->company(),
            'first_name'       => $billingFirstName = fake()->firstName(),
            'last_name'        => $billingLastName = fake()->lastName(),
            'email'            => $billingEmail = fake()->email(),
            'country'          => $billingCountry = fake()->countryCode(),
            'state'            => $billingState = fake()->state(),
            'city'             => $billingCity = fake()->city(),
            'postcode'         => $billingPostCode = rand(111111, 999999),
            'phone'            => $billingPhone = fake()->e164PhoneNumber(),
            'use_for_shipping' => true,
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
                'address_type'      => CartAddress::ADDRESS_TYPE_BILLING,
                'parent_address_id' => null,
                'customer_id'       => null,
                'cart_id'           => $cart->id,
                'order_id'          => null,
                'first_name'        => $billingFirstName,
                'last_name'         => $billingLastName,
                'gender'            => null,
                'company_name'      => $billingCompanyName,
                'address'           => implode("\n", $billingAddress),
                'city'              => $billingCity,
                'state'             => $billingState,
                'country'           => $billingCountry,
                'email'             => $billingEmail,
                'postcode'          => $billingPostCode,
                'phone'             => $billingPhone,
            ],
        ],
    ]);
});

it('should store the shipping address as the billing address when use_for_shipping key is set to true in billing for customer', function () {
    // Arrange
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
        'billing' => [
            'address' => $address = [
                fake()->address(),
                fake()->address(),
            ],
            'company_name'     => $companyName = fake()->company(),
            'first_name'       => $firstName = fake()->firstName(),
            'last_name'        => $lastName = fake()->lastName(),
            'email'            => $email = fake()->email(),
            'country'          => $country = fake()->countryCode(),
            'state'            => $state = fake()->state(),
            'city'             => $city = fake()->city(),
            'postcode'         => $postCode = rand(111111, 999999),
            'phone'            => $phone = fake()->e164PhoneNumber(),
            'use_for_shipping' => true,
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
                'address_type'      => CartAddress::ADDRESS_TYPE_BILLING,
                'parent_address_id' => null,
                'customer_id'       => $customer->id,
                'cart_id'           => $cart->id,
                'order_id'          => null,
                'first_name'        => $firstName,
                'last_name'         => $lastName,
                'gender'            => null,
                'company_name'      => $companyName,
                'address'           => implode("\n", $address),
                'city'              => $city,
                'state'             => $state,
                'country'           => $country,
                'email'             => $email,
                'postcode'          => $postCode,
                'phone'             => $phone,
                'use_for_shipping'  => 1,
            ],
        ],

        CartAddress::class => [
            [
                'address_type'      => CartAddress::ADDRESS_TYPE_SHIPPING,
                'parent_address_id' => null,
                'customer_id'       => $customer->id,
                'cart_id'           => $cart->id,
                'order_id'          => null,
                'first_name'        => $firstName,
                'last_name'         => $lastName,
                'gender'            => null,
                'company_name'      => $companyName,
                'address'           => implode("\n", $address),
                'city'              => $city,
                'state'             => $state,
                'country'           => $country,
                'email'             => $email,
                'postcode'          => $postCode,
                'phone'             => $phone,
                'use_for_shipping'  => 0,
            ],
        ],
    ]);
});

it('should store the billing and shipping address for customer', function () {
    // Arrange
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
        'billing' => [
            'address' => $billingAddress = [
                fake()->address(),
                fake()->address(),
            ],
            'company_name'     => $billingCompanyName = fake()->company(),
            'first_name'       => $billingFirstName = fake()->firstName(),
            'last_name'        => $billingLastName = fake()->lastName(),
            'email'            => $billingEmail = fake()->email(),
            'country'          => $billingCountry = fake()->countryCode(),
            'state'            => $billingState = fake()->state(),
            'city'             => $billingCity = fake()->city(),
            'postcode'         => $billingPostCode = rand(111111, 999999),
            'phone'            => $billingPhone = fake()->e164PhoneNumber(),
        ],

        'shipping' => [
            'address' => $shippingAddress = [
                fake()->address(),
                fake()->address(),
            ],
            'company_name'     => $shippingCompanyName = fake()->company(),
            'first_name'       => $shippingFirstName = fake()->firstName(),
            'last_name'        => $shippingLastName = fake()->lastName(),
            'email'            => $shippingEmail = fake()->email(),
            'country'          => $shippingCountry = fake()->countryCode(),
            'state'            => $shippingState = fake()->state(),
            'city'             => $shippingCity = fake()->city(),
            'postcode'         => $shippingPostCode = rand(111111, 999999),
            'phone'            => $shippingPhone = fake()->e164PhoneNumber(),
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
                'address_type'      => CartAddress::ADDRESS_TYPE_BILLING,
                'parent_address_id' => null,
                'customer_id'       => $customer->id,
                'cart_id'           => $cart->id,
                'order_id'          => null,
                'first_name'        => $billingFirstName,
                'last_name'         => $billingLastName,
                'gender'            => null,
                'company_name'      => $billingCompanyName,
                'address'           => implode("\n", $billingAddress),
                'city'              => $billingCity,
                'state'             => $billingState,
                'country'           => $billingCountry,
                'email'             => $billingEmail,
                'postcode'          => $billingPostCode,
                'phone'             => $billingPhone,
            ],
        ],

        CartAddress::class => [
            [
                'address_type'      => CartAddress::ADDRESS_TYPE_SHIPPING,
                'parent_address_id' => null,
                'customer_id'       => $customer->id,
                'cart_id'           => $cart->id,
                'order_id'          => null,
                'first_name'        => $shippingFirstName,
                'last_name'         => $shippingLastName,
                'gender'            => null,
                'company_name'      => $shippingCompanyName,
                'address'           => implode("\n", $shippingAddress),
                'city'              => $shippingCity,
                'state'             => $shippingState,
                'country'           => $shippingCountry,
                'email'             => $shippingEmail,
                'postcode'          => $shippingPostCode,
                'phone'             => $shippingPhone,
            ],
        ],
    ]);
});

it('should store the billing address for non stockable items for customer', function () {
    // Arrange
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
        'billing' => [
            'address' => $billingAddress = [
                fake()->address(),
                fake()->address(),
            ],
            'company_name'     => $billingCompanyName = fake()->company(),
            'first_name'       => $billingFirstName = fake()->firstName(),
            'last_name'        => $billingLastName = fake()->lastName(),
            'email'            => $billingEmail = fake()->email(),
            'country'          => $billingCountry = fake()->countryCode(),
            'state'            => $billingState = fake()->state(),
            'city'             => $billingCity = fake()->city(),
            'postcode'         => $billingPostCode = rand(111111, 999999),
            'phone'            => $billingPhone = fake()->e164PhoneNumber(),
            'use_for_shipping' => true,
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
                'address_type'      => CartAddress::ADDRESS_TYPE_BILLING,
                'parent_address_id' => null,
                'customer_id'       => $customer->id,
                'cart_id'           => $cart->id,
                'order_id'          => null,
                'first_name'        => $billingFirstName,
                'last_name'         => $billingLastName,
                'gender'            => null,
                'company_name'      => $billingCompanyName,
                'address'           => implode("\n", $billingAddress),
                'city'              => $billingCity,
                'state'             => $billingState,
                'country'           => $billingCountry,
                'email'             => $billingEmail,
                'postcode'          => $billingPostCode,
                'phone'             => $billingPhone,
            ],
        ],
    ]);
});

it('should fails the certain validation errors when use for shipping is set to false in billing address and shipping address not provided for customer', function () {
    // Arrange
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
            'address'          => [fake()->address()],
            'company_name'     => fake()->company(),
            'first_name'       => fake()->firstName(),
            'last_name'        => fake()->lastName(),
            'email'            => fake()->email(),
            'country'          => fake()->countryCode(),
            'state'            => fake()->state(),
            'city'             => fake()->city(),
            'postcode'         => rand(111111, 999999),
            'phone'            => fake()->e164PhoneNumber(),
            'use_for_shipping' => false,
        ],
    ])
        ->assertJsonValidationErrorFor('shipping.first_name')
        ->assertJsonValidationErrorFor('shipping.last_name')
        ->assertJsonValidationErrorFor('shipping.email')
        ->assertJsonValidationErrorFor('shipping.address')
        ->assertJsonValidationErrorFor('shipping.city')
        ->assertJsonValidationErrorFor('shipping.country')
        ->assertJsonValidationErrorFor('shipping.state')
        ->assertJsonValidationErrorFor('shipping.postcode')
        ->assertJsonValidationErrorFor('shipping.phone')
        ->assertUnprocessable();
});

it('should fails the certain validation errors when use for shipping is set to false in billing address and shipping address not provided for guest user', function () {
    // Arrange
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

    cart()->putCart($cart);

    // Act and Assert.
    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            'address'          => [fake()->address()],
            'company_name'     => fake()->company(),
            'first_name'       => fake()->firstName(),
            'last_name'        => fake()->lastName(),
            'email'            => fake()->email(),
            'country'          => fake()->countryCode(),
            'state'            => fake()->state(),
            'city'             => fake()->city(),
            'postcode'         => rand(111111, 999999),
            'phone'            => fake()->e164PhoneNumber(),
            'use_for_shipping' => false,
        ],
    ])
        ->assertJsonValidationErrorFor('shipping.first_name')
        ->assertJsonValidationErrorFor('shipping.last_name')
        ->assertJsonValidationErrorFor('shipping.email')
        ->assertJsonValidationErrorFor('shipping.address')
        ->assertJsonValidationErrorFor('shipping.city')
        ->assertJsonValidationErrorFor('shipping.country')
        ->assertJsonValidationErrorFor('shipping.state')
        ->assertJsonValidationErrorFor('shipping.postcode')
        ->assertJsonValidationErrorFor('shipping.phone')
        ->assertUnprocessable();
});

it('should fails the validation error when shipping method not providing when store the shipping method for the guest user', function () {
    // Arrange
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

    cart()->putCart($cart);

    // Act and Assert.
    postJson(route('shop.checkout.onepage.shipping_methods.store'))
        ->assertJsonValidationErrorFor('shipping_method')
        ->assertUnprocessable();
});

it('should fails the validation error when shipping method not providing when store the shipping method for the customer', function () {
    // Arrange
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

    cart()->putCart($cart);

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.shipping_methods.store'))
        ->assertJsonValidationErrorFor('shipping_method')
        ->assertUnprocessable();
});

it('should store the shipping method for guest user', function () {
    // Arrange
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
    // Arrange
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
    // Arrange
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
    // Arrange
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

    cart()->putCart($cart);

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

    $this->assertEquality($product->price, $response['cart']['grand_total']);

    $this->assertEquality($cartItem->total, $response['cart']['items']['0']['total']);

    $this->assertEquality($product->price, $response['cart']['sub_total']);
});

it('should store the payment method for customer', function () {
    // Arrange
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

    $this->assertEquality($product->price, $response['cart']['grand_total']);

    $this->assertEquality($cartItem->total, $response['cart']['items']['0']['total']);

    $this->assertEquality($product->price, $response['cart']['sub_total']);
});

it('should place a simple product order for a guest user', function () {
    // Arrange
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

    CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartShippingAddress->id,
    ]);

    cart()->putCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertCart($cart);

    $this->assertCartItem($cartItem);

    $this->assertCartPayment($cartPayment);

    $this->assertAddress($cartBillingAddress);

    $this->assertAddress($cartShippingAddress);

    $cartBillingAddress->address_type = 'order_billing';

    $this->assertAddress($cartBillingAddress);

    $cartShippingAddress->address_type = 'order_shipping';

    $this->assertAddress($cartShippingAddress);

    $this->assertModelWise([
        Order::class => [
            [
                'status'               => Order::STATUS_PENDING,
                'customer_id'          => null,
                'is_guest'             => 1,
                'total_item_count'     => 1,
                'total_qty_ordered'    => 1,
                'channel_name'         => core()->getCurrentChannel()->name,
                'base_currency_code'   => core()->getBaseCurrencyCode(),
                'channel_id'           => core()->getCurrentChannel()->id,
                'customer_email'       => $cart->customer_email,
                'customer_first_name'  => $cart->customer_first_name,
                'customer_last_name'   => $cart->customer_last_name,
                'shipping_method'      => $cart->shipping_method,
                'grand_total'          => $cart->grand_total,
                'base_grand_total'     => $cart->base_grand_total,
                'cart_id'              => $cart->id,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => $quantity = $cartItem->quantity,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'price'        => $price,
                'base_price'   => $price,
                'total'        => $price,
                'base_total'   => $price,
                'type'         => $product->type,
                'product_id'   => $product->id,
            ],
        ],

        OrderPayment::class => [
            [
                'method'       => $paymentMethod,
                'method_title' => $methodTitle,
            ],
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $quantity,
                'product_id' => $product->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $product->inventory_source_qty(1) - $quantity,
                'product_id' => $product->id,
            ],
        ],
    ]);
});

it('should place a simple product order for a guest user and send mail to guest and admin', function () {
    // Arrange
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

    CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'cart_address_id'    => $cartShippingAddress->id,
        'method'             => 'free_free',
        'method_description' => 'Free Shipping',
        'method_title'       => 'Free Shipping',
    ]);

    cart()->putCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertCart($cart);

    $this->assertCartItem($cartItem);

    $this->assertCartPayment($cartPayment);

    $this->assertAddress($cartBillingAddress);

    $this->assertAddress($cartShippingAddress);

    $cartBillingAddress->address_type = 'order_billing';

    $this->assertAddress($cartBillingAddress);

    $cartShippingAddress->address_type = 'order_shipping';

    $this->assertAddress($cartShippingAddress);

    $this->assertModelWise([
        Order::class => [
            [
                'status'               => Order::STATUS_PENDING,
                'channel_name'         => core()->getCurrentChannel()->name,
                'is_guest'             => 1,
                'customer_id'          => null,
                'channel_id'           => core()->getCurrentChannel()->id,
                'customer_email'       => $cart->customer_email,
                'customer_first_name'  => $cart->customer_first_name,
                'customer_last_name'   => $cart->customer_last_name,
                'shipping_method'      => $cart->shipping_method,
                'total_item_count'     => 1,
                'total_qty_ordered'    => 1,
                'base_currency_code'   => core()->getBaseCurrencyCode(),
                'grand_total'          => $cart->grand_total,
                'base_grand_total'     => $cart->base_grand_total,
                'cart_id'              => $cart->id,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => $quantity = $cartItem->quantity,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'price'        => $price,
                'total'        => $price,
                'base_total'   => $price,
                'type'         => $product->type,
                'product_id'   => $product->id,
            ],
        ],

        OrderPayment::class => [
            [
                'method'       => $paymentMethod,
                'method_title' => $methodTitle,
            ],
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $quantity,
                'product_id' => $product->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $product->inventory_source_qty(1) - $quantity,
                'product_id' => $product->id,
            ],
        ],
    ]);

    Mail::assertQueued(AdminOrderCreatedNotification::class);

    Mail::assertQueued(ShopOrderCreatedNotification::class);

    Mail::assertQueuedCount(2);
});

it('should place a simple product order for a customer', function () {
    // Arrange
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

    CartShippingRate::factory()->create([
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

    $this->assertCart($cart);

    $this->assertCartItem($cartItem);

    $this->assertCartPayment($cartPayment);

    $this->assertAddress($cartBillingAddress);

    $this->assertAddress($cartShippingAddress);

    $cartBillingAddress->address_type = 'order_billing';

    $this->assertAddress($cartBillingAddress);

    $cartShippingAddress->address_type = 'order_shipping';

    $this->assertAddress($cartShippingAddress);

    $this->assertModelWise([
        Order::class => [
            [
                'status'               => Order::STATUS_PENDING,
                'channel_name'         => core()->getCurrentChannel()->name,
                'is_guest'             => 0,
                'customer_id'          => $customer->id,
                'channel_id'           => core()->getCurrentChannel()->id,
                'customer_email'       => $cart->customer_email,
                'customer_first_name'  => $cart->customer_first_name,
                'customer_last_name'   => $cart->customer_last_name,
                'shipping_method'      => $cart->shipping_method,
                'total_item_count'     => 1,
                'total_qty_ordered'    => 1,
                'base_currency_code'   => core()->getBaseCurrencyCode(),
                'grand_total'          => $cart->grand_total,
                'base_grand_total'     => $cart->base_grand_total,
                'cart_id'              => $cart->id,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => $quantity = $cartItem->quantity,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'price'        => $price,
                'type'         => $product->type,
                'product_id'   => $product->id,
            ],
        ],

        OrderPayment::class => [
            [
                'method'       => $paymentMethod,
                'method_title' => $methodTitle,
            ],
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $quantity,
                'product_id' => $product->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $product->inventory_source_qty(1) - $quantity,
                'product_id' => $product->id,
            ],
        ],
    ]);
});

it('should place a simple product order for a customer and send email to the customer', function () {
    // Arrange
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

    CartShippingRate::factory()->create([
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

    $this->assertCart($cart);

    $this->assertCartItem($cartItem);

    $this->assertCartPayment($cartPayment);

    $this->assertAddress($cartBillingAddress);

    $this->assertAddress($cartShippingAddress);

    $cartBillingAddress->address_type = 'order_billing';

    $this->assertAddress($cartBillingAddress);

    $cartShippingAddress->address_type = 'order_shipping';

    $this->assertAddress($cartShippingAddress);

    $this->assertModelWise([
        Order::class => [
            [
                'status'               => Order::STATUS_PENDING,
                'channel_name'         => core()->getCurrentChannel()->name,
                'is_guest'             => 0,
                'customer_id'          => $customer->id,
                'channel_id'           => core()->getCurrentChannel()->id,
                'customer_email'       => $cart->customer_email,
                'customer_first_name'  => $cart->customer_first_name,
                'customer_last_name'   => $cart->customer_last_name,
                'shipping_method'      => $cart->shipping_method,
                'total_item_count'     => 1,
                'total_qty_ordered'    => 1,
                'base_currency_code'   => core()->getBaseCurrencyCode(),
                'grand_total'          => $cart->grand_total,
                'base_grand_total'     => $cart->base_grand_total,
                'cart_id'              => $cart->id,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => $quantity = $cartItem->quantity,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'price'        => $price,
                'type'         => $product->type,
                'product_id'   => $product->id,
            ],
        ],

        OrderPayment::class => [
            [
                'method'       => $paymentMethod,
                'method_title' => $methodTitle,
            ],
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $quantity,
                'product_id' => $product->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $product->inventory_source_qty(1) - $quantity,
                'product_id' => $product->id,
            ],
        ],
    ]);

    Mail::assertQueued(AdminOrderCreatedNotification::class);

    Mail::assertQueued(ShopOrderCreatedNotification::class);

    Mail::assertQueuedCount(2);
});

it('should place a configurable product order for a guest user', function () {
    // Arrange
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

    CartShippingRate::factory()->create([
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

    cart()->putCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertCart($cart);

    $this->assertCartItem($cartItem);

    $this->assertCartPayment($cartPayment);

    $this->assertAddress($cartBillingAddress);

    $this->assertAddress($cartShippingAddress);

    $cartBillingAddress->address_type = 'order_billing';

    $this->assertAddress($cartBillingAddress);

    $cartShippingAddress->address_type = 'order_shipping';

    $this->assertAddress($cartShippingAddress);

    $this->assertModelWise([
        Order::class => [
            [
                'shipping_method'      => 'free_free',
                'grand_total'          => $childProduct->price,
                'cart_id'              => $cart->id,
                'status'               => Order::STATUS_PENDING,
                'channel_name'         => core()->getCurrentChannel()->name,
                'is_guest'             => 1,
                'channel_id'           => core()->getCurrentChannel()->id,
                'customer_email'       => $cart->customer_email,
                'customer_first_name'  => $cart->customer_first_name,
                'customer_last_name'   => $cart->customer_last_name,
                'shipping_method'      => $cart->shipping_method,
                'total_item_count'     => 1,
                'total_qty_ordered'    => 1,
                'base_currency_code'   => core()->getBaseCurrencyCode(),
                'grand_total'          => $cart->grand_total,
                'base_grand_total'     => $cart->base_grand_total,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => $cartItem->quantity,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'product_id'   => $childProduct->id,
                'price'        => $childProduct->price,
                'type'         => $childProduct->type,
            ],
        ],

        OrderPayment::class => [
            [
                'method'       => $paymentMethod,
                'method_title' => $methodTitle,
            ],
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
});

it('should place a configurable product order for a guest user and send email to the guest user', function () {
    // Arrange
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

    CartShippingRate::factory()->create([
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

    cart()->putCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertCart($cart);

    $this->assertCartItem($cartItem);

    $this->assertCartPayment($cartPayment);

    $this->assertAddress($cartBillingAddress);

    $this->assertAddress($cartShippingAddress);

    $cartBillingAddress->address_type = 'order_billing';

    $this->assertAddress($cartBillingAddress);

    $cartShippingAddress->address_type = 'order_shipping';

    $this->assertAddress($cartShippingAddress);

    $this->assertModelWise([
        CartPayment::class => [
            [
                'cart_id' => $cart->id,
                'method'  => $paymentMethod,
            ],
        ],

        Order::class => [
            [
                'shipping_method'      => 'free_free',
                'grand_total'          => $childProduct->price,
                'cart_id'              => $cart->id,
                'status'               => Order::STATUS_PENDING,
                'channel_name'         => core()->getCurrentChannel()->name,
                'is_guest'             => 1,
                'channel_id'           => core()->getCurrentChannel()->id,
                'customer_email'       => $cart->customer_email,
                'customer_first_name'  => $cart->customer_first_name,
                'customer_last_name'   => $cart->customer_last_name,
                'shipping_method'      => $cart->shipping_method,
                'total_item_count'     => 1,
                'total_qty_ordered'    => 1,
                'base_currency_code'   => core()->getBaseCurrencyCode(),
                'grand_total'          => $cart->grand_total,
                'base_grand_total'     => $cart->base_grand_total,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => $cartItem->quantity,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'product_id'   => $childProduct->id,
                'price'        => $childProduct->price,
                'type'         => $childProduct->type,
            ],
        ],

        OrderPayment::class => [
            [
                'method'       => $paymentMethod,
                'method_title' => $methodTitle,
            ],
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

    Mail::assertQueued(AdminOrderCreatedNotification::class);

    Mail::assertQueued(ShopOrderCreatedNotification::class);

    Mail::assertQueuedCount(2);
});

it('should place a configurable product order for a customer', function () {
    // Arrange
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

    CartShippingRate::factory()->create([
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

    $this->assertCart($cart);

    $this->assertCartItem($cartItem);

    $this->assertCartPayment($cartPayment);

    $this->assertAddress($cartBillingAddress);

    $this->assertAddress($cartShippingAddress);

    $cartBillingAddress->address_type = 'order_billing';

    $this->assertAddress($cartBillingAddress);

    $cartShippingAddress->address_type = 'order_shipping';

    $this->assertAddress($cartShippingAddress);

    $this->assertModelWise([
        Order::class => [
            [
                'shipping_method'      => 'free_free',
                'grand_total'          => $childProduct->price,
                'cart_id'              => $cart->id,
                'status'               => Order::STATUS_PENDING,
                'channel_name'         => core()->getCurrentChannel()->name,
                'is_guest'             => 0,
                'customer_id'          => $customer->id,
                'channel_id'           => core()->getCurrentChannel()->id,
                'customer_email'       => $cart->customer_email,
                'customer_first_name'  => $cart->customer_first_name,
                'customer_last_name'   => $cart->customer_last_name,
                'shipping_method'      => $cart->shipping_method,
                'total_item_count'     => 1,
                'total_qty_ordered'    => 1,
                'base_currency_code'   => core()->getBaseCurrencyCode(),
                'grand_total'          => $cart->grand_total,
                'base_grand_total'     => $cart->base_grand_total,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => $cartItem->quantity,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'product_id'   => $childProduct->id,
                'price'        => $childProduct->price,
                'type'         => $childProduct->type,
            ],
        ],

        OrderPayment::class => [
            [
                'method'       => $paymentMethod,
                'method_title' => $methodTitle,
            ],
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
});

it('should place a configurable product order for a customer and send email to the user', function () {
    // Arrange
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

    CartShippingRate::factory()->create([
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

    $this->assertCart($cart);

    $this->assertCartItem($cartItem);

    $this->assertCartPayment($cartPayment);

    $this->assertAddress($cartBillingAddress);

    $this->assertAddress($cartShippingAddress);

    $cartBillingAddress->address_type = 'order_billing';

    $this->assertAddress($cartBillingAddress);

    $cartShippingAddress->address_type = 'order_shipping';

    $this->assertAddress($cartShippingAddress);

    $this->assertModelWise([
        Order::class => [
            [
                'shipping_method'      => 'free_free',
                'grand_total'          => $childProduct->price,
                'cart_id'              => $cart->id,
                'status'               => Order::STATUS_PENDING,
                'channel_name'         => core()->getCurrentChannel()->name,
                'is_guest'             => 0,
                'customer_id'          => $customer->id,
                'channel_id'           => core()->getCurrentChannel()->id,
                'customer_email'       => $cart->customer_email,
                'customer_first_name'  => $cart->customer_first_name,
                'customer_last_name'   => $cart->customer_last_name,
                'shipping_method'      => $cart->shipping_method,
                'total_item_count'     => 1,
                'total_qty_ordered'    => 1,
                'base_currency_code'   => core()->getBaseCurrencyCode(),
                'grand_total'          => $cart->grand_total,
                'base_grand_total'     => $cart->base_grand_total,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => $cartItem->quantity,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'product_id'   => $childProduct->id,
                'price'        => $childProduct->price,
                'type'         => $childProduct->type,
            ],
        ],

        OrderPayment::class => [
            [
                'method'       => $paymentMethod,
                'method_title' => $methodTitle,
            ],
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

    Mail::assertQueued(AdminOrderCreatedNotification::class);

    Mail::assertQueued(ShopOrderCreatedNotification::class);

    Mail::assertQueuedCount(2);
});

it('should place a virtual product order for a guest user', function () {
    // Arrange
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

    cart()->putCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertCart($cart);

    $this->assertCartItem($cartItem);

    $this->assertCartPayment($cartPayment);

    $this->assertAddress($cartBillingAddress);

    $cartBillingAddress->address_type = 'order_billing';

    $this->assertAddress($cartBillingAddress);

    $this->assertModelWise([
        Order::class => [
            [
                'status'               => Order::STATUS_PENDING,
                'channel_name'         => core()->getCurrentChannel()->name,
                'is_guest'             => 1,
                'customer_id'          => null,
                'channel_id'           => core()->getCurrentChannel()->id,
                'customer_email'       => $cart->customer_email,
                'customer_first_name'  => $cart->customer_first_name,
                'customer_last_name'   => $cart->customer_last_name,
                'shipping_method'      => null,
                'total_item_count'     => 1,
                'total_qty_ordered'    => 1,
                'base_currency_code'   => core()->getBaseCurrencyCode(),
                'grand_total'          => $cart->grand_total,
                'base_grand_total'     => $cart->base_grand_total,
                'cart_id'              => $cart->id,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => $quantity = $cartItem->quantity,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'price'        => $price,
                'type'         => $product->type,
                'product_id'   => $product->id,
            ],
        ],

        OrderPayment::class => [
            [
                'method' => $paymentMethod,
            ],
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $quantity,
                'product_id' => $product->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $product->inventory_source_qty(1) - $quantity,
                'product_id' => $product->id,
            ],
        ],
    ]);
});

it('should place a virtual product order for a guest user and send email to the guest user', function () {
    // Arrange
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

    cart()->putCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertCart($cart);

    $this->assertCartItem($cartItem);

    $this->assertCartPayment($cartPayment);

    $this->assertAddress($cartBillingAddress);

    $cartBillingAddress->address_type = 'order_billing';

    $this->assertAddress($cartBillingAddress);

    $this->assertModelWise([
        Order::class => [
            [
                'status'               => Order::STATUS_PENDING,
                'channel_name'         => core()->getCurrentChannel()->name,
                'is_guest'             => 1,
                'customer_id'          => null,
                'channel_id'           => core()->getCurrentChannel()->id,
                'customer_email'       => $cart->customer_email,
                'customer_first_name'  => $cart->customer_first_name,
                'customer_last_name'   => $cart->customer_last_name,
                'shipping_method'      => null,
                'total_item_count'     => 1,
                'total_qty_ordered'    => 1,
                'base_currency_code'   => core()->getBaseCurrencyCode(),
                'grand_total'          => $cart->grand_total,
                'base_grand_total'     => $cart->base_grand_total,
                'cart_id'              => $cart->id,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => $quantity = $cartItem->quantity,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'price'        => $price,
                'type'         => $product->type,
                'product_id'   => $product->id,
            ],
        ],

        OrderPayment::class => [
            [
                'method' => $paymentMethod,
            ],
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $quantity,
                'product_id' => $product->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $product->inventory_source_qty(1) - $quantity,
                'product_id' => $product->id,
            ],
        ],
    ]);

    Mail::assertQueued(AdminOrderCreatedNotification::class);

    Mail::assertQueued(ShopOrderCreatedNotification::class);

    Mail::assertQueuedCount(2);
});

it('should place a virtual product order for a customer', function () {
    // Arrange
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

    $this->assertCart($cart);

    $this->assertCartItem($cartItem);

    $this->assertCartPayment($cartPayment);

    $this->assertAddress($cartBillingAddress);

    $cartBillingAddress->address_type = 'order_billing';

    $this->assertAddress($cartBillingAddress);

    $this->assertModelWise([
        Order::class => [
            [
                'status'               => Order::STATUS_PENDING,
                'channel_name'         => core()->getCurrentChannel()->name,
                'is_guest'             => 0,
                'customer_id'          => $customer->id,
                'channel_id'           => core()->getCurrentChannel()->id,
                'customer_email'       => $cart->customer_email,
                'customer_first_name'  => $cart->customer_first_name,
                'customer_last_name'   => $cart->customer_last_name,
                'shipping_method'      => $cart->shipping_method,
                'total_item_count'     => 1,
                'total_qty_ordered'    => 1,
                'base_currency_code'   => core()->getBaseCurrencyCode(),
                'grand_total'          => $cart->grand_total,
                'base_grand_total'     => $cart->base_grand_total,
                'cart_id'              => $cart->id,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => $quantity = $cartItem->quantity,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'price'        => $price,
                'type'         => $product->type,
                'product_id'   => $product->id,
            ],
        ],

        OrderPayment::class => [
            [
                'method' => $paymentMethod,
            ],
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $quantity,
                'product_id' => $product->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $product->inventory_source_qty(1) - $quantity,
                'product_id' => $product->id,
            ],
        ],
    ]);
});

it('should place a virtual product order for a customer and send email to the user', function () {
    // Arrange
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

    $this->assertCart($cart);

    $this->assertCartItem($cartItem);

    $this->assertCartPayment($cartPayment);

    $this->assertAddress($cartBillingAddress);

    $cartBillingAddress->address_type = 'order_billing';

    $this->assertAddress($cartBillingAddress);

    $this->assertModelWise([
        Order::class => [
            [
                'status'               => Order::STATUS_PENDING,
                'channel_name'         => core()->getCurrentChannel()->name,
                'is_guest'             => 0,
                'customer_id'          => $customer->id,
                'channel_id'           => core()->getCurrentChannel()->id,
                'customer_email'       => $cart->customer_email,
                'customer_first_name'  => $cart->customer_first_name,
                'customer_last_name'   => $cart->customer_last_name,
                'shipping_method'      => $cart->shipping_method,
                'total_item_count'     => 1,
                'total_qty_ordered'    => 1,
                'base_currency_code'   => core()->getBaseCurrencyCode(),
                'grand_total'          => $cart->grand_total,
                'base_grand_total'     => $cart->base_grand_total,
                'cart_id'              => $cart->id,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => $quantity = $cartItem->quantity,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'price'        => $price,
                'type'         => $product->type,
                'product_id'   => $product->id,
            ],
        ],

        OrderPayment::class => [
            [
                'method' => $paymentMethod,
            ],
        ],

        ProductOrderedInventory::class => [
            [
                'qty'        => $quantity,
                'product_id' => $product->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $product->inventory_source_qty(1) - $quantity,
                'product_id' => $product->id,
            ],
        ],
    ]);

    Mail::assertQueued(AdminOrderCreatedNotification::class);

    Mail::assertQueued(ShopOrderCreatedNotification::class);

    Mail::assertQueuedCount(2);
});

it('should place a downloadable product order for a customer', function () {
    // Arrange
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

    $this->assertCart($cart);

    $this->assertCartItem($cartItem);

    $this->assertCartPayment($cartPayment);

    $this->assertAddress($cartBillingAddress);

    $cartBillingAddress->address_type = 'order_billing';

    $this->assertAddress($cartBillingAddress);

    $this->assertModelWise([
        Order::class => [
            [
                'status'               => Order::STATUS_PENDING,
                'channel_name'         => core()->getCurrentChannel()->name,
                'is_guest'             => 0,
                'customer_id'          => $customer->id,
                'channel_id'           => core()->getCurrentChannel()->id,
                'customer_email'       => $cart->customer_email,
                'customer_first_name'  => $cart->customer_first_name,
                'customer_last_name'   => $cart->customer_last_name,
                'shipping_method'      => $cart->shipping_method,
                'total_item_count'     => 1,
                'total_qty_ordered'    => 1,
                'base_currency_code'   => core()->getBaseCurrencyCode(),
                'grand_total'          => $cart->grand_total,
                'base_grand_total'     => $cart->base_grand_total,
                'cart_id'              => $cart->id,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => $cartItem->quantity,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'price'        => $price,
                'type'         => $product->type,
                'product_id'   => $product->id,
            ],
        ],

        OrderPayment::class => [
            [
                'method'  => $paymentMethod,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => 0,
                'product_id' => $product->id,
            ],
        ],
    ]);
});

it('should place a downloadable product order for a customer and send email to the user', function () {
    // Arrange
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

    $this->assertCart($cart);

    $this->assertCartItem($cartItem);

    $this->assertCartPayment($cartPayment);

    $this->assertAddress($cartBillingAddress);

    $cartBillingAddress->address_type = 'order_billing';

    $this->assertAddress($cartBillingAddress);

    $this->assertModelWise([
        Order::class => [
            [
                'status'               => Order::STATUS_PENDING,
                'channel_name'         => core()->getCurrentChannel()->name,
                'is_guest'             => 0,
                'customer_id'          => $customer->id,
                'channel_id'           => core()->getCurrentChannel()->id,
                'customer_email'       => $cart->customer_email,
                'customer_first_name'  => $cart->customer_first_name,
                'customer_last_name'   => $cart->customer_last_name,
                'shipping_method'      => $cart->shipping_method,
                'total_item_count'     => 1,
                'total_qty_ordered'    => 1,
                'base_currency_code'   => core()->getBaseCurrencyCode(),
                'grand_total'          => $cart->grand_total,
                'base_grand_total'     => $cart->base_grand_total,
                'cart_id'              => $cart->id,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => $cartItem->quantity,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'price'        => $price,
                'type'         => $product->type,
                'product_id'   => $product->id,
            ],
        ],

        OrderPayment::class => [
            [
                'method'  => $paymentMethod,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => 0,
                'product_id' => $product->id,
            ],
        ],
    ]);

    Mail::assertQueued(AdminOrderCreatedNotification::class);

    Mail::assertQueued(ShopOrderCreatedNotification::class);

    Mail::assertQueuedCount(2);
});

it('should not return the cash on delivery payment method if product is downloadable', function () {
    // Arrange
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
            'address'          => [fake()->address()],
            'company_name'     => fake()->company(),
            'first_name'       => fake()->firstName(),
            'last_name'        => fake()->lastName(),
            'email'            => fake()->email(),
            'country'          => fake()->countryCode(),
            'state'            => fake()->state(),
            'city'             => fake()->city(),
            'postcode'         => rand(111111, 999999),
            'phone'            => fake()->e164PhoneNumber(),
            'use_for_shipping' => false,
        ],

        'shipping' => [
            'address'          => [fake()->address()],
            'company_name'     => fake()->company(),
            'first_name'       => fake()->firstName(),
            'last_name'        => fake()->lastName(),
            'email'            => fake()->email(),
            'country'          => fake()->countryCode(),
            'state'            => fake()->state(),
            'city'             => fake()->city(),
            'postcode'         => rand(111111, 999999),
            'phone'            => fake()->e164PhoneNumber(),
        ],
    ])
        ->assertOk()
        ->assertJsonPath('redirect', false)
        ->assertJsonCount(3, 'data.payment_methods')
        ->assertDontSeeText('cashondelivery');
});

it('should not return the shipping methods if product is downloadable', function () {
    // Arrange
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
            'address'          => [fake()->address()],
            'company_name'     => fake()->company(),
            'first_name'       => fake()->firstName(),
            'last_name'        => fake()->lastName(),
            'email'            => fake()->email(),
            'country'          => fake()->countryCode(),
            'state'            => fake()->state(),
            'city'             => fake()->city(),
            'postcode'         => rand(111111, 999999),
            'phone'            => fake()->e164PhoneNumber(),
            'use_for_shipping' => false,
        ],

        'shipping' => [
            'address'          => [fake()->address()],
            'company_name'     => fake()->company(),
            'first_name'       => fake()->firstName(),
            'last_name'        => fake()->lastName(),
            'email'            => fake()->email(),
            'country'          => fake()->countryCode(),
            'state'            => fake()->state(),
            'city'             => fake()->city(),
            'postcode'         => rand(111111, 999999),
            'phone'            => fake()->e164PhoneNumber(),
        ],
    ])
        ->assertOk()
        ->assertJsonPath('redirect', false)
        ->assertJsonMissingPath('data.shippingMethods');
});

it('should not return the cash on delivery payment method if product is virtual', function () {
    // Arrange
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
            'address'          => [fake()->address()],
            'company_name'     => fake()->company(),
            'first_name'       => fake()->firstName(),
            'last_name'        => fake()->lastName(),
            'email'            => fake()->email(),
            'country'          => fake()->countryCode(),
            'state'            => fake()->state(),
            'city'             => fake()->city(),
            'postcode'         => rand(111111, 999999),
            'phone'            => fake()->e164PhoneNumber(),
            'use_for_shipping' => fake()->boolean(),
        ],

        'shipping' => [
            'address'      => [fake()->address()],
            'company_name' => fake()->company(),
            'first_name'   => fake()->firstName(),
            'last_name'    => fake()->lastName(),
            'email'        => fake()->email(),
            'country'      => fake()->countryCode(),
            'state'        => fake()->state(),
            'city'         => fake()->city(),
            'postcode'     => rand(111111, 999999),
            'phone'        => fake()->e164PhoneNumber(),
        ],
    ])
        ->assertOk()
        ->assertJsonPath('redirect', false)
        ->assertJsonCount(3, 'data.payment_methods')
        ->assertDontSeeText('cashondelivery');
});

it('should not return the shipping methods if product is virtual', function () {
    // Arrange
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
            'address'          => [fake()->address()],
            'company_name'     => fake()->company(),
            'first_name'       => fake()->firstName(),
            'last_name'        => fake()->lastName(),
            'email'            => fake()->email(),
            'country'          => fake()->countryCode(),
            'state'            => fake()->state(),
            'city'             => fake()->city(),
            'postcode'         => rand(111111, 999999),
            'phone'            => fake()->e164PhoneNumber(),
            'use_for_shipping' => fake()->boolean(),
        ],

        'shipping' => [
            'address'      => [fake()->address()],
            'company_name' => fake()->company(),
            'first_name'   => fake()->firstName(),
            'last_name'    => fake()->lastName(),
            'email'        => fake()->email(),
            'country'      => fake()->countryCode(),
            'state'        => fake()->state(),
            'city'         => fake()->city(),
            'postcode'     => rand(111111, 999999),
            'phone'        => fake()->e164PhoneNumber(),
        ],
    ])
        ->assertOk()
        ->assertJsonPath('redirect', false)
        ->assertJsonMissingPath('data.shippingMethods');
});

it('should place order with two products with simple and configurable product type', function () {
    // Arrange
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

    CartShippingRate::factory()->create([
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

    $this->assertCart($cart);

    $this->assertCartItem($configurableProductCartItem);

    $this->assertCartItem($simpleProductCartItem);

    $this->assertCartPayment($cartPayment);

    $this->assertAddress($cartBillingAddress);

    $this->assertAddress($cartShippingAddress);

    $cartBillingAddress->address_type = 'order_billing';

    $this->assertAddress($cartBillingAddress);

    $cartShippingAddress->address_type = 'order_shipping';

    $this->assertAddress($cartShippingAddress);

    $this->assertModelWise([
        Order::class => [
            [
                'shipping_method'      => 'free_free',
                'grand_total'          => $childProduct->price + $simpleProduct->price,
                'cart_id'              => $cart->id,
                'status'               => Order::STATUS_PENDING,
                'channel_name'         => core()->getCurrentChannel()->name,
                'is_guest'             => 0,
                'customer_id'          => $customer->id,
                'channel_id'           => core()->getCurrentChannel()->id,
                'customer_email'       => $cart->customer_email,
                'customer_first_name'  => $cart->customer_first_name,
                'customer_last_name'   => $cart->customer_last_name,
                'shipping_method'      => $cart->shipping_method,
                'total_item_count'     => 2,
                'total_qty_ordered'    => 2,
                'base_currency_code'   => core()->getBaseCurrencyCode(),
                'base_grand_total'     => $cart->base_grand_total,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => $quantity = $configurableProductCartItem->quantity,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'product_id'   => $configurableProduct->id,
                'price'        => $childProduct->price,
                'type'         => $configurableProduct->type,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => $quantity = $configurableProductCartItem->quantity,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'product_id'   => $simpleProduct->id,
                'price'        => $simpleProduct->price,
                'type'         => $simpleProduct->type,
            ],
        ],

        OrderPayment::class => [
            [
                'method' => $paymentMethod,
            ],
        ],

        ProductOrderedInventory::class => [
            [
                'product_id' => $childProduct->id,
                'qty'        => $quantity,
            ],
        ],

        ProductOrderedInventory::class => [
            [
                'product_id' => $simpleProduct->id,
                'qty'        => $quantity,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $childProduct->inventory_source_qty(1) - $quantity,
                'product_id' => $childProduct->id,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $simpleProduct->inventory_source_qty(1) - $quantity,
                'product_id' => $simpleProduct->id,
            ],
        ],
    ]);
});

it('should place order with two products with simple and grouped product type', function () {
    // Arrange
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

    CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    CartShippingRate::factory()->create([
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

    $this->assertCart($cart);

    $this->assertCartItem($simpleProductCartItem);

    $this->assertCartItems($bundleProductCartItems);

    $this->assertAddress($cartBillingAddress);

    $this->assertAddress($cartShippingAddress);

    $cartBillingAddress->address_type = 'order_billing';

    $this->assertAddress($cartBillingAddress);

    $cartShippingAddress->address_type = 'order_shipping';

    $this->assertAddress($cartShippingAddress);

    $this->assertModelWise([
        Order::class => [
            [
                'shipping_method'      => 'free_free',
                'grand_total'          => array_sum($data['grand_total']),
                'cart_id'              => $cart->id,
                'status'               => Order::STATUS_PENDING,
                'total_item_count'     => 5,
                'total_qty_ordered'    => array_sum($data['qty']),
                'customer_id'          => $customer->id,
                'status'               => Order::STATUS_PENDING,
                'channel_name'         => core()->getCurrentChannel()->name,
                'channel_id'           => core()->getCurrentChannel()->id,
                'customer_email'       => $cart->customer_email,
                'customer_first_name'  => $cart->customer_first_name,
                'customer_last_name'   => $cart->customer_last_name,
                'base_currency_code'   => core()->getBaseCurrencyCode(),
            ],
        ],

        CartPayment::class => [
            [
                'cart_id' => $cart->id,
                'method'  => $paymentMethod,
            ],
        ],

        OrderPayment::class => [
            [
                'method' => $paymentMethod,
            ],
        ],
    ]);

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

            OrderItem::class => [
                [
                    'qty_ordered'  => $bundleProduct->qty,
                    'qty_shipped'  => 0,
                    'qty_invoiced' => 0,
                    'qty_canceled' => 0,
                    'qty_refunded' => 0,
                    'product_id'   => $bundleProduct->associated_product->id,
                    'price'        => $bundleProduct->associated_product->price,
                    'type'         => $bundleProduct->associated_product->type,
                ],
            ],
        ]);
    }

    $this->assertModelWise([
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

        OrderItem::class => [
            [
                'qty_ordered'  => 1,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'product_id'   => $simpleProduct->id,
                'price'        => $simpleProduct->price,
                'type'         => $simpleProduct->type,
            ],
        ],
    ]);
});

it('should place order with two products with simple and downloadable product type', function () {
    // Arrange
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

    CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartShippingAddress->id,
    ]);

    CartPayment::factory()->create([
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

    $this->assertCart($cart);

    $this->assertCartItem($simpleProductCartItem);

    $this->assertCartItem($downloadableProductCartItem);

    $this->assertAddress($cartBillingAddress);

    $this->assertAddress($cartShippingAddress);

    $cartBillingAddress->address_type = 'order_billing';

    $this->assertAddress($cartBillingAddress);

    $cartShippingAddress->address_type = 'order_shipping';

    $this->assertAddress($cartShippingAddress);

    $this->assertModelWise([
        Order::class => [
            [
                'status'               => Order::STATUS_PENDING,
                'grand_total'          => $downloadableProductCartItem->price + $simpleProductCartItem->price,
                'cart_id'              => $cart->id,
                'channel_name'         => core()->getCurrentChannel()->name,
                'is_guest'             => 0,
                'customer_id'          => $customer->id,
                'channel_id'           => core()->getCurrentChannel()->id,
                'customer_email'       => $cart->customer_email,
                'customer_first_name'  => $cart->customer_first_name,
                'customer_last_name'   => $cart->customer_last_name,
                'shipping_method'      => $cart->shipping_method,
                'total_item_count'     => 2,
                'total_qty_ordered'    => 2,
                'base_currency_code'   => core()->getBaseCurrencyCode(),
                'cart_id'              => $cart->id,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => 1,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'price'        => $downloadableProduct->price,
                'type'         => $downloadableProduct->type,
                'product_id'   => $downloadableProduct->id,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => 1,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'price'        => $simpleProduct->price,
                'type'         => $simpleProduct->type,
                'product_id'   => $simpleProduct->id,
            ],
        ],

        OrderPayment::class => [
            [
                'method' => $paymentMethod,
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
