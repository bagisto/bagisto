<?php

use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\ProductInventoryIndex;
use Webkul\Product\Models\ProductOrderedInventory;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;

use function Pest\Laravel\postJson;

it('should fails the certain validation error when store the guest user address for cart billing/shipping for guest user', function () {
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

    CartItem::factory()->create([
        'quantity'          => 1,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'weight'            => 1,
        'total_weight'      => 1,
        'base_total_weight' => 1,
        'cart_id'           => $cartId = Cart::factory()->create([
            'channel_id'            => core()->getCurrentChannel()->id,
            'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
            'base_currency_code'    => $baseCurrencyCode,
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'items_count'           => 1,
            'items_qty'             => 1,
            'grand_total'           => $price = $product->price,
            'base_grand_total'      => $price,
            'sub_total'	            => $price,
            'base_sub_total'        => $price,
            'is_guest'              => 1,
        ])->id,
    ]);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cartId;

    session()->put('cart', $cartTemp);

    // Act and Assert
    postJson(route('shop.checkout.onepage.addresses.store'))
        ->assertJsonValidationErrorFor('billing.first_name')
        ->assertJsonValidationErrorFor('billing.last_name')
        ->assertJsonValidationErrorFor('billing.email')
        ->assertJsonValidationErrorFor('billing.address1')
        ->assertJsonValidationErrorFor('billing.city')
        ->assertJsonValidationErrorFor('billing.phone')
        ->assertUnprocessable();
});

it('should store the guest user address for cart billing/shipping for guest user', function () {
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

    CartItem::factory()->create([
        'quantity'          => 1,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'weight'            => 1,
        'total_weight'      => 1,
        'base_total_weight' => 1,
        'cart_id'           => $cartId = Cart::factory()->create([
            'channel_id'            => core()->getCurrentChannel()->id,
            'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
            'base_currency_code'    => $baseCurrencyCode,
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'items_count'           => 1,
            'items_qty'             => 1,
            'grand_total'           => $price = $product->price,
            'base_grand_total'      => $price,
            'sub_total'	            => $price,
            'base_sub_total'        => $price,
            'is_guest'              => 1,
        ])->id,
    ]);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cartId;

    session()->put('cart', $cartTemp);

    // Act and Assert
    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            'address1'         => [fake()->address()],
            'isSaved'          => false,
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
            'address1'         => [fake()->address()],
            'isSaved'          => false,
            'company_name'     => fake()->company(),
            'first_name'       => fake()->firstName(),
            'last_name'        => fake()->lastName(),
            'email'            => fake()->email(),
            'country'          => fake()->countryCode(),
            'state'            => fake()->state(),
            'city'             => fake()->city(),
            'postcode'         => rand(111111, 999999),
            'phone'            => fake()->e164PhoneNumber(),
            'use_for_shipping' => true,
        ],
    ])
        ->assertOk()
        ->assertJsonPath('redirect', false)
        ->assertJsonPath('data.shippingMethods.flatrate.carrier_title', 'Flat Rate')
        ->assertJsonPath('data.shippingMethods.flatrate.rates.0.carrier', 'flatrate')
        ->assertJsonPath('data.shippingMethods.free.carrier_title', 'Free Shipping')
        ->assertJsonPath('data.shippingMethods.free.rates.0.carrier', 'free')
        ->assertJsonPath('data.shippingMethods.free.rates.0.carrier_title', 'Free Shipping');
});

it('should fails the validation error when shipping method not providing when store the shipping method', function () {
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

    CartItem::factory()->create([
        'quantity'          => 1,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'weight'            => 1,
        'total_weight'      => 1,
        'base_total_weight' => 1,
        'cart_id'           => $cartId = Cart::factory()->create([
            'channel_id'            => core()->getCurrentChannel()->id,
            'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
            'base_currency_code'    => $baseCurrencyCode,
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'items_count'           => 1,
            'items_qty'             => 1,
            'grand_total'           => $price = $product->price,
            'base_grand_total'      => $price,
            'sub_total'	            => $price,
            'base_sub_total'        => $price,
            'is_guest'              => 1,
        ])->id,
    ]);

    CustomerAddress::factory()->create(['cart_id' => $cartId, 'address_type' => 'cart_billing']);
    CustomerAddress::factory()->create(['cart_id' => $cartId, 'address_type' => 'cart_shipping']);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cartId;

    session()->put('cart', $cartTemp);

    // Act and Assert
    postJson(route('shop.checkout.onepage.shipping_methods.store'))
        ->assertJsonValidationErrorFor('shipping_method')
        ->assertUnprocessable();
});

