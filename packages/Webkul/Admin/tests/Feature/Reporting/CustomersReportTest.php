<?php

use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartItem;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\ProductReview;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\InvoiceItem;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;

use function Pest\Laravel\get;

it('should return the index page of customers reporting', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.reporting.customers.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.reporting.customers.index.title'))
        ->assertSeeText(trans('admin::app.reporting.customers.index.customers-with-most-orders'))
        ->assertSeeText(trans('admin::app.reporting.customers.index.customers-with-most-reviews'))
        ->assertSeeText(trans('admin::app.reporting.customers.index.customers-with-most-sales'))
        ->assertSeeText(trans('admin::app.reporting.customers.index.top-customer-groups'))
        ->assertSeeText(trans('admin::app.reporting.customers.index.total-customers'))
        ->assertSeeText(trans('admin::app.reporting.customers.index.customers-traffic'));
});

it('should return the customers stats report', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    Customer::factory()->count(2)->create();

    get(route('admin.reporting.customers.stats', [
        'type' => 'total-customers',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.over_time.current.30.total', 2);
});

it('should return the customers with most reviews stats report', function () {
    // Arrange.
    $customer = Customer::factory()->create();

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

    $productReviews = ProductReview::factory()->count(2)->create([
        'status'      => 'approved',
        'customer_id' => $customer->id,
        'name'        => $customer->name,
        'product_id'  => $product->id,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.reporting.customers.stats', [
        'type' => 'customers-with-most-reviews',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.0.email', $customer->email)
        ->assertJsonPath('statistics.0.id', $customer->id)
        ->assertJsonPath('statistics.0.reviews', $productReviews->count());

    foreach ($productReviews as $productReview) {
        $this->assertDatabaseHas('product_reviews', [
            'customer_id' => $productReview->customer_id,
            'product_id'  => $productReview->product_id,
            'status'      => $productReview->status,
            'name'        => $productReview->name,
        ]);
    }
});

it('should return the top customers group stats report', function () {
    // Arrange.
    $customer = Customer::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.reporting.customers.stats', [
        'type' => 'top-customer-groups',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.0.id', $customer->id)
        ->assertJsonPath('statistics.0.group_name', 'General');
});

it('should return the customers traffic stats report', function () {
    // Arrange.
    $customer = Customer::factory()->create();

    visitor()->visit($customer);

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.reporting.customers.stats', [
        'type' => 'customers-traffic',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.total.current', 1)
        ->assertJsonPath('statistics.unique.current', 1);
});

it('should return the customers with most orders stats report', function () {
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

    OrderPayment::factory()->create([
        'order_id' => $order->id,
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

    $invoice = Invoice::factory()->create([
        'order_id' => $order->id,
        'state'    => 'paid',
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

    $orderPayment = OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.reporting.customers.stats', [
        'type' => 'customers-with-most-orders',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.0.id', $customer->id)
        ->assertJsonPath('statistics.0.email', $customer->email)
        ->assertJsonPath('statistics.0.full_name', $customer->name)
        ->assertJsonPath('statistics.0.orders', $customer->orders()->count());

    $cart->refresh();

    $cartItem->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $orderBillingAddress->refresh();

    $orderShippingAddress->refresh();

    $order->refresh();

    $orderItem->refresh();

    $invoiceItem->refresh();

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

        InvoiceItem::class => [
            $this->prepareInvoiceItem($invoiceItem),
        ],
    ]);
});

it('should return the customers with most sales stats report', function () {
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

    OrderPayment::factory()->create([
        'order_id' => $order->id,
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

    $invoice = Invoice::factory()->create([
        'order_id' => $order->id,
        'state'    => 'paid',
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

    $orderPayment = OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.reporting.customers.stats', [
        'type' => 'customers-with-most-sales',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.0.id', $customer->id)
        ->assertJsonPath('statistics.0.email', $customer->email)
        ->assertJsonPath('statistics.0.full_name', $customer->name)
        ->assertJsonPath('statistics.0.orders', $customer->orders()->count());

    $cart->refresh();

    $cartItem->refresh();

    $cartBillingAddress->refresh();

    $cartShippingAddress->refresh();

    $orderBillingAddress->refresh();

    $orderShippingAddress->refresh();

    $order->refresh();

    $orderItem->refresh();

    $invoiceItem->refresh();

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

        InvoiceItem::class => [
            $this->prepareInvoiceItem($invoiceItem),
        ],
    ]);
});
