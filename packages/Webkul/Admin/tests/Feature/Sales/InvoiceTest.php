<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Webkul\Admin\Mail\Order\InvoicedNotification as AdminInvoicedNotification;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartItem;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\InvoiceItem;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Sales\Models\OrderTransaction;
use Webkul\Shop\Mail\Order\InvoicedNotification as ShopInvoicedNotification;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

it('should returns the invoice index page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.sales.invoices.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.invoices.index.title'));
});

it('should fails the validation error when the invoice item quantity not provided', function () {
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

    $customerAddress = CustomerAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
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

    $orderItem = OrderItem::factory()->create([
        'product_id' => $product->id,
        'order_id'   => $order->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
    ]);

    $orderBillingAddress = OrderAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    $orderShippingAddress = OrderAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $orderPayment = OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.store', $order->id))
        ->assertJsonValidationErrorFor('invoice.items')
        ->assertUnprocessable();

    $cart->refresh();

    $cartItem->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $orderBillingAddress->refresh();

    $orderShippingAddress->refresh();

    $order->refresh();

    $orderItem->refresh();

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

        CartAddress::class => [
            $this->prepareAddress($cartShippingAddress),
        ],

        CartShippingRate::class => [
            $this->prepareCartShippingRate($cartShippingRate),
        ],

        CustomerAddress::class => [
            $this->prepareAddress($customerAddress),
        ],

        Order::class => [
            $this->prepareOrder($order),
        ],

        OrderItem::class => [
            $this->prepareOrderItem($orderItem),
        ],

        OrderAddress::class => [
            $this->prepareAddress($orderBillingAddress),

            $this->prepareAddress($orderShippingAddress),
        ],

        OrderPayment::class => [
            $this->prepareOrderPayment($orderPayment),
        ],
    ]);
});

it('should fails the validation error when the invoice item quantity is string', function () {
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

    $customerAddress = CustomerAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
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

    $orderItem = OrderItem::factory()->create([
        'product_id' => $product->id,
        'order_id'   => $order->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
    ]);

    $orderBillingAddress = OrderAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    $orderShippingAddress = OrderAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $orderPayment = OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.store', $order->id), [
        'invoice' => [
            'items' => [
                'INVALID_ITEM_ID',
            ],
        ],
    ])
        ->assertJsonValidationErrorFor('invoice.items.0')
        ->assertUnprocessable();

    $cart->refresh();

    $cartItem->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $orderBillingAddress->refresh();

    $orderShippingAddress->refresh();

    $order->refresh();

    $orderItem->refresh();

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

        CartAddress::class => [
            $this->prepareAddress($cartShippingAddress),
        ],

        CartShippingRate::class => [
            $this->prepareCartShippingRate($cartShippingRate),
        ],

        CustomerAddress::class => [
            $this->prepareAddress($customerAddress),
        ],

        Order::class => [
            $this->prepareOrder($order),
        ],

        OrderItem::class => [
            $this->prepareOrderItem($orderItem),
        ],

        OrderAddress::class => [
            $this->prepareAddress($orderBillingAddress),

            $this->prepareAddress($orderShippingAddress),
        ],

        OrderPayment::class => [
            $this->prepareOrderPayment($orderPayment),
        ],
    ]);
});

it('should store the invoice', function () {
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

    $customerAddress = CustomerAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
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

    $orderItem = OrderItem::factory()->create([
        'product_id' => $product->id,
        'order_id'   => $order->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
    ]);

    $orderBillingAddress = OrderAddress::factory()->create([
        ...Arr::except($cartBillingAddress->toArray(), ['id', 'created_at', 'updated_at']),
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
        'order_id'     => $order->id,
    ]);

    $orderShippingAddress = OrderAddress::factory()->create([
        ...Arr::except($cartShippingAddress->toArray(), ['id', 'created_at', 'updated_at']),
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
        'order_id'     => $order->id,
    ]);

    $orderPayment = OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    $items = [];

    foreach ($order->items as $item) {
        $items[$item->id] = $item->qty_to_invoice;
    }

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.store', $order->id), [
        'invoice' => [
            'items' => $items,
        ],
    ])
        ->assertRedirect(route('admin.sales.orders.view', $order->id))
        ->isRedirection();

    $cart->refresh();

    $cartItem->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $orderBillingAddress->refresh();

    $orderShippingAddress->refresh();

    $order->refresh();

    $orderItem->refresh();

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

        CartAddress::class => [
            $this->prepareAddress($cartShippingAddress),
        ],

        CartShippingRate::class => [
            $this->prepareCartShippingRate($cartShippingRate),
        ],

        CustomerAddress::class => [
            $this->prepareAddress($customerAddress),
        ],

        Order::class => [
            $this->prepareOrder($order),
        ],

        OrderItem::class => [
            $this->prepareOrderItem($orderItem),
        ],

        OrderAddress::class => [
            $this->prepareAddress($orderBillingAddress),

            $this->prepareAddress($orderShippingAddress),
        ],

        OrderPayment::class => [
            $this->prepareOrderPayment($orderPayment),
        ],

        Invoice::class => [
            $this->prepareInvoice($order, $orderItem),
        ],
    ]);
});

