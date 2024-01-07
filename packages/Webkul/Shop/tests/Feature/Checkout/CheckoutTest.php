<?php

use Illuminate\Support\Facades\DB;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductInventory;
use Webkul\Product\Models\ProductOrderedInventory;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderItem;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

afterEach(function () {
    /**
     * Cleaing up the rows which are createing while testing.
     */
    Cart::query()->delete();
    CartItem::query()->delete();
    CartPayment::query()->delete();
    CartShippingRate::query()->delete();
    CustomerAddress::query()->delete();
    Order::query()->delete();
    OrderItem::query()->delete();
    ProductOrderedInventory::query()->delete();
    ProductInventory::query()->delete();
    Product::query()->delete();
    DB::table('product_ordered_inventories')->truncate();
    DB::table('product_inventory_indices')->truncate();
});

it('should display the cart items from the cart', function () {
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
        'weight'	           => 1,
        'total_weight'	     => 1,
        'base_total_weight' => 1,
        'cart_id'           => $cartId = Cart::factory()->create([
            'channel_id'            => core()->getCurrentChannel()->id,
            'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
            'base_currency_code'    => $baseCurrencyCode,
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'items_count'           => 1,
            'items_qty'             => 1,
            'grand_total'	          => $price = $product->price,
            'base_grand_total'	     => $price,
            'sub_total'	            => $price,
            'base_sub_total'        => $price,
            'is_guest'              => 1,
        ])->id,
    ]);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cartId;

    session()->put('cart', $cartTemp);

    // Act and Assert
    get(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.id', $cartId)
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items.0.id', $cartItem->id)
        ->assertJsonPath('data.items.0.type', $product->type)
        ->assertJsonPath('data.items.0.name', $product->name)
        ->assertJsonPath('data.haveStockableItems', true);
});

