<?php

use Carbon\Carbon;
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

use function Pest\Laravel\get;

it('should return the sales index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.reporting.sales.index.title'))
        ->assertSeeText(trans('admin::app.reporting.sales.index.refunds'))
        ->assertSeeText(trans('admin::app.reporting.sales.index.total-sales'));
});

it('should returns the sales stats', function () {
    // Arrange
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

    CartItem::factory()->create([
        'quantity'          => $quantity = 1,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'cart_id'           => $cart->id,
        'price'             => $convertedPrice = core()->convertPrice($price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $quantity,
        'base_total'        => $price * $quantity,
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $quantity,
        'base_total_weight' => ($product->weight ?? 0) * $quantity,
        'additional'        => [
            'quantity'   => $quantity,
            'product_id' => $product->id,
        ],
    ]);

    $order = Order::factory()->create([
        'cart_id'             => $cart->id,
        'customer_id'         => $customer->id,
        'customer_email'      => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
    ]);

    $orderItem = OrderItem::factory()->create([
        'product_id'   => $product->id,
        'order_id'     => $order->id,
        'sku'          => $product->sku,
        'type'         => $product->type,
        'name'         => $product->name,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cart->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cart->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    $invoice = Invoice::factory()->create([
        'order_id' => $order->id,
        'state'    => 'paid',
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

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'type' => 'total-sales',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.sales.progress', 100)
        ->assertJsonPath('statistics.sales.formatted_total', core()->formatBasePrice($order->grand_total))
        ->assertJsonPath('statistics.over_time.current.30.count', 1);
});

it('should returns the purchase funnel stats', function () {
    // Arrange
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

    visitor()->visit($customer);

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

    CartItem::factory()->create([
        'quantity'          => $quantity = 1,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'cart_id'           => $cart->id,
        'price'             => $convertedPrice = core()->convertPrice($price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $quantity,
        'base_total'        => $price * $quantity,
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $quantity,
        'base_total_weight' => ($product->weight ?? 0) * $quantity,
        'additional'        => [
            'quantity'   => $quantity,
            'product_id' => $product->id,
        ],
    ]);

    CustomerAddress::factory()->create([
        'customer_id'  => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
    ]);

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    CartPayment::factory()->create([
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
        'cart_id'      => $cart->id,
    ]);

    CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartBillingAddress->id,
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

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cart->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cart->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $invoice = Invoice::factory()->create([
        'order_id' => $order->id,
        'state'    => 'paid',
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

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'type' => 'purchase-funnel',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.visitors.total', 1)
        ->assertJsonPath('statistics.carts.total', 1)
        ->assertJsonPath('statistics.orders.total', 1);
});

it('should returns the abandoned carts stats', function () {
    // Arrange
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

    CartItem::factory()->create([
        'product_id' => $product->id,
        'cart_id'    => Cart::factory()->create([
            'customer_id' => Customer::factory()->create(),
            'created_at'  => Carbon::now()->subMonth()->toDateString(),
        ])->id,
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'start' => Carbon::now()->copy()->subMonth()->toDateString(),
        'type'  => 'abandoned-carts',
        'end'   => Carbon::now()->addMonths(5)->toDateString(),
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.carts.current', 1);
});

it('should returns the total orders stats', function () {
    // Arrange
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

    CartItem::factory()->create([
        'quantity'          => $quantity = 1,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'cart_id'           => $cart->id,
        'price'             => $convertedPrice = core()->convertPrice($price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $quantity,
        'base_total'        => $price * $quantity,
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $quantity,
        'base_total_weight' => ($product->weight ?? 0) * $quantity,
        'additional'        => [
            'quantity'   => $quantity,
            'product_id' => $product->id,
        ],
    ]);

    CustomerAddress::factory()->create([
        'customer_id'  => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
    ]);

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    CartPayment::factory()->create([
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
        'cart_id'      => $cart->id,
    ]);

    CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartBillingAddress->id,
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

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cart->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cart->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $invoice = Invoice::factory()->create([
        'order_id'      => $order->id,
        'state'         => 'paid',
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

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'start' => Carbon::now()->copy()->subMonth(),
        'type'  => 'total-orders',
        'end'   => Carbon::now()->addMonths(5),
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.orders.current', 1);
});

it('should returns the average sale stats', function () {
    // Arrange
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

    CartItem::factory()->create([
        'quantity'          => $quantity = 1,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'cart_id'           => $cart->id,
        'price'             => $convertedPrice = core()->convertPrice($price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $quantity,
        'base_total'        => $price * $quantity,
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $quantity,
        'base_total_weight' => ($product->weight ?? 0) * $quantity,
        'additional'        => [
            'quantity'   => $quantity,
            'product_id' => $product->id,
        ],
    ]);

    CustomerAddress::factory()->create([
        'customer_id'  => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
    ]);

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    CartPayment::factory()->create([
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
        'cart_id'      => $cart->id,
    ]);

    CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartBillingAddress->id,
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

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cart->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cart->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $invoice = Invoice::factory()->create([
        'order_id' => $order->id,
        'state'    => 'paid',
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

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'start' => Carbon::now()->copy()->subMonth(),
        'type'  => 'average-sales',
        'end'   => Carbon::now()->addMonths(5),
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.sales.progress', 100)
        ->assertJsonPath('statistics.sales.formatted_total', core()->formatBasePrice($order->grand_total))
        ->assertJsonPath('statistics.over_time.current.30.count', 1);
});

it('should returns the shipping collected stats', function () {
    // Arrange
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

    CartItem::factory()->create([
        'quantity'          => $quantity = 1,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'cart_id'           => $cart->id,
        'price'             => $convertedPrice = core()->convertPrice($price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $quantity,
        'base_total'        => $price * $quantity,
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $quantity,
        'base_total_weight' => ($product->weight ?? 0) * $quantity,
        'additional'        => [
            'quantity'   => $quantity,
            'product_id' => $product->id,
        ],
    ]);

    CustomerAddress::factory()->create([
        'customer_id'  => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
    ]);

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    CartPayment::factory()->create([
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
        'cart_id'      => $cart->id,
    ]);

    CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartBillingAddress->id,
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

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cart->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cart->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $invoice = Invoice::factory()->create([
        'order_id'      => $order->id,
        'state'         => 'paid',
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

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'start' => Carbon::now()->copy()->subMonth(),
        'type'  => 'shipping-collected',
        'end'   => Carbon::now()->addMonths(5),
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.top_methods.0.title', 'Free Shipping');
});

it('should returns the tax collected stats', function () {
    // Arrange
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

    CartItem::factory()->create([
        'quantity'          => $quantity = 1,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'cart_id'           => $cart->id,
        'price'             => $convertedPrice = core()->convertPrice($price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $quantity,
        'base_total'        => $price * $quantity,
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $quantity,
        'base_total_weight' => ($product->weight ?? 0) * $quantity,
        'additional'        => [
            'quantity'   => $quantity,
            'product_id' => $product->id,
        ],
    ]);

    CustomerAddress::factory()->create([
        'customer_id'  => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
    ]);

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    CartPayment::factory()->create([
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
        'cart_id'      => $cart->id,
    ]);

    CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartBillingAddress->id,
    ]);

    $order = Order::factory()->create([
        'base_tax_amount_invoiced' => $taxInvoiced = rand(0, 50),
        'cart_id'                  => $cart->id,
        'customer_id'              => $customer->id,
        'customer_email'           => $customer->email,
        'customer_first_name'      => $customer->first_name,
        'customer_last_name'       => $customer->last_name,
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

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cart->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cart->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $invoice = Invoice::factory()->create([
        'order_id'      => $order->id,
        'state'         => 'paid',
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

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'start' => Carbon::now()->copy()->subMonth(),
        'type'  => 'tax-collected',
        'end'   => Carbon::now()->addMonths(5),
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.tax_collected.current', $taxInvoiced);
});

it('should returns the refunds stats', function () {
    // Arrange
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

    CartItem::factory()->create([
        'quantity'          => $quantity = 1,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'cart_id'           => $cart->id,
        'price'             => $convertedPrice = core()->convertPrice($price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $quantity,
        'base_total'        => $price * $quantity,
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $quantity,
        'base_total_weight' => ($product->weight ?? 0) * $quantity,
        'additional'        => [
            'quantity'   => $quantity,
            'product_id' => $product->id,
        ],
    ]);

    CustomerAddress::factory()->create([
        'customer_id'  => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
    ]);

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    CartPayment::factory()->create([
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
        'cart_id'      => $cart->id,
    ]);

    CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartBillingAddress->id,
    ]);

    $order = Order::factory()->create([
        'base_tax_amount_invoiced'  => $taxInvoiced = rand(20, 50),
        'base_grand_total_refunded' => $grantTotalRefunded = $taxInvoiced - 10,
        'cart_id'                   => $cart->id,
        'customer_id'               => $customer->id,
        'customer_email'            => $customer->email,
        'customer_first_name'       => $customer->first_name,
        'customer_last_name'        => $customer->last_name,
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

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cart->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cart->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $invoice = Invoice::factory()->create([
        'order_id'      => $order->id,
        'state'         => 'paid',
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

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'start' => Carbon::now()->copy()->subMonth(),
        'type'  => 'refunds',
        'end'   => Carbon::now()->addMonths(5),
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.refunds.current', $grantTotalRefunded);
});

it('should returns the top payment methods stats', function () {
    // Arrange
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

    CartItem::factory()->create([
        'quantity'          => $quantity = 1,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'cart_id'           => $cart->id,
        'price'             => $convertedPrice = core()->convertPrice($price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $quantity,
        'base_total'        => $price * $quantity,
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $quantity,
        'base_total_weight' => ($product->weight ?? 0) * $quantity,
        'additional'        => [
            'quantity'   => $quantity,
            'product_id' => $product->id,
        ],
    ]);

    CustomerAddress::factory()->create([
        'customer_id'  => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
    ]);

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    CartPayment::factory()->create([
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
        'cart_id'      => $cart->id,
    ]);

    CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartBillingAddress->id,
    ]);

    $order = Order::factory()->create([
        'base_tax_amount_invoiced'  => $taxInvoiced = rand(20, 50),
        'base_grand_total_refunded' => $taxInvoiced - 10,
        'cart_id'                   => $cart->id,
        'customer_id'               => $customer->id,
        'customer_email'            => $customer->email,
        'customer_first_name'       => $customer->first_name,
        'customer_last_name'        => $customer->last_name,
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

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cart->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cart->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $invoice = Invoice::factory()->create([
        'order_id' => $order->id,
        'state'    => 'paid',
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

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'start' => Carbon::now()->copy()->subMonth(),
        'type'  => 'top-payment-methods',
        'end'   => Carbon::now()->addMonths(5),
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.0.method', 'cashondelivery')
        ->assertJsonPath('statistics.0.title', 'Cash On Delivery');
});

it('should return the view page of sales stats', function () {
    // Arrange
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

    CartItem::factory()->create([
        'product_id' => $product->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
        'cart_id'    => $cartId = Cart::factory()->create([
            'customer_id'         => $customer->id,
            'customer_email'      => $customer->email,
            'customer_first_name' => $customer->first_name,
            'customer_last_name'  => $customer->last_name,
        ])->id,
    ]);

    $order = Order::factory()->create([
        'cart_id'             => $cartId,
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

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    $invoice = Invoice::factory()->create([
        'order_id' => $order->id,
        'state'    => 'paid',
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

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.view', [
        'type' => 'total-sales',
    ]))
        ->assertOk()
        ->assertSeeText(trans('admin::app.reporting.sales.index.total-sales'));
});

it('should export the sales stats', function () {
    // Arrange
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

    CartItem::factory()->create([
        'quantity'          => $quantity = 1,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'name'              => $product->name,
        'type'              => $product->type,
        'cart_id'           => $cart->id,
        'price'             => $convertedPrice = core()->convertPrice($price),
        'base_price'        => $price,
        'total'             => $convertedPrice * $quantity,
        'base_total'        => $price * $quantity,
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $quantity,
        'base_total_weight' => ($product->weight ?? 0) * $quantity,
        'additional'        => [
            'quantity'   => $quantity,
            'product_id' => $product->id,
        ],
    ]);

    CustomerAddress::factory()->create([
        'customer_id'  => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
    ]);

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    CartPayment::factory()->create([
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
        'cart_id'      => $cart->id,
    ]);

    CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartBillingAddress->id,
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

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cart->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cart->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $invoice = Invoice::factory()->create([
        'order_id'      => $order->id,
        'state'         => 'paid',
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

    // Act and Assert
    $this->loginAsAdmin();

    $period = fake()->randomElement(['day', 'month', 'year']);

    $start = Carbon::now();

    if ($period === 'day') {
        $end = $start->copy()->addDay();
    } elseif ($period === 'month') {
        $end = $start->copy()->addMonth();
    } else {
        $end = $start->copy()->addYear();
    }

    get(route('admin.reporting.sales.export', [
        'start'  => $start->toDateString(),
        'end'    => $end->toDateString(),
        'format' => $format = fake()->randomElement(['csv', 'xls']),
        'period' => $period,
        'type'   => 'total-sales',
    ]))
        ->assertOk()
        ->assertDownload('total-sales.'.$format);
});