it('should store the invoice and send email to the customer and admin', function () {
    // Arrange.
    Mail::fake();

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

    $customerAddress = CustomerAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
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

    $orderItem = OrderItem::factory()->create([
        'product_id' => $product->id,
        'order_id'   => $order->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
    ]);

    $orderBillingAddress = OrderAddress::factory()->create([
        ...Arr::except($cartBillingAddress->toArray(), ['id', 'created_at', 'updated_at']),
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
        'order_id'     => $order->id,
    ]);

    $orderShippingAddress = OrderAddress::factory()->create([
        ...Arr::except($cartShippingAddress->toArray(), ['id', 'created_at', 'updated_at']),
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
        'order_id'     => $order->id,
    ]);

    $orderPayment = OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    $items = [];

    foreach ($order->items as $item) {
        $items[$item->id] = $item->qty_to_invoice;
    }

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.store', $order->id), [
        'invoice' => [
            'items' => $items,
        ],
    ])
        ->assertRedirect(route('admin.sales.orders.view', $order->id))
        ->isRedirection();

    $cart->refresh();

    $cartItem->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $orderBillingAddress->refresh();

    $orderShippingAddress->refresh();

    $order->refresh();

    $orderItem->refresh();

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

        CartAddress::class => [
            $this->prepareAddress($cartShippingAddress),
        ],

        CartShippingRate::class => [
            $this->prepareCartShippingRate($cartShippingRate),
        ],

        CustomerAddress::class => [
            $this->prepareAddress($customerAddress),
        ],

        Order::class => [
            $this->prepareOrder($order),
        ],

        OrderItem::class => [
            $this->prepareOrderItem($orderItem),
        ],

        OrderAddress::class => [
            $this->prepareAddress($orderBillingAddress),

            $this->prepareAddress($orderShippingAddress),
        ],

        OrderPayment::class => [
            $this->prepareOrderPayment($orderPayment),
        ],

        Invoice::class => [
            $this->prepareInvoice($order, $orderItem),
        ],
    ]);

    Mail::assertQueued(AdminInvoicedNotification::class);

    Mail::assertQueued(ShopInvoicedNotification::class);

    Mail::assertQueuedCount(2);
});

