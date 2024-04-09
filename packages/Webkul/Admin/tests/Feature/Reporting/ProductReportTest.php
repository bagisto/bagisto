<?php

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartItem;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Customer\Models\Wishlist;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Marketing\Models\SearchTerm;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductReview;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\InvoiceItem;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Sales\Models\OrderTransaction;

use function Pest\Laravel\get;

it('should returns the reporting product page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.reporting.products.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.reporting.products.index.title'))
        ->assertSeeText(trans('admin::app.reporting.products.index.last-search-terms'))
        ->assertSeeText(trans('admin::app.reporting.products.index.products-with-most-reviews'))
        ->assertSeeText(trans('admin::app.reporting.products.index.products-with-most-visits'))
        ->assertSeeText(trans('admin::app.reporting.products.index.view-details'))
        ->assertSeeText(trans('admin::app.reporting.products.index.top-selling-products-by-quantity'))
        ->assertSeeText(trans('admin::app.reporting.products.index.top-selling-products-by-revenue'))
        ->assertSeeText(trans('admin::app.reporting.products.index.view-details'))
        ->assertSeeText(trans('admin::app.reporting.products.index.top-search-terms'));
});

it('should return the product reporting stats', function () {
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

    get(route('admin.reporting.products.stats', [
        'type' => 'total-sold-quantities',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.quantities.current', 0)
        ->assertJsonPath('statistics.over_time.current.30.total', 1);

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

it('should return the total products added to wishlist reporting stats', function () {
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

    Wishlist::factory()->create([
        'channel_id'  => core()->getDefaultChannel()->id,
        'product_id'  => $product->id,
        'customer_id' => $customer->id,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.reporting.products.stats', [
        'type' => 'total-products-added-to-wishlist',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.wishlist.current', 1)
        ->assertJsonPath('statistics.over_time.current.30.total', 1);
});

it('should return the top selling products by revenue reporting stats', function () {
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

    $response = get(route('admin.reporting.products.stats', [
        'type' => 'top-selling-products-by-revenue',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.0.id', $orderItem->product_id)
        ->assertJsonPath('statistics.0.name', $orderItem->product?->name)
        ->assertJsonPath('statistics.0.formatted_price', core()->formatBasePrice($orderItem->price))
        ->assertJsonPath('statistics.0.formatted_revenue', core()->formatBasePrice($orderItem->base_total_invoiced));

    $this->assertPrice($orderItem->product->price, $response->json('statistics.0.price'));

    $this->assertPrice($orderItem->base_total_invoiced, $response->json('statistics.0.revenue'));

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

it('should return the top selling products by quantity reporting stats', function () {
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
        'status'         => 'paid',
        'status'         => $invoice->state,
        'order_id'       => $invoice->order->id,
        'invoice_id'     => $invoice->id,
        'amount'         => $invoice->grand_total,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    $response = get(route('admin.reporting.products.stats', [
        'type' => 'top-selling-products-by-quantity',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.0.id', $orderItem->product->id);

    $this->assertPrice($orderItem->product->price, $response->json('statistics.0.price'));

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

it('should return the products with most reviews reporting stats', function () {
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

    ProductReview::factory()->count(2)->create([
        'status'      => 'approved',
        'comment'     => fake()->sentence(20),
        'product_id'  => $product->id,
        'customer_id' => $customer->id,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.reporting.products.stats', [
        'type' => 'products-with-most-reviews',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.0.product_id', $product->id)
        ->assertJsonPath('statistics.0.reviews', 2)
        ->assertJsonPath('statistics.0.product.id', $product->id)
        ->assertJsonPath('statistics.0.product.type', $product->type);
});

it('should return the products with most visits reporting stats', function () {
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

    visitor()->visit($product);

    get(route('admin.reporting.products.stats', [
        'type' => 'products-with-most-visits',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.0.visitable_id', $product->id)
        ->assertJsonPath('statistics.0.visits', 1)
        ->assertJsonPath('statistics.0.visitable_type', Product::class)
        ->assertJsonPath('statistics.0.visitable.id', $product->id)
        ->assertJsonPath('statistics.0.visitable.type', $product->type);
});

it('should return the last search terms reporting stats', function () {
    // Arrange.
    (new ProductFaker([
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

    $searchTerm = SearchTerm::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.reporting.products.stats', [
        'type' => 'last-search-terms',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.0.id', $searchTerm->id)
        ->assertJsonPath('statistics.0.term', $searchTerm->term)
        ->assertJsonPath('statistics.0.redirect_url', $searchTerm->redirect_url);
});

it('should return top search terms reporting stats', function () {
    // Arrange.
    (new ProductFaker([
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

    $searchTerm = SearchTerm::factory()->create();

    get(route('admin.reporting.products.stats', [
        'type' => 'top-search-terms',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.0.id', $searchTerm->id)
        ->assertJsonPath('statistics.0.term', $searchTerm->term)
        ->assertJsonPath('statistics.0.redirect_url', $searchTerm->redirect_url);
});

it('should return the downloadable response for product stats', function () {
    // Arrange.
    $period = fake()->randomElement(['day', 'month', 'year']);

    $start = Carbon::now();

    if ($period === 'day') {
        $end = $start->copy()->addDay();
    } elseif ($period === 'month') {
        $end = $start->copy()->addMonth();
    } else {
        $end = $start->copy()->addYear();
    }

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.reporting.products.export', [
        'start'  => $start->toDateString(),
        'end'    => $end->toDateString(),
        'format' => $format = fake()->randomElement(['csv', 'xls']),
        'period' => $period,
        'type'   => 'total-sold-quantities',
    ]))
        ->assertOk()
        ->assertDownload('total-sold-quantities.'.$format);
});

it('should return the product view page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.reporting.products.view', [
        'type' => 'total-sold-quantities',
    ]))
        ->assertOk()
        ->assertSeeText(trans('admin::app.reporting.products.index.total-sold-quantities'))
        ->assertSeeText(trans('admin::app.reporting.view.export-csv'))
        ->assertSeeText(trans('admin::app.reporting.view.export-xls'));
});

it('should returns the report the product', function () {
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

    OrderAddress::factory()->create([
        ...Arr::except($cartBillingAddress->toArray(), ['id', 'created_at', 'updated_at']),
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
        'order_id'     => $order->id,
    ]);

    OrderAddress::factory()->create([
        ...Arr::except($cartShippingAddress->toArray(), ['id', 'created_at', 'updated_at']),
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
        'order_id'     => $order->id,
    ]);

    OrderPayment::factory()->create([
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

    InvoiceItem::factory()->create([
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

    OrderTransaction::factory()->create([
        'transaction_id' => md5(uniqid()),
        'type'           => 'cashondelivery',
        'payment_method' => 'cashondelivery',
        'status'         => 'paid',
        'status'         => $invoice->state,
        'order_id'       => $invoice->order->id,
        'invoice_id'     => $invoice->id,
        'amount'         => $invoice->grand_total,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.reporting.products.view.stats', [
        'type'   => 'total-sold-quantities',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.columns.0.key', 'label')
        ->assertJsonPath('statistics.columns.1.key', 'total')
        ->assertJsonPath('statistics.records.30.total', 1);
});