it('should store the shipping method', function () {
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

    CartItem::factory()->create([
        'quantity'          => 1,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'weight'            => 1,
        'total_weight'      => 1,
        'base_total_weight' => 1,
        'cart_id'           => $cartId = Cart::factory()->create([
            'channel_id'            => core()->getCurrentChannel()->id,
            'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
            'base_currency_code'    => $baseCurrencyCode,
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'items_count'           => 1,
            'items_qty'             => 1,
            'grand_total'           => $price = $product->price,
            'base_grand_total'      => $price,
            'sub_total'	            => $price,
            'base_sub_total'        => $price,
            'is_guest'              => 1,
        ])->id,
    ]);

    CustomerAddress::factory()->create(['cart_id' => $cartId, 'address_type' => 'cart_billing']);
    CustomerAddress::factory()->create(['cart_id' => $cartId, 'address_type' => 'cart_shipping']);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cartId;

    session()->put('cart', $cartTemp);

    // Act and Assert
    postJson(route('shop.checkout.onepage.shipping_methods.store'), [
        'shipping_method' => 'free_free',
    ])
        ->assertOk()
        ->assertJsonPath('payment_methods.0.method', 'paypal_smart_button')
        ->assertJsonPath('payment_methods.1.method', 'cashondelivery')
        ->assertJsonPath('payment_methods.2.method', 'moneytransfer')
        ->assertJsonPath('payment_methods.3.method', 'paypal_standard');
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

    CartItem::factory()->create([
        'quantity'          => 1,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'weight'            => 1,
        'total_weight'      => 1,
        'base_total_weight' => 1,
        'cart_id'           => $cartId = Cart::factory()->create([
            'channel_id'            => core()->getCurrentChannel()->id,
            'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
            'base_currency_code'    => $baseCurrencyCode,
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'items_count'           => 1,
            'items_qty'             => 1,
            'grand_total'           => $price = $product->price,
            'base_grand_total'      => $price,
            'sub_total'	            => $price,
            'base_sub_total'        => $price,
            'is_guest'              => 1,
            'shipping_method'       => 'free_free',
        ])->id,
    ]);

    CustomerAddress::factory()->create(['cart_id' => $cartId, 'address_type' => 'cart_billing']);
    CustomerAddress::factory()->create(['cart_id' => $cartId, 'address_type' => 'cart_shipping']);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cartId;

    session()->put('cart', $cartTemp);

    // Act and Assert
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

    $cartItem = CartItem::factory()->create([
        'quantity'          => 1,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'weight'            => 1,
        'total_weight'      => 1,
        'base_total_weight' => 1,
        'cart_id'           => $cartId = Cart::factory()->create([
            'channel_id'            => core()->getCurrentChannel()->id,
            'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
            'base_currency_code'    => $baseCurrencyCode,
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'items_count'           => 1,
            'items_qty'             => 1,
            'grand_total'           => $price = $product->price,
            'base_grand_total'      => $price,
            'sub_total'	            => $price,
            'base_sub_total'        => $price,
            'is_guest'              => 1,
            'shipping_method'       => 'free_free',
        ])->id,
    ]);

    $billingAddress = CustomerAddress::factory()->create(['cart_id' => $cartId, 'address_type' => 'cart_billing']);
    $shippingAddress = CustomerAddress::factory()->create(['cart_id' => $cartId, 'address_type' => 'cart_shipping']);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cartId;

    session()->put('cart', $cartTemp);

    // Act and Assert
    postJson(route('shop.checkout.onepage.payment_methods.store'), [
        'payment' => [
            'method'       => 'cashondelivery',
            'method_title' => 'Cash On Delivery',
            'description'  => 'Cash On Delivery',
            'sort'         => 1,
        ],
    ])
        ->assertOk()
        ->assertJsonPath('cart.id', $cartId)
        ->assertJsonPath('cart.items_count', 1)
        ->assertJsonPath('cart.items.0.id', $cartItem->id)
        ->assertJsonPath('cart.items.0.type', $product->type)
        ->assertJsonPath('cart.haveStockableItems', true)
        ->assertJsonPath('cart.payment_method', 'Cash On Delivery')
        ->assertJsonPath('cart.billing_address.id', $billingAddress->id)
        ->assertJsonPath('cart.billing_address.address_type', 'cart_billing')
        ->assertJsonPath('cart.billing_address.cart_id', $cartId)
        ->assertJsonPath('cart.shipping_address.id', $shippingAddress->id)
        ->assertJsonPath('cart.shipping_address.address_type', 'cart_shipping')
        ->assertJsonPath('cart.shipping_address.cart_id', $cartId);
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

    $cart = Cart::factory()->create([
        'channel_id'            => core()->getCurrentChannel()->id,
        'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
        'base_currency_code'    => $baseCurrencyCode,
        'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
        'cart_currency_code'    => core()->getCurrentCurrencyCode(),
        'items_count'           => 1,
        'items_qty'             => 1,
        'grand_total'           => $price = $product->price,
        'base_grand_total'      => $price,
        'sub_total'	            => $price,
        'base_sub_total'        => $price,
        'is_guest'              => 1,
        'shipping_method'       => 'free_free',
    ]);

    $cartItem = CartItem::factory()->create([
        'quantity'          => 1,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'weight'            => 1,
        'total_weight'      => 1,
        'base_total_weight' => 1,
        'cart_id'           => $cart->id,
    ]);

    $billingAddress = CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_billing']);
    $shippingAddress = CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_shipping']);

    CartShippingRate::factory()->create([
        'carrier'               => 'free',
        'carrier_title'         => 'Free shipping',
        'method'                => 'free_free',
        'method_title'          => 'Free Shipping',
        'method_description'    => 'Free Shipping',
        'cart_address_id'       => $shippingAddress->id,
    ]);

    $cartPayment = new CartPayment;
    $cartPayment->method = $paymentMethod = 'cashondelivery';
    $cartPayment->method_title = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title');
    $cartPayment->cart_id = $cart->id;
    $cartPayment->save();

    $cartTemp = new \stdClass();
    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    // Act and Assert
    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertModelWise([
        Order::class => [
            [
                'status'          => Order::STATUS_PENDING,
                'shipping_method' => 'free_free',
                'grand_total'     => $price,
                'cart_id'         => $cart->id,
            ],
        ],

        CustomerAddress::class => [
            [
                'address_type' => $billingAddress->address_type,
                'cart_id'      => $cart->id,
            ],
        ],

        CustomerAddress::class => [
            [
                'address_type' => $shippingAddress->address_type,
                'cart_id'      => $cart->id,
            ],
        ],

        Cart::class => [
            [
                'is_active' => 0,
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

        CartPayment::class => [
            [
                'method'  => $paymentMethod,
                'cart_id' => $cart->id,
            ],
        ],

        OrderPayment::class => [
            [
                'method'   => $paymentMethod,
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
        'channel_id'            => core()->getCurrentChannel()->id,
        'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
        'base_currency_code'    => $baseCurrencyCode,
        'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
        'cart_currency_code'    => core()->getCurrentCurrencyCode(),
        'items_count'           => 1,
        'items_qty'             => 1,
        'grand_total'           => $price = $product->price,
        'base_grand_total'      => $price,
        'sub_total'	            => $price,
        'base_sub_total'        => $price,
        'shipping_method'       => 'free_free',
        'customer_id'           => $customer->id,
        'is_active'             => 1,
        'customer_email'        => $customer->email,
        'customer_first_name'   => $customer->first_name,
        'customer_last_name'    => $customer->last_name,
    ]);

    $cartItem = CartItem::factory()->create([
        'quantity'          => 1,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'weight'            => 1,
        'total_weight'      => 1,
        'base_total_weight' => 1,
        'cart_id'           => $cart->id,
    ]);

    $billingAddress = CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_billing']);
    $shippingAddress = CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_shipping']);

    CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $shippingAddress->id,
    ]);

    $cartPayment = new CartPayment;
    $cartPayment->method = $paymentMethod = 'cashondelivery';
    $cartPayment->method_title = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title');
    $cartPayment->cart_id = $cart->id;
    $cartPayment->save();

    $cartTemp = new \stdClass();
    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    // Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertModelWise([
        Cart::class => [
            [
                'is_active' => 0,
            ],
        ],

        CustomerAddress::class => [
            [
                'address_type' => $billingAddress->address_type,
                'cart_id'      => $cart->id,
            ],
        ],

        CustomerAddress::class => [
            [
                'address_type' => $shippingAddress->address_type,
                'cart_id'      => $cart->id,
            ],
        ],

        Order::class => [
            [
                'status'          => Order::STATUS_PENDING,
                'shipping_method' => 'free_free',
                'grand_total'     => $price,
                'cart_id'         => $cart->id,
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

        CartPayment::class => [
            [
                'method'  => $paymentMethod,
                'cart_id' => $cart->id,
            ],
        ],

        OrderPayment::class => [
            [
                'method'  => $paymentMethod,
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

    foreach ($product->super_attributes as $attribute) {
        foreach ($attribute->options as $option) {
            $super_attributes[$option->attribute_id] = $option->id;
        }
    }

    $cart = Cart::factory()->create([
        'channel_id'            => core()->getCurrentChannel()->id,
        'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
        'base_currency_code'    => $baseCurrencyCode,
        'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
        'cart_currency_code'    => core()->getCurrentCurrencyCode(),
        'items_count'           => 1,
        'items_qty'             => 1,
        'grand_total'           => $price = $product->price,
        'base_grand_total'      => $price,
        'sub_total'	            => $price,
        'base_sub_total'        => $price,
        'is_guest'              => 1,
        'shipping_method'       => 'free_free',
    ]);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    $childProduct = $product->variants()->first();

    $data = [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => $super_attributes ?? [],
    ];

    $cartProducts = $product->getTypeInstance()->prepareForCart($data);

    $parentCartItem = null;

    foreach ($cartProducts as $cartProduct) {
        $cartItem = cart()->getItemByProduct($cartProduct, $data);

        if (isset($cartProduct['parent_id'])) {
            $cartProduct['parent_id'] = $parentCartItem->id;
        }

        if (! $cartItem) {
            $cartItem = CartItem::factory()->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
        } else {
            if (
                isset($cartProduct['parent_id'])
                && $cartItem->parent_id !== $parentCartItem->id
            ) {
                $cartItem = CartItem::factory()->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
            } else {
                $cartItem = CartItem::find($cartItem->id);
                $cartItem->update(array_merge($cartProduct, ['cart_id' => $cart->id]));
            }
        }

        if (! $parentCartItem) {
            $parentCartItem = $cartItem;
        }
    }

    $billingAddress = CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_billing']);
    $shippingAddress = CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_shipping']);

    CartShippingRate::factory()->create([
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $shippingAddress->id,
        'carrier_title'      => 'Free shipping',
        'method_title'       => 'Free Shipping',
        'carrier'            => 'free',
        'method'             => 'free_free',
    ]);

    $cartPayment = new CartPayment;
    $cartPayment->method = $paymentMethod = 'cashondelivery';
    $cartPayment->method_title = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title');
    $cartPayment->cart_id = $cart->id;
    $cartPayment->save();

    // Act and Assert
    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertModelWise([
        Cart::class => [
            [
                'is_active' => 0,
            ],
        ],

        CustomerAddress::class => [
            [
                'address_type' => $billingAddress->address_type,
                'cart_id'      => $cart->id,
            ],
        ],

        CustomerAddress::class => [
            [
                'address_type' => $shippingAddress->address_type,
                'cart_id'      => $cart->id,
            ],
        ],

        Order::class => [
            [
                'shipping_method' => 'free_free',
                'grand_total'     => $childProduct->price,
                'cart_id'         => $cart->id,
                'status'          => Order::STATUS_PENDING,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => $quantity = $cartItem->quantity,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'product_id'   => $product->id,
                'price'        => $childProduct->price,
                'type'         => $product->type,
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
                'method'  => $paymentMethod,
            ],
        ],

        ProductOrderedInventory::class => [
            [
                'product_id' => $childProduct->id,
                'qty'        => $quantity,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $childProduct->inventory_source_qty(1) - $quantity,
                'product_id' => $childProduct->id,
            ],
        ],
    ]);
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

    foreach ($product->super_attributes as $attribute) {
        foreach ($attribute->options as $option) {
            $super_attributes[$option->attribute_id] = $option->id;
        }
    }

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'channel_id'            => core()->getCurrentChannel()->id,
        'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
        'base_currency_code'    => $baseCurrencyCode,
        'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
        'cart_currency_code'    => core()->getCurrentCurrencyCode(),
        'items_count'           => 1,
        'items_qty'             => 1,
        'grand_total'           => $price = $product->price,
        'base_grand_total'      => $price,
        'sub_total'	            => $price,
        'base_sub_total'        => $price,
        'shipping_method'       => 'free_free',
        'customer_id'           => $customer->id,
        'is_active'             => 1,
        'customer_email'        => $customer->email,
        'customer_first_name'   => $customer->first_name,
        'customer_last_name'    => $customer->last_name,
    ]);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    $childProduct = $product->variants()->first();

    $data = [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => $super_attributes ?? [],
    ];

    $cartProducts = $product->getTypeInstance()->prepareForCart($data);

    $parentCartItem = null;

    foreach ($cartProducts as $cartProduct) {
        $cartItem = cart()->getItemByProduct($cartProduct, $data);

        if (isset($cartProduct['parent_id'])) {
            $cartProduct['parent_id'] = $parentCartItem->id;
        }

        if (! $cartItem) {
            $cartItem = CartItem::factory()->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
        } else {
            if (
                isset($cartProduct['parent_id'])
                && $cartItem->parent_id !== $parentCartItem->id
            ) {
                $cartItem = CartItem::factory()->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
            } else {
                $cartItem = CartItem::find($cartItem->id);
                $cartItem->update(array_merge($cartProduct, ['cart_id' => $cart->id]));
            }
        }

        if (! $parentCartItem) {
            $parentCartItem = $cartItem;
        }
    }

    $billingAddress = CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_billing']);
    $shippingAddress = CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_shipping']);

    CartShippingRate::factory()->create([
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $shippingAddress->id,
        'carrier_title'      => 'Free shipping',
        'method_title'       => 'Free Shipping',
        'carrier'            => 'free',
        'method'             => 'free_free',
    ]);

    $cartPayment = new CartPayment;
    $cartPayment->method = $paymentMethod = 'cashondelivery';
    $cartPayment->method_title = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title');
    $cartPayment->cart_id = $cart->id;
    $cartPayment->save();

    // Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertModelWise([
        Cart::class => [
            [
                'is_active' => 0,
            ],
        ],

        CustomerAddress::class => [
            [
                'address_type' => $billingAddress->address_type,
                'cart_id'      => $cart->id,
            ],
        ],

        CustomerAddress::class => [
            [
                'address_type' => $shippingAddress->address_type,
                'cart_id'      => $cart->id,
            ],
        ],

        Order::class => [
            [
                'shipping_method' => 'free_free',
                'grand_total'     => $childProduct->price,
                'cart_id'         => $cart->id,
                'status'          => Order::STATUS_PENDING,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => $quantity = $cartItem->quantity,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'product_id'   => $product->id,
                'price'        => $childProduct->price,
                'type'         => $product->type,
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

        ProductOrderedInventory::class => [
            [
                'product_id' => $childProduct->id,
                'qty'        => $quantity,
            ],
        ],

        ProductInventoryIndex::class => [
            [
                'qty'        => $childProduct->inventory_source_qty(1) - $quantity,
                'product_id' => $childProduct->id,
            ],
        ],
    ]);
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

    $cartItem = CartItem::factory()->create([
        'base_total_weight' => 1,
        'total_weight'      => 1,
        'product_id'        => $product->id,
        'quantity'          => 1,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'weight'            => 1,
        'cart_id'           => $cart = Cart::factory()->create([
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
            'base_currency_code'    => $baseCurrencyCode,
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'base_grand_total'      => $price = $product->price,
            'base_sub_total'        => $price,
            'channel_id'            => core()->getCurrentChannel()->id,
            'items_count'           => 1,
            'items_qty'             => 1,
            'grand_total'           => $price,
            'sub_total'	            => $price,
            'is_guest'              => 1,
        ]),
    ]);

    CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_billing']);

    $cartPayment = new CartPayment;
    $cartPayment->method = $paymentMethod = 'cashondelivery';
    $cartPayment->method_title = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title');
    $cartPayment->cart_id = $cart->id;
    $cartPayment->save();

    $cartTemp = new \stdClass();
    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    // Act and Assert
    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertModelWise([
        Cart::class => [
            [
                'is_active' => 0,
            ],
        ],

        CustomerAddress::class => [
            [
                'address_type' => 'cart_billing',
                'cart_id'      => $cart->id,
            ],
        ],

        Order::class => [
            [
                'status'      => Order::STATUS_PENDING,
                'grand_total' => $price,
                'cart_id'     => $cart->id,
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

        CartPayment::class => [
            [
                'method'  => $paymentMethod,
                'cart_id' => $cart->id,
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

    $cartItem = CartItem::factory()->create([
        'base_total_weight' => 1,
        'total_weight'      => 1,
        'product_id'        => $product->id,
        'quantity'          => 1,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'weight'            => 1,
        'cart_id'           => $cart = Cart::factory()->create([
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
            'base_currency_code'    => $baseCurrencyCode,
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'base_grand_total'      => $price = $product->price,
            'base_sub_total'        => $price,
            'channel_id'            => core()->getCurrentChannel()->id,
            'items_count'           => 1,
            'items_qty'             => 1,
            'grand_total'           => $price,
            'sub_total'	            => $price,
            'customer_id'           => $customer->id,
            'is_active'             => 1,
            'customer_email'        => $customer->email,
            'customer_first_name'   => $customer->first_name,
            'customer_last_name'    => $customer->last_name,
        ]),
    ]);

    CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_billing']);

    $cartPayment = new CartPayment;
    $cartPayment->method = $paymentMethod = 'cashondelivery';
    $cartPayment->method_title = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title');
    $cartPayment->cart_id = $cart->id;
    $cartPayment->save();

    $cartTemp = new \stdClass();
    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    // Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertModelWise([
        Cart::class => [
            [
                'is_active' => 0,
            ],
        ],

        CustomerAddress::class => [
            [
                'address_type' => 'cart_billing',
                'cart_id'      => $cart->id,
            ],
        ],

        Order::class => [
            [
                'status'      => Order::STATUS_PENDING,
                'grand_total' => $price,
                'cart_id'     => $cart->id,
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

        CartPayment::class => [
            [
                'method'  => $paymentMethod,
                'cart_id' => $cart->id,
            ],
        ],

        OrderPayment::class => [
            [
                'method'  => $paymentMethod,
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

    $cart = Cart::factory()->create([
        'channel_id'            => core()->getCurrentChannel()->id,
        'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
        'base_currency_code'    => $baseCurrencyCode,
        'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
        'cart_currency_code'    => core()->getCurrentCurrencyCode(),
        'items_count'           => 1,
        'items_qty'             => 1,
        'grand_total'           => $price = $product->price,
        'base_grand_total'      => $price,
        'sub_total'	            => $price,
        'base_sub_total'        => $price,
        'is_guest'              => 1,
    ]);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    $data = [
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating'     => '0',
        'quantity'   => '1',
        'links'      => [
            '1',
        ],
    ];

    $cartProducts = $product->getTypeInstance()->prepareForCart($data);

    $parentCartItem = null;

    foreach ($cartProducts as $cartProduct) {
        $cartItem = cart()->getItemByProduct($cartProduct, $data);

        if (isset($cartProduct['parent_id'])) {
            $cartProduct['parent_id'] = $parentCartItem->id;
        }

        if (! $cartItem) {
            $cartItem = CartItem::factory()->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
        } else {
            if (
                isset($cartProduct['parent_id'])
                && $cartItem->parent_id !== $parentCartItem->id
            ) {
                $cartItem = CartItem::factory()->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
            } else {
                $cartItem = CartItem::find($cartItem->id);
                $cartItem->update(array_merge($cartProduct, ['cart_id' => $cart->id]));
            }
        }

        if (! $parentCartItem) {
            $parentCartItem = $cartItem;
        }
    }

    $billingAddress = CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_billing']);
    $shippingAddress = CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_shipping']);

    $cartPayment = new CartPayment;
    $cartPayment->method = $paymentMethod = 'moneytransfer';
    $cartPayment->method_title = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title');
    $cartPayment->cart_id = $cart->id;
    $cartPayment->save();

    // Act and Assert
    $this->loginAsCustomer();

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertModelWise([
        Cart::class => [
            [
                'is_active' => 0,
            ],
        ],

        CustomerAddress::class => [
            [
                'address_type' => $billingAddress->address_type,
                'cart_id'      => $cart->id,
            ],
        ],

        CustomerAddress::class => [
            [
                'address_type' => $shippingAddress->address_type,
                'cart_id'      => $cart->id,
            ],
        ],

        Order::class => [
            [
                'status'      => Order::STATUS_PENDING,
                'grand_total' => $price,
                'cart_id'     => $cart->id,
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

        CartPayment::class => [
            [
                'method'  => $paymentMethod,
                'cart_id' => $cart->id,
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

it('should not return the cash on delivery payment mehthod if product is downloadable', function () {
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

    $customerAddress = CustomerAddress::factory()->create([
        'address_type' => 'customer',
        'customer_id'  => $customer->id,
    ]);

    $cart = Cart::factory()->create([
        'channel_id'            => core()->getCurrentChannel()->id,
        'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
        'base_currency_code'    => $baseCurrencyCode,
        'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
        'cart_currency_code'    => core()->getCurrentCurrencyCode(),
        'items_count'           => 1,
        'items_qty'             => 1,
        'grand_total'           => $price = $product->price,
        'base_grand_total'      => $price,
        'sub_total'	            => $price,
        'base_sub_total'        => $price,
        'customer_id'           => $customer->id,
        'customer_email'        => $customer->email,
        'customer_first_name'   => $customer->first_name,
        'customer_last_name'    => $customer->last_name,
    ]);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    $data = [
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating'     => '0',
        'quantity'   => '1',
        'links'      => [
            '1',
        ],
    ];

    $cartProducts = $product->getTypeInstance()->prepareForCart($data);

    $parentCartItem = null;

    foreach ($cartProducts as $cartProduct) {
        $cartItem = cart()->getItemByProduct($cartProduct, $data);

        if (isset($cartProduct['parent_id'])) {
            $cartProduct['parent_id'] = $parentCartItem->id;
        }

        if (! $cartItem) {
            $cartItem = CartItem::factory()->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
        } else {
            if (
                isset($cartProduct['parent_id'])
                && $cartItem->parent_id !== $parentCartItem->id
            ) {
                $cartItem = CartItem::factory()->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
            } else {
                $cartItem = CartItem::find($cartItem->id);
                $cartItem->update(array_merge($cartProduct, ['cart_id' => $cart->id]));
            }
        }

        if (! $parentCartItem) {
            $parentCartItem = $cartItem;
        }
    }

    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            'address1' => [
                0 => '',
            ],
            'isSaved'          => false,
            'address_id'       => $customerAddress->id,
            'use_for_shipping' => true,
        ],
        'shipping' => [
            'address1' => [
                0 => '',
            ],
            'isSaved'    => false,
            'address_id' => $customerAddress->id,
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

    $customerAddress = CustomerAddress::factory()->create([
        'address_type' => 'customer',
        'customer_id'  => $customer->id,
    ]);

    $cart = Cart::factory()->create([
        'channel_id'            => core()->getCurrentChannel()->id,
        'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
        'base_currency_code'    => $baseCurrencyCode,
        'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
        'cart_currency_code'    => core()->getCurrentCurrencyCode(),
        'items_count'           => 1,
        'items_qty'             => 1,
        'grand_total'           => $price = $product->price,
        'base_grand_total'      => $price,
        'sub_total'	            => $price,
        'base_sub_total'        => $price,
        'customer_id'           => $customer->id,
        'customer_email'        => $customer->email,
        'customer_first_name'   => $customer->first_name,
        'customer_last_name'    => $customer->last_name,
    ]);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    $data = [
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating'     => '0',
        'quantity'   => '1',
        'links'      => [
            '1',
        ],
    ];

    $cartProducts = $product->getTypeInstance()->prepareForCart($data);

    $parentCartItem = null;

    foreach ($cartProducts as $cartProduct) {
        $cartItem = cart()->getItemByProduct($cartProduct, $data);

        if (isset($cartProduct['parent_id'])) {
            $cartProduct['parent_id'] = $parentCartItem->id;
        }

        if (! $cartItem) {
            $cartItem = CartItem::factory()->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
        } else {
            if (
                isset($cartProduct['parent_id'])
                && $cartItem->parent_id !== $parentCartItem->id
            ) {
                $cartItem = CartItem::factory()->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
            } else {
                $cartItem = CartItem::find($cartItem->id);
                $cartItem->update(array_merge($cartProduct, ['cart_id' => $cart->id]));
            }
        }

        if (! $parentCartItem) {
            $parentCartItem = $cartItem;
        }
    }

    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            'address1' => [
                0 => '',
            ],
            'isSaved'          => false,
            'address_id'       => $customerAddress->id,
            'use_for_shipping' => true,
        ],
        'shipping' => [
            'address1' => [
                0 => '',
            ],
            'isSaved'    => false,
            'address_id' => $customerAddress->id,
        ],
    ])
        ->assertOk()
        ->assertJsonPath('redirect', false)
        ->assertJsonMissingPath('data.shippingMethods');
});

it('should not return the cash on delivery payment mehthod if product is virtual', function () {
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

    $customerAddress = CustomerAddress::factory()->create([
        'address_type' => 'customer',
        'customer_id'  => $customer->id,
    ]);

    $cart = Cart::factory()->create([
        'channel_id'            => core()->getCurrentChannel()->id,
        'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
        'base_currency_code'    => $baseCurrencyCode,
        'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
        'cart_currency_code'    => core()->getCurrentCurrencyCode(),
        'items_count'           => 1,
        'items_qty'             => 1,
        'grand_total'           => $price = $product->price,
        'base_grand_total'      => $price,
        'sub_total'	            => $price,
        'base_sub_total'        => $price,
        'customer_id'           => $customer->id,
        'customer_email'        => $customer->email,
        'customer_first_name'   => $customer->first_name,
        'customer_last_name'    => $customer->last_name,
    ]);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    $data = [
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating'     => '0',
        'quantity'   => '1',
        'links'      => [
            '1',
        ],
    ];

    $cartProducts = $product->getTypeInstance()->prepareForCart($data);

    $parentCartItem = null;

    foreach ($cartProducts as $cartProduct) {
        $cartItem = cart()->getItemByProduct($cartProduct, $data);

        if (isset($cartProduct['parent_id'])) {
            $cartProduct['parent_id'] = $parentCartItem->id;
        }

        if (! $cartItem) {
            $cartItem = CartItem::factory()->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
        } else {
            if (
                isset($cartProduct['parent_id'])
                && $cartItem->parent_id !== $parentCartItem->id
            ) {
                $cartItem = CartItem::factory()->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
            } else {
                $cartItem = CartItem::find($cartItem->id);
                $cartItem->update(array_merge($cartProduct, ['cart_id' => $cart->id]));
            }
        }

        if (! $parentCartItem) {
            $parentCartItem = $cartItem;
        }
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            'address1' => [
                0 => '',
            ],
            'isSaved'          => false,
            'address_id'       => $customerAddress->id,
            'use_for_shipping' => true,
        ],
        'shipping' => [
            'address1' => [
                0 => '',
            ],
            'isSaved'    => false,
            'address_id' => $customerAddress->id,
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

    $customerAddress = CustomerAddress::factory()->create([
        'address_type' => 'customer',
        'customer_id'  => $customer->id,
    ]);

    $cart = Cart::factory()->create([
        'channel_id'            => core()->getCurrentChannel()->id,
        'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
        'base_currency_code'    => $baseCurrencyCode,
        'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
        'cart_currency_code'    => core()->getCurrentCurrencyCode(),
        'items_count'           => 1,
        'items_qty'             => 1,
        'grand_total'           => $price = $product->price,
        'base_grand_total'      => $price,
        'sub_total'	            => $price,
        'base_sub_total'        => $price,
        'customer_id'           => $customer->id,
        'customer_email'        => $customer->email,
        'customer_first_name'   => $customer->first_name,
        'customer_last_name'    => $customer->last_name,
    ]);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    $data = [
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating'     => '0',
        'quantity'   => '1',
        'links'      => [
            '1',
        ],
    ];

    $cartProducts = $product->getTypeInstance()->prepareForCart($data);

    $parentCartItem = null;

    foreach ($cartProducts as $cartProduct) {
        $cartItem = cart()->getItemByProduct($cartProduct, $data);

        if (isset($cartProduct['parent_id'])) {
            $cartProduct['parent_id'] = $parentCartItem->id;
        }

        if (! $cartItem) {
            $cartItem = CartItem::factory()->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
        } else {
            if (
                isset($cartProduct['parent_id'])
                && $cartItem->parent_id !== $parentCartItem->id
            ) {
                $cartItem = CartItem::factory()->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
            } else {
                $cartItem = CartItem::find($cartItem->id);
                $cartItem->update(array_merge($cartProduct, ['cart_id' => $cart->id]));
            }
        }

        if (! $parentCartItem) {
            $parentCartItem = $cartItem;
        }
    }

    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            'address1' => [
                0 => '',
            ],
            'isSaved'          => false,
            'address_id'       => $customerAddress->id,
            'use_for_shipping' => true,
        ],
        'shipping' => [
            'address1' => [
                0 => '',
            ],
            'isSaved'    => false,
            'address_id' => $customerAddress->id,
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
        'channel_id'            => core()->getCurrentChannel()->id,
        'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
        'base_currency_code'    => $baseCurrencyCode,
        'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
        'cart_currency_code'    => core()->getCurrentCurrencyCode(),
        'items_count'           => 1,
        'items_qty'             => 1,
        'grand_total'           => $price = $configurableProduct->price,
        'base_grand_total'      => $price,
        'sub_total'	            => $price,
        'base_sub_total'        => $price,
        'shipping_method'       => 'free_free',
        'customer_id'           => $customer->id,
        'is_active'             => 1,
        'customer_email'        => $customer->email,
        'customer_first_name'   => $customer->first_name,
        'customer_last_name'    => $customer->last_name,
    ]);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    $childProduct = $configurableProduct->variants()->first();

    $data = [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $configurableProduct->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => $super_attributes ?? [],
    ];

    $cartProducts = $configurableProduct->getTypeInstance()->prepareForCart($data);

    $parentCartItem = null;

    foreach ($cartProducts as $cartProduct) {
        $cartItem = cart()->getItemByProduct($cartProduct, $data);

        if (isset($cartProduct['parent_id'])) {
            $cartProduct['parent_id'] = $parentCartItem->id;
        }

        if (! $cartItem) {
            $cartItem = CartItem::factory()->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
        } else {
            if (
                isset($cartProduct['parent_id'])
                && $cartItem->parent_id !== $parentCartItem->id
            ) {
                $cartItem = CartItem::factory()->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
            } else {
                $cartItem = CartItem::find($cartItem->id);
                $cartItem->update(array_merge($cartProduct, ['cart_id' => $cart->id]));
            }
        }

        if (! $parentCartItem) {
            $parentCartItem = $cartItem;
        }
    }

    $cartItem = CartItem::factory()->create([
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

    $billingAddress = CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_billing']);
    $shippingAddress = CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_shipping']);

    CartShippingRate::factory()->create([
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $shippingAddress->id,
        'carrier_title'      => 'Free shipping',
        'method_title'       => 'Free Shipping',
        'carrier'            => 'free',
        'method'             => 'free_free',
    ]);

    $cartPayment = new CartPayment;
    $cartPayment->method = $paymentMethod = 'cashondelivery';
    $cartPayment->method_title = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title');
    $cartPayment->cart_id = $cart->id;
    $cartPayment->save();

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertModelWise([
        Cart::class => [
            [
                'is_active' => 0,
            ],
        ],

        CustomerAddress::class => [
            [
                'address_type' => $billingAddress->address_type,
                'cart_id'      => $cart->id,
            ],
        ],

        CustomerAddress::class => [
            [
                'address_type' => $shippingAddress->address_type,
                'cart_id'      => $cart->id,
            ],
        ],

        Order::class => [
            [
                'shipping_method'   => 'free_free',
                'grand_total'       => $childProduct->price + $simpleProduct->price,
                'cart_id'           => $cart->id,
                'status'            => Order::STATUS_PENDING,
                'total_item_count'  => 2,
                'total_qty_ordered' => 2,
                'customer_id'       => $customer->id,
                'is_guest'          => 0,
            ],
        ],

        OrderItem::class => [
            [
                'qty_ordered'  => $quantity = $cartItem->quantity,
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
                'qty_ordered'  => $quantity = $cartItem->quantity,
                'qty_shipped'  => 0,
                'qty_invoiced' => 0,
                'qty_canceled' => 0,
                'qty_refunded' => 0,
                'product_id'   => $simpleProduct->id,
                'price'        => $simpleProduct->price,
                'type'         => $simpleProduct->type,
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
        'grand_tatal' => [],
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
        'channel_id'            => core()->getCurrentChannel()->id,
        'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
        'base_currency_code'    => $baseCurrencyCode,
        'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
        'cart_currency_code'    => core()->getCurrentCurrencyCode(),
        'items_count'           => 5,
        'items_qty'             => array_sum($data['qty']),
        'grand_total'           => $price = array_sum($data['grand_total']),
        'base_grand_total'      => $price,
        'sub_total'	            => $price,
        'base_sub_total'        => $price,
        'shipping_method'       => 'free_free',
        'customer_id'           => $customer->id,
        'is_active'             => 1,
        'customer_email'        => $customer->email,
        'customer_first_name'   => $customer->first_name,
        'customer_last_name'    => $customer->last_name,
    ]);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    foreach ($bundleProducts as $bundleProduct) {
        CartItem::factory()->create([
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

    CartItem::factory()->create([
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

    $billingAddress = CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_billing']);
    $shippingAddress = CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_shipping']);

    CartShippingRate::factory()->create([
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $shippingAddress->id,
        'carrier_title'      => 'Free shipping',
        'method_title'       => 'Free Shipping',
        'carrier'            => 'free',
        'method'             => 'free_free',
    ]);

    $cartPayment = new CartPayment;
    $cartPayment->method = $paymentMethod = 'cashondelivery';
    $cartPayment->method_title = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title');
    $cartPayment->cart_id = $cart->id;
    $cartPayment->save();

    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertModelWise([
        Cart::class => [
            [
                'is_active' => 0,
            ],
        ],

        CustomerAddress::class => [
            [
                'address_type' => $billingAddress->address_type,
                'cart_id'      => $cart->id,
            ],
        ],

        CustomerAddress::class => [
            [
                'address_type' => $shippingAddress->address_type,
                'cart_id'      => $cart->id,
            ],
        ],

        Order::class => [
            [
                'shipping_method'   => 'free_free',
                'grand_total'       => array_sum($data['grand_total']),
                'cart_id'           => $cart->id,
                'status'            => Order::STATUS_PENDING,
                'total_item_count'  => 5,
                'total_qty_ordered' => array_sum($data['qty']),
                'customer_id'       => $customer->id,
                'is_guest'          => 0,
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
        'channel_id'            => core()->getCurrentChannel()->id,
        'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
        'base_currency_code'    => $baseCurrencyCode,
        'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
        'cart_currency_code'    => core()->getCurrentCurrencyCode(),
        'items_count'           => 1,
        'items_qty'             => 1,
        'grand_total'           => $price = $downloadableProduct->price + $simpleProduct->price,
        'base_grand_total'      => $price,
        'sub_total'	            => $price,
        'base_sub_total'        => $price,
        'customer_id'           => $customer->id,
        'is_active'             => 1,
        'customer_email'        => $customer->email,
        'customer_first_name'   => $customer->first_name,
        'customer_last_name'    => $customer->last_name,
        'shipping_method'       => 'free_free',
    ]);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    $data = [
        'product_id' => $downloadableProduct->id,
        'is_buy_now' => '0',
        'rating'     => '0',
        'quantity'   => '1',
        'links'      => [
            '1',
        ],
    ];

    $cartProducts = $downloadableProduct->getTypeInstance()->prepareForCart($data);

    $parentCartItem = null;

    foreach ($cartProducts as $cartProduct) {
        $cartItem = cart()->getItemByProduct($cartProduct, $data);

        if (isset($cartProduct['parent_id'])) {
            $cartProduct['parent_id'] = $parentCartItem->id;
        }

        if (! $cartItem) {
            $cartItem = CartItem::factory()->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
        } else {
            if (
                isset($cartProduct['parent_id'])
                && $cartItem->parent_id !== $parentCartItem->id
            ) {
                $cartItem = CartItem::factory()->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
            } else {
                $cartItem = CartItem::find($cartItem->id);
                $cartItem->update(array_merge($cartProduct, ['cart_id' => $cart->id]));
            }
        }

        if (! $parentCartItem) {
            $parentCartItem = $cartItem;
        }
    }

    CartItem::factory()->create([
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

    $billingAddress = CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_billing']);
    $shippingAddress = CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_shipping']);

    CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $shippingAddress->id,
    ]);

    $cartPayment = new CartPayment;
    $cartPayment->method = $paymentMethod = 'moneytransfer';
    $cartPayment->method_title = core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title');
    $cartPayment->cart_id = $cart->id;
    $cartPayment->save();

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertModelWise([
        Cart::class => [
            [
                'is_active' => 0,
            ],
        ],

        CustomerAddress::class => [
            [
                'address_type' => $billingAddress->address_type,
                'cart_id'      => $cart->id,
            ],
        ],

        CustomerAddress::class => [
            [
                'address_type' => $shippingAddress->address_type,
                'cart_id'      => $cart->id,
            ],
        ],

        Order::class => [
            [
                'status'      => Order::STATUS_PENDING,
                'grand_total' => $price,
                'cart_id'     => $cart->id,
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

        CartPayment::class => [
            [
                'method'  => $paymentMethod,
                'cart_id' => $cart->id,
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