it('should return the view page of the invoice', function () {
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

    $customerAddress = CustomerAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
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
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartShippingAddress->id,
    ]);

    $order = Order::factory()->create([
        'cart_id'                  => $cart->id,
        'customer_id'              => $customer->id,
        'customer_email'           => $customer->email,
        'customer_first_name'      => $customer->first_name,
        'customer_last_name'       => $customer->last_name,
        'status'                   => 'processing',
        'sub_total_invoiced'       => $product->price,
        'base_sub_total_invoiced'  => $product->price,
    ]);

    $orderItem = OrderItem::factory()->create([
        'product_id'           => $product->id,
        'order_id'             => $order->id,
        'sku'                  => $product->sku,
        'type'                 => $product->type,
        'name'                 => $product->name,
        'qty_invoiced'         => 1,
        'base_total_invoiced'  => $product->price,
    ]);

    $orderBillingAddress = OrderAddress::factory()->create([
        ...Arr::except($cartBillingAddress->toArray(), ['id', 'created_at', 'updated_at']),
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
        'order_id'     => $order->id,
    ]);

    $orderShippingAddress = OrderAddress::factory()->create([
        ...Arr::except($cartShippingAddress->toArray(), ['id', 'created_at', 'updated_at']),
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
        'order_id'     => $order->id,
    ]);

    $orderPayment = OrderPayment::factory()->create([
        'order_id' => $order->id,
        'method'   => 'cashondelivery',
    ]);

    $invoice = Invoice::factory()->create([
        'order_id'              => $order->id,
        'state'                 => 'paid',
        'total_qty'             => 1,
        'base_currency_code'    => $order->base_currency_code,
        'channel_currency_code' => $order->channel_currency_code,
        'order_currency_code'   => $order->order_currency_code,
        'email_sent'            => 1,
        'discount_amount'       => 0,
        'base_discount_amount'  => 0,
        'sub_total'             => $orderItem->base_price,
        'base_sub_total'        => $orderItem->base_price,
        'grand_total'           => $orderItem->price,
        'base_grand_total'      => $orderItem->price,
    ]);

    $invoiceItem = InvoiceItem::factory()->create([
        'invoice_id'           => $invoice->id,
        'order_item_id'        => $orderItem->id,
        'name'                 => $orderItem->name,
        'sku'                  => $orderItem->sku,
        'qty'                  => 1,
        'price'                => $orderItem->price,
        'base_price'           => $orderItem->base_price,
        'total'                => $orderItem->price,
        'base_total'           => $orderItem->base_price,
        'tax_amount'           => (($orderItem->tax_amount / $orderItem->qty_ordered)),
        'base_tax_amount'      => (($orderItem->base_tax_amount / $orderItem->qty_ordered)),
        'discount_amount'      => (($orderItem->discount_amount / $orderItem->qty_ordered)),
        'base_discount_amount' => (($orderItem->base_discount_amount / $orderItem->qty_ordered)),
        'product_id'           => $orderItem->product_id,
        'product_type'         => $orderItem->product_type,
        'additional'           => $orderItem->additional,
    ]);

    $orderTransaction = OrderTransaction::factory()->create([
        'transaction_id' => md5(uniqid()),
        'type'           => 'cashondelivery',
        'payment_method' => 'cashondelivery',
        'status'         => $invoice->state,
        'order_id'       => $invoice->order->id,
        'invoice_id'     => $invoice->id,
        'amount'         => $invoice->grand_total,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.sales.invoices.view', $invoice->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.invoices.view.title', ['invoice_id' => $invoice->increment_id ?? $invoice->id]))
        ->assertSeeText(trans('admin::app.account.edit.back-btn'))
        ->assertSeeText(trans('admin::app.sales.invoices.view.print'));

    $cart->refresh();

    $cartItem->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $orderBillingAddress->refresh();

    $orderShippingAddress->refresh();

    $order->refresh();

    $orderItem->refresh();

    $invoiceItem->refresh();

    $orderTransaction->refresh();

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

        CartAddress::class => [
            $this->prepareAddress($cartShippingAddress),
        ],

        CartShippingRate::class => [
            $this->prepareCartShippingRate($cartShippingRate),
        ],

        CustomerAddress::class => [
            $this->prepareAddress($customerAddress),
        ],

        Order::class => [
            $this->prepareOrder($order),
        ],

        OrderItem::class => [
            $this->prepareOrderItem($orderItem),
        ],

        OrderAddress::class => [
            $this->prepareAddress($orderBillingAddress),

            $this->prepareAddress($orderShippingAddress),
        ],

        OrderPayment::class => [
            $this->prepareOrderPayment($orderPayment),
        ],

        Invoice::class => [
            $this->prepareInvoice($order, $orderItem),
        ],

        InvoiceItem::class => [
            $this->prepareInvoiceItem($invoiceItem),
        ],

        OrderTransaction::class => [
            $this->prepareOrderTransaction($orderTransaction),
        ],
    ]);
});

