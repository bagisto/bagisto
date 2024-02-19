<?php

use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartItem;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Sales\Models\Refund;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

it('should return the refund index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.sales.refunds.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.refunds.index.title'));
});

it('should fails the validation error when refund items data not provided', function () {
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

    OrderItem::factory()->create([
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

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.sales.refunds.store', $order->id))
        ->assertJsonValidationErrorFor('refund.items')
        ->assertUnprocessable();
});

it('should fails the validation error when refund items data provided with wrong way', function () {
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

    OrderItem::factory()->create([
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

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.sales.refunds.store', $order->id), [
        'refund' => [
            'items' => [
                fake()->word(),
            ],
        ],
    ])
        ->assertJsonValidationErrorFor('refund.items.0')
        ->assertUnprocessable();
});

it('should store the order refund', function () {
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

    OrderItem::factory()->create([
        'product_id'   => $product->id,
        'order_id'     => $order->id,
        'sku'          => $product->sku,
        'type'         => $product->type,
        'name'         => $product->name,
        'qty_shipped'  => 1,
        'qty_invoiced' => 1,
        'qty_canceled' => 1,
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

    foreach ($order->items as $item) {
        foreach ($order->channel->inventory_sources as $inventorySource) {
            $items[$item->id] = $inventorySource->id;
        }
    }

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.sales.refunds.store', $order->id), [
        'refund' => [
            'items'             => $items,
            'shipping'          => 0,
            'adjustment_refund' => '0',
            'adjustment_fee'    => '0',
        ],
    ])
        ->assertRedirect(route('admin.sales.refunds.index'))
        ->isRedirection();

    $this->assertModelWise([
        Refund::class => [
            [
                'state'    => 'refunded',
                'order_id' => $order->id,
            ],
        ],
    ]);
});

it('should return the order refunded data', function () {
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

    OrderItem::factory()->create([
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

    $summary = [
        'subtotal'    => ['price' => 0],
        'discount'    => ['price' => 0],
        'tax'         => ['price' => 0],
        'shipping'    => ['price' => 0],
        'grand_total' => ['price' => 0],
    ];

    $items = [];

    foreach ($order->items as $item) {
        if ($item->qty_to_refund) {
            $items[$item->id] = rand(1, $item->qty_to_refund);
        }
    }

    foreach ($items as $orderItemId => $qty) {
        $orderItem = OrderItem::find($orderItemId);

        $summary['subtotal']['price'] += $orderItem->base_price * $qty;

        $summary['discount']['price'] += ($orderItem->base_discount_amount / $orderItem->qty_ordered) * $qty;

        $summary['tax']['price'] += ($orderItem->tax_amount / $orderItem->qty_ordered) * $qty;
    }

    $summary['shipping']['price'] += $order->base_shipping_invoiced - $order->base_shipping_refunded - $order->base_shipping_discount_amount;

    $summary['grand_total']['price'] += $summary['subtotal']['price'] + $summary['tax']['price'] + $summary['shipping']['price'] - $summary['discount']['price'];

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.sales.refunds.update_qty', $order->id), $items)
        ->assertOk()
        ->assertJsonPath('grand_total.price', $summary['grand_total']['price']);
});

it('should return the view page of refund', function () {
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

    OrderItem::factory()->create([
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

    $refund = Refund::factory()->create([
        'order_id' => $order->id,
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.sales.refunds.view', $refund->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.refunds.view.title', ['refund_id' => $refund->id]))
        ->assertSeeText(trans('admin::app.sales.refunds.view.product-ordered'));
});