it('should remove product items to the cart', function () {
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
        'weight'	           => 1,
        'total_weight'	     => 1,
        'base_total_weight' => 1,
        'cart_id'           => $cartId = Cart::factory()->create([
            'channel_id'            => core()->getCurrentChannel()->id,
            'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
            'base_currency_code'    => $baseCurrencyCode,
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'items_count'           => 1,
            'items_qty'             => 1,
            'grand_total'	          => $price = $product->price,
            'base_grand_total'	     => $price,
            'sub_total'	            => $price,
            'base_sub_total'        => $price,
            'is_guest'              => 1,
        ])->id,
    ]);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cartId;

    session()->put('cart', $cartTemp);

    // Act and Assert
    deleteJson(route('shop.api.checkout.cart.destroy', [
        'cart_item_id' => $cartItem->id,
    ]))
        ->assertOk()
        ->assertJsonPath('data', null)
        ->assertJsonPath('message', trans('shop::app.checkout.cart.success-remove'));

    $this->assertDatabaseMissing('cart_items', [
        'id' => $cartItem->id,
    ]);
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
        'weight'	           => 1,
        'total_weight'	     => 1,
        'base_total_weight' => 1,
        'cart_id'           => $cartId = Cart::factory()->create([
            'channel_id'            => core()->getCurrentChannel()->id,
            'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
            'base_currency_code'    => $baseCurrencyCode,
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'items_count'           => 1,
            'items_qty'             => 1,
            'grand_total'	          => $price = $product->price,
            'base_grand_total'	     => $price,
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
        'weight'	           => 1,
        'total_weight'	     => 1,
        'base_total_weight' => 1,
        'cart_id'           => $cartId = Cart::factory()->create([
            'channel_id'            => core()->getCurrentChannel()->id,
            'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
            'base_currency_code'    => $baseCurrencyCode,
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'items_count'           => 1,
            'items_qty'             => 1,
            'grand_total'	          => $price = $product->price,
            'base_grand_total'	     => $price,
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
        'weight'	           => 1,
        'total_weight'	     => 1,
        'base_total_weight' => 1,
        'cart_id'           => $cartId = Cart::factory()->create([
            'channel_id'            => core()->getCurrentChannel()->id,
            'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
            'base_currency_code'    => $baseCurrencyCode,
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'items_count'           => 1,
            'items_qty'             => 1,
            'grand_total'	          => $price = $product->price,
            'base_grand_total'	     => $price,
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
        'grand_total'	          => $price = $product->price,
        'base_grand_total'	     => $price,
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
        'weight'	           => 1,
        'total_weight'	     => 1,
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
    $cartPayment->method_title = core()->getConfigData('sales.payment_methods.' . $paymentMethod . '.title');
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

    $this->assertDatabaseHas('cart', ['is_active' => 0]);

    $this->assertDatabaseHas('addresses', [
        'address_type' => $billingAddress->address_type,
        'cart_id'      => $cart->id,
    ]);

    $this->assertDatabaseHas('addresses', [
        'address_type' => $shippingAddress->address_type,
        'cart_id'      => $cart->id,
    ]);

    $this->assertDatabaseHas('orders', [
        'status'          => 'pending',
        'shipping_method' => 'free_free',
        'grand_total'     => $price,
        'cart_id'         => $cart->id,
    ]);

    $this->assertDatabaseHas('order_items', [
        'qty_ordered'  => $quantity = $cartItem->quantity,
        'qty_shipped'  => 0,
        'qty_invoiced' => 0,
        'qty_canceled' => 0,
        'qty_refunded' => 0,
        'price'        => $price,
        'type'         => $product->type,
        'product_id'   => $product->id,
    ]);

    $this->assertDatabaseHas('cart_payment', [
        'method'  => $paymentMethod,
        'cart_id' => $cart->id,
    ]);

    $this->assertDatabaseHas('order_payment', ['method'  => $paymentMethod]);

    $this->assertDatabaseHas('product_ordered_inventories', [
        'qty'        => $quantity,
        'product_id' => $product->id,
    ]);

    $this->assertDatabaseHas('product_inventory_indices', [
        'qty'        => $product->inventory_source_qty(1) - $quantity,
        'product_id' => $product->id,
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

    // Act
    $customer = $this->loginAsCustomer();

    $cart = Cart::factory()->create([
        'channel_id'            => core()->getCurrentChannel()->id,
        'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
        'base_currency_code'    => $baseCurrencyCode,
        'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
        'cart_currency_code'    => core()->getCurrentCurrencyCode(),
        'items_count'           => 1,
        'items_qty'             => 1,
        'grand_total'	          => $price = $product->price,
        'base_grand_total'	     => $price,
        'sub_total'	            => $price,
        'base_sub_total'        => $price,
        'shipping_method'       => 'free_free',
        'customer_id'           => $customer->id,
        'is_active'             => 1,
        'customer_email'	       => $customer->email,
        'customer_first_name'	  => $customer->first_name,
        'customer_last_name'    => $customer->last_name,
    ]);

    $cartItem = CartItem::factory()->create([
        'quantity'          => 1,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'weight'	           => 1,
        'total_weight'	     => 1,
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
    $cartPayment->method_title = core()->getConfigData('sales.payment_methods.' . $paymentMethod . '.title');
    $cartPayment->cart_id = $cart->id;
    $cartPayment->save();

    $cartTemp = new \stdClass();
    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    // Assert
    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertDatabaseHas('cart', ['is_active' => 0]);

    $this->assertDatabaseHas('addresses', [
        'address_type' => $billingAddress->address_type,
        'cart_id'      => $cart->id,
    ]);

    $this->assertDatabaseHas('addresses', [
        'address_type' => $shippingAddress->address_type,
        'cart_id'      => $cart->id,
    ]);

    $this->assertDatabaseHas('orders', [
        'status'          => 'pending',
        'shipping_method' => 'free_free',
        'grand_total'     => $price,
        'cart_id'         => $cart->id,
    ]);

    $this->assertDatabaseHas('order_items', [
        'qty_ordered'  => $quantity = $cartItem->quantity,
        'qty_shipped'  => 0,
        'qty_invoiced' => 0,
        'qty_canceled' => 0,
        'qty_refunded' => 0,
        'price'        => $price,
        'type'         => $product->type,
        'product_id'   => $product->id,
    ]);

    $this->assertDatabaseHas('cart_payment', [
        'method'  => $paymentMethod,
        'cart_id' => $cart->id,
    ]);

    $this->assertDatabaseHas('order_payment', ['method'  => $paymentMethod]);

    $this->assertDatabaseHas('product_ordered_inventories', [
        'qty'        => $quantity,
        'product_id' => $product->id,
    ]);

    $this->assertDatabaseHas('product_inventory_indices', [
        'qty'        => $product->inventory_source_qty(1) - $quantity,
        'product_id' => $product->id,
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
        'grand_total'	          => $price = $product->price,
        'base_grand_total'	     => $price,
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
        'method_description'    => 'Free Shipping',
        'cart_address_id'       => $shippingAddress->id,
        'carrier_title'         => 'Free shipping',
        'method_title'          => 'Free Shipping',
        'carrier'               => 'free',
        'method'                => 'free_free',
    ]);

    $cartPayment = new CartPayment;
    $cartPayment->method = $paymentMethod = 'cashondelivery';
    $cartPayment->method_title = core()->getConfigData('sales.payment_methods.' . $paymentMethod . '.title');
    $cartPayment->cart_id = $cart->id;
    $cartPayment->save();

    // Act and Assert
    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertDatabaseHas('cart', ['is_active' => 0]);

    $this->assertDatabaseHas('addresses', [
        'address_type' => $billingAddress->address_type,
        'cart_id'      => $cart->id,
    ]);

    $this->assertDatabaseHas('addresses', [
        'address_type' => $shippingAddress->address_type,
        'cart_id'      => $cart->id,
    ]);

    $this->assertDatabaseHas('orders', [
        'shipping_method' => 'free_free',
        'grand_total'     => $childProduct->price,
        'cart_id'         => $cart->id,
        'status'          => 'pending',
    ]);

    $this->assertDatabaseHas('order_items', [
        'qty_ordered'  => $quantity = $cartItem->quantity,
        'qty_shipped'  => 0,
        'qty_invoiced' => 0,
        'qty_canceled' => 0,
        'qty_refunded' => 0,
        'product_id'   => $product->id,
        'price'        => $childProduct->price,
        'type'         => $product->type,
    ]);

    $this->assertDatabaseHas('cart_payment', [
        'cart_id' => $cart->id,
        'method'  => $paymentMethod,
    ]);

    $this->assertDatabaseHas('order_payment', ['method'  => $paymentMethod]);

    $this->assertDatabaseHas('product_ordered_inventories', [
        'product_id' => $childProduct->id,
        'qty'        => $quantity,
    ]);

    $this->assertDatabaseHas('product_inventory_indices', [
        'qty'        => $childProduct->inventory_source_qty(1) - $quantity,
        'product_id' => $childProduct->id,
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

    // Act
    $customer = $this->loginAsCustomer();

    $cart = Cart::factory()->create([
        'channel_id'            => core()->getCurrentChannel()->id,
        'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
        'base_currency_code'    => $baseCurrencyCode,
        'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
        'cart_currency_code'    => core()->getCurrentCurrencyCode(),
        'items_count'           => 1,
        'items_qty'             => 1,
        'grand_total'	          => $price = $product->price,
        'base_grand_total'	     => $price,
        'sub_total'	            => $price,
        'base_sub_total'        => $price,
        'shipping_method'       => 'free_free',
        'customer_id'           => $customer->id,
        'is_active'             => 1,
        'customer_email'	       => $customer->email,
        'customer_first_name'	  => $customer->first_name,
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
        'method_description'    => 'Free Shipping',
        'cart_address_id'       => $shippingAddress->id,
        'carrier_title'         => 'Free shipping',
        'method_title'          => 'Free Shipping',
        'carrier'               => 'free',
        'method'                => 'free_free',
    ]);

    $cartPayment = new CartPayment;
    $cartPayment->method = $paymentMethod = 'cashondelivery';
    $cartPayment->method_title = core()->getConfigData('sales.payment_methods.' . $paymentMethod . '.title');
    $cartPayment->cart_id = $cart->id;
    $cartPayment->save();

    // Assert
    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertDatabaseHas('cart', ['is_active' => 0]);

    $this->assertDatabaseHas('addresses', [
        'address_type' => $billingAddress->address_type,
        'cart_id'      => $cart->id,
    ]);

    $this->assertDatabaseHas('addresses', [
        'address_type' => $shippingAddress->address_type,
        'cart_id'      => $cart->id,
    ]);

    $this->assertDatabaseHas('orders', [
        'shipping_method' => 'free_free',
        'grand_total'     => $childProduct->price,
        'cart_id'         => $cart->id,
        'status'          => 'pending',
    ]);

    $this->assertDatabaseHas('order_items', [
        'qty_ordered'  => $quantity = $cartItem->quantity,
        'qty_shipped'  => 0,
        'qty_invoiced' => 0,
        'qty_canceled' => 0,
        'qty_refunded' => 0,
        'product_id'   => $product->id,
        'price'        => $childProduct->price,
        'type'         => $product->type,
    ]);

    $this->assertDatabaseHas('cart_payment', [
        'cart_id' => $cart->id,
        'method'  => $paymentMethod,
    ]);

    $this->assertDatabaseHas('order_payment', ['method'  => $paymentMethod]);

    $this->assertDatabaseHas('product_ordered_inventories', [
        'product_id' => $childProduct->id,
        'qty'        => $quantity,
    ]);

    $this->assertDatabaseHas('product_inventory_indices', [
        'qty'        => $childProduct->inventory_source_qty(1) - $quantity,
        'product_id' => $childProduct->id,
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
        'total_weight'	     => 1,
        'product_id'        => $product->id,
        'quantity'          => 1,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'weight'	           => 1,
        'cart_id'           => $cart = Cart::factory()->create([
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
            'base_currency_code'    => $baseCurrencyCode,
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'base_grand_total'	     => $price = $product->price,
            'base_sub_total'        => $price,
            'channel_id'            => core()->getCurrentChannel()->id,
            'items_count'           => 1,
            'items_qty'             => 1,
            'grand_total'	          => $price,
            'sub_total'	            => $price,
            'is_guest'              => 1,
        ]),
    ]);

    CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_billing']);

    $cartPayment = new CartPayment;
    $cartPayment->method = $paymentMethod = 'cashondelivery';
    $cartPayment->method_title = core()->getConfigData('sales.payment_methods.' . $paymentMethod . '.title');
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

    $this->assertDatabaseHas('cart', ['is_active' => 0]);

    $this->assertDatabaseHas('addresses', [
        'address_type' => 'cart_billing',
        'cart_id'      => $cart->id,
    ]);

    $this->assertDatabaseHas('orders', [
        'status'          => 'pending',
        'grand_total'     => $price,
        'cart_id'         => $cart->id,
    ]);

    $this->assertDatabaseHas('order_items', [
        'qty_ordered'  => $quantity = $cartItem->quantity,
        'qty_shipped'  => 0,
        'qty_invoiced' => 0,
        'qty_canceled' => 0,
        'qty_refunded' => 0,
        'price'        => $price,
        'type'         => $product->type,
        'product_id'   => $product->id,
    ]);

    $this->assertDatabaseHas('cart_payment', [
        'method'  => $paymentMethod,
        'cart_id' => $cart->id,
    ]);

    $this->assertDatabaseHas('order_payment', ['method'  => $paymentMethod]);

    $this->assertDatabaseHas('product_ordered_inventories', [
        'qty'        => $quantity,
        'product_id' => $product->id,
    ]);

    $this->assertDatabaseHas('product_inventory_indices', [
        'qty'        => $product->inventory_source_qty(1) - $quantity,
        'product_id' => $product->id,
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

    // Act
    $customer = $this->loginAsCustomer();

    $cartItem = CartItem::factory()->create([
        'base_total_weight' => 1,
        'total_weight'	     => 1,
        'product_id'        => $product->id,
        'quantity'          => 1,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'weight'	           => 1,
        'cart_id'           => $cart = Cart::factory()->create([
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
            'base_currency_code'    => $baseCurrencyCode,
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'base_grand_total'	     => $price = $product->price,
            'base_sub_total'        => $price,
            'channel_id'            => core()->getCurrentChannel()->id,
            'items_count'           => 1,
            'items_qty'             => 1,
            'grand_total'	          => $price,
            'sub_total'	            => $price,
            'customer_id'           => $customer->id,
            'is_active'             => 1,
            'customer_email'	       => $customer->email,
            'customer_first_name'	  => $customer->first_name,
            'customer_last_name'    => $customer->last_name,
        ]),
    ]);

    CustomerAddress::factory()->create(['cart_id' => $cart->id, 'address_type' => 'cart_billing']);

    $cartPayment = new CartPayment;
    $cartPayment->method = $paymentMethod = 'cashondelivery';
    $cartPayment->method_title = core()->getConfigData('sales.payment_methods.' . $paymentMethod . '.title');
    $cartPayment->cart_id = $cart->id;
    $cartPayment->save();

    $cartTemp = new \stdClass();
    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    // Assert
    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertDatabaseHas('cart', ['is_active' => 0]);

    $this->assertDatabaseHas('addresses', [
        'address_type' => 'cart_billing',
        'cart_id'      => $cart->id,
    ]);

    $this->assertDatabaseHas('orders', [
        'status'          => 'pending',
        'grand_total'     => $price,
        'cart_id'         => $cart->id,
    ]);

    $this->assertDatabaseHas('order_items', [
        'qty_ordered'  => $quantity = $cartItem->quantity,
        'qty_shipped'  => 0,
        'qty_invoiced' => 0,
        'qty_canceled' => 0,
        'qty_refunded' => 0,
        'price'        => $price,
        'type'         => $product->type,
        'product_id'   => $product->id,
    ]);

    $this->assertDatabaseHas('cart_payment', [
        'method'  => $paymentMethod,
        'cart_id' => $cart->id,
    ]);

    $this->assertDatabaseHas('order_payment', ['method'  => $paymentMethod]);

    $this->assertDatabaseHas('product_ordered_inventories', [
        'qty'        => $quantity,
        'product_id' => $product->id,
    ]);

    $this->assertDatabaseHas('product_inventory_indices', [
        'qty'        => $product->inventory_source_qty(1) - $quantity,
        'product_id' => $product->id,
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
        'grand_total'	          => $price = $product->price,
        'base_grand_total'	     => $price,
        'sub_total'	            => $price,
        'base_sub_total'        => $price,
        'is_guest'              => 1,
    ]);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    $data = [
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'links'                        => [
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

    CartShippingRate::factory()->create([
        'method_description'    => 'Free Shipping',
        'cart_address_id'       => $shippingAddress->id,
        'carrier_title'         => 'Free shipping',
        'method_title'          => 'Free Shipping',
        'carrier'               => 'free',
        'method'                => 'free_free',
    ]);

    $cartPayment = new CartPayment;
    $cartPayment->method = $paymentMethod = 'cashondelivery';
    $cartPayment->method_title = core()->getConfigData('sales.payment_methods.' . $paymentMethod . '.title');
    $cartPayment->cart_id = $cart->id;
    $cartPayment->save();

    // Act and Assert
    $this->loginAsCustomer();

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'));

    $this->assertDatabaseHas('cart', ['is_active' => 0]);

    $this->assertDatabaseHas('addresses', [
        'address_type' => $billingAddress->address_type,
        'cart_id'      => $cart->id,
    ]);

    $this->assertDatabaseHas('addresses', [
        'address_type' => $shippingAddress->address_type,
        'cart_id'      => $cart->id,
    ]);

    $this->assertDatabaseHas('orders', [
        'status'          => 'pending',
        'grand_total'     => $price,
        'cart_id'         => $cart->id,
    ]);

    $this->assertDatabaseHas('order_items', [
        'qty_ordered'  => $quantity = $cartItem->quantity,
        'qty_shipped'  => 0,
        'qty_invoiced' => 0,
        'qty_canceled' => 0,
        'qty_refunded' => 0,
        'price'        => $price,
        'type'         => $product->type,
        'product_id'   => $product->id,
    ]);

    $this->assertDatabaseHas('cart_payment', [
        'method'  => $paymentMethod,
        'cart_id' => $cart->id,
    ]);

    $this->assertDatabaseHas('order_payment', ['method'  => $paymentMethod]);

    $this->assertDatabaseHas('product_inventory_indices', [
        'qty'        => 0,
        'product_id' => $product->id,
    ]);
});