it('should send duplicate mail to provided email address', function () {
    // Arrange.
    Mail::fake();

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

    $customerAddress = CustomerAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
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
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartShippingAddress->id,
    ]);

    $order = Order::factory()->create([
        'cart_id'                  => $cart->id,
        'customer_id'              => $customer->id,
        'customer_email'           => $customer->email,
        'customer_first_name'      => $customer->first_name,
        'customer_last_name'       => $customer->last_name,
        'status'                   => 'processing',
        'sub_total_invoiced'       => $product->price,
        'base_sub_total_invoiced'  => $product->price,
    ]);

    $orderItem = OrderItem::factory()->create([
        'product_id'           => $product->id,
        'order_id'             => $order->id,
        'sku'                  => $product->sku,
        'type'                 => $product->type,
        'name'                 => $product->name,
        'qty_invoiced'         => 1,
        'base_total_invoiced'  => $product->price,
    ]);

    $orderBillingAddress = OrderAddress::factory()->create([
        ...Arr::except($cartBillingAddress->toArray(), ['id', 'created_at', 'updated_at']),
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
        'order_id'     => $order->id,
    ]);

    $orderShippingAddress = OrderAddress::factory()->create([
        ...Arr::except($cartShippingAddress->toArray(), ['id', 'created_at', 'updated_at']),
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
        'order_id'     => $order->id,
    ]);

    $orderPayment = OrderPayment::factory()->create([
        'order_id' => $order->id,
        'method'   => 'cashondelivery',
    ]);

    $invoice = Invoice::factory()->create([
        'order_id'              => $order->id,
        'state'                 => 'paid',
        'total_qty'             => 1,
        'base_currency_code'    => $order->base_currency_code,
        'channel_currency_code' => $order->channel_currency_code,
        'order_currency_code'   => $order->order_currency_code,
        'email_sent'            => 1,
        'discount_amount'       => 0,
        'base_discount_amount'  => 0,
        'sub_total'             => $orderItem->base_price,
        'base_sub_total'        => $orderItem->base_price,
        'grand_total'           => $orderItem->price,
        'base_grand_total'      => $orderItem->price,
    ]);

    $invoiceItem = InvoiceItem::factory()->create([
        'invoice_id'           => $invoice->id,
        'order_item_id'        => $orderItem->id,
        'name'                 => $orderItem->name,
        'sku'                  => $orderItem->sku,
        'qty'                  => 1,
        'price'                => $orderItem->price,
        'base_price'           => $orderItem->base_price,
        'total'                => $orderItem->price,
        'base_total'           => $orderItem->base_price,
        'tax_amount'           => (($orderItem->tax_amount / $orderItem->qty_ordered)),
        'base_tax_amount'      => (($orderItem->base_tax_amount / $orderItem->qty_ordered)),
        'discount_amount'      => (($orderItem->discount_amount / $orderItem->qty_ordered)),
        'base_discount_amount' => (($orderItem->base_discount_amount / $orderItem->qty_ordered)),
        'product_id'           => $orderItem->product_id,
        'product_type'         => $orderItem->product_type,
        'additional'           => $orderItem->additional,
    ]);

    $orderTransaction = OrderTransaction::factory()->create([
        'transaction_id' => md5(uniqid()),
        'type'           => 'cashondelivery',
        'payment_method' => 'cashondelivery',
        'status'         => $invoice->state,
        'order_id'       => $invoice->order->id,
        'invoice_id'     => $invoice->id,
        'amount'         => $invoice->grand_total,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.send_duplicate_email', $invoice->id), [
        'email' => fake()->email(),
    ])
        ->assertRedirect(route('admin.sales.invoices.view', $invoice->id))
        ->isRedirection();

    $cart->refresh();

    $cartItem->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $orderBillingAddress->refresh();

    $orderShippingAddress->refresh();

    $order->refresh();

    $orderItem->refresh();

    $invoiceItem->refresh();

    $orderTransaction->refresh();

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

        CartAddress::class => [
            $this->prepareAddress($cartShippingAddress),
        ],

        CartShippingRate::class => [
            $this->prepareCartShippingRate($cartShippingRate),
        ],

        CustomerAddress::class => [
            $this->prepareAddress($customerAddress),
        ],

        Order::class => [
            $this->prepareOrder($order),
        ],

        OrderItem::class => [
            $this->prepareOrderItem($orderItem),
        ],

        OrderAddress::class => [
            $this->prepareAddress($orderBillingAddress),

            $this->prepareAddress($orderShippingAddress),
        ],

        OrderPayment::class => [
            $this->prepareOrderPayment($orderPayment),
        ],

        Invoice::class => [
            $this->prepareInvoice($order, $orderItem),
        ],

        InvoiceItem::class => [
            $this->prepareInvoiceItem($invoiceItem),
        ],

        OrderTransaction::class => [
            $this->prepareOrderTransaction($orderTransaction),
        ],
    ]);

    Mail::assertQueued(ShopInvoicedNotification::class);

    Mail::assertQueuedCount(1);
});

it('should print/download the invoice', function () {
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

    $customerAddress = CustomerAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
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
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartShippingAddress->id,
    ]);

    $order = Order::factory()->create([
        'cart_id'                  => $cart->id,
        'customer_id'              => $customer->id,
        'customer_email'           => $customer->email,
        'customer_first_name'      => $customer->first_name,
        'customer_last_name'       => $customer->last_name,
        'status'                   => 'processing',
        'sub_total_invoiced'       => $product->price,
        'base_sub_total_invoiced'  => $product->price,
    ]);

    $orderItem = OrderItem::factory()->create([
        'product_id'           => $product->id,
        'order_id'             => $order->id,
        'sku'                  => $product->sku,
        'type'                 => $product->type,
        'name'                 => $product->name,
        'qty_invoiced'         => 1,
        'base_total_invoiced'  => $product->price,
    ]);

    $orderBillingAddress = OrderAddress::factory()->create([
        ...Arr::except($cartBillingAddress->toArray(), ['id', 'created_at', 'updated_at']),
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
        'order_id'     => $order->id,
    ]);

    $orderShippingAddress = OrderAddress::factory()->create([
        ...Arr::except($cartShippingAddress->toArray(), ['id', 'created_at', 'updated_at']),
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
        'order_id'     => $order->id,
    ]);

    $orderPayment = OrderPayment::factory()->create([
        'order_id' => $order->id,
        'method'   => 'cashondelivery',
    ]);

    $invoice = Invoice::factory()->create([
        'order_id'              => $order->id,
        'state'                 => 'paid',
        'total_qty'             => 1,
        'base_currency_code'    => $order->base_currency_code,
        'channel_currency_code' => $order->channel_currency_code,
        'order_currency_code'   => $order->order_currency_code,
        'email_sent'            => 1,
        'discount_amount'       => 0,
        'base_discount_amount'  => 0,
        'sub_total'             => $orderItem->base_price,
        'base_sub_total'        => $orderItem->base_price,
        'grand_total'           => $orderItem->price,
        'base_grand_total'      => $orderItem->price,
    ]);

    $invoiceItem = InvoiceItem::factory()->create([
        'invoice_id'           => $invoice->id,
        'order_item_id'        => $orderItem->id,
        'name'                 => $orderItem->name,
        'sku'                  => $orderItem->sku,
        'qty'                  => 1,
        'price'                => $orderItem->price,
        'base_price'           => $orderItem->base_price,
        'total'                => $orderItem->price,
        'base_total'           => $orderItem->base_price,
        'tax_amount'           => (($orderItem->tax_amount / $orderItem->qty_ordered)),
        'base_tax_amount'      => (($orderItem->base_tax_amount / $orderItem->qty_ordered)),
        'discount_amount'      => (($orderItem->discount_amount / $orderItem->qty_ordered)),
        'base_discount_amount' => (($orderItem->base_discount_amount / $orderItem->qty_ordered)),
        'product_id'           => $orderItem->product_id,
        'product_type'         => $orderItem->product_type,
        'additional'           => $orderItem->additional,
    ]);

    $orderTransaction = OrderTransaction::factory()->create([
        'transaction_id' => md5(uniqid()),
        'type'           => 'cashondelivery',
        'payment_method' => 'cashondelivery',
        'status'         => $invoice->state,
        'order_id'       => $invoice->order->id,
        'invoice_id'     => $invoice->id,
        'amount'         => $invoice->grand_total,
    ]);

    $fileName = 'invoice-'.$invoice->created_at->format('d-m-Y').'.pdf';

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.sales.invoices.print', $invoice->id))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/pdf')
        ->assertHeader('Content-Disposition', 'attachment; filename="'.$fileName.'"');

    $cart->refresh();

    $cartItem->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $orderBillingAddress->refresh();

    $orderShippingAddress->refresh();

    $order->refresh();

    $orderItem->refresh();

    $invoiceItem->refresh();

    $orderTransaction->refresh();

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

        CartAddress::class => [
            $this->prepareAddress($cartShippingAddress),
        ],

        CartShippingRate::class => [
            $this->prepareCartShippingRate($cartShippingRate),
        ],

        CustomerAddress::class => [
            $this->prepareAddress($customerAddress),
        ],

        Order::class => [
            $this->prepareOrder($order),
        ],

        OrderItem::class => [
            $this->prepareOrderItem($orderItem),
        ],

        OrderAddress::class => [
            $this->prepareAddress($orderBillingAddress),

            $this->prepareAddress($orderShippingAddress),
        ],

        OrderPayment::class => [
            $this->prepareOrderPayment($orderPayment),
        ],

        Invoice::class => [
            $this->prepareInvoice($order, $orderItem),
        ],

        InvoiceItem::class => [
            $this->prepareInvoiceItem($invoiceItem),
        ],

        OrderTransaction::class => [
            $this->prepareOrderTransaction($orderTransaction),
        ],
    ]);
});
