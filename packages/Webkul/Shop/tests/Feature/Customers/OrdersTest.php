<?php

use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartItem;
use Webkul\Checkout\Models\CartPayment;
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
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

it('should returns the index page customers orders', function () {
    // Act and Assert
    $this->loginAsCustomer();

    get(route('shop.customers.account.orders.index'))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.account.orders.title'));
});

it('should view the order', function () {
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

    CustomerAddress::factory()->create([
        'cart_id'      => $cartId,
        'customer_id'  => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cartId,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cartId,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    CartPayment::factory()->create([
        'cart_id'      => $cartId,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    $order = Order::factory()->create([
        'cart_id'             => $cartId,
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
        'cart_id'      => $cartId,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    OrderAddress::factory()->create([
        'cart_id'      => $cartId,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    // Act and Assert
    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.orders.view', $order->id))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.account.orders.view.information.sku'))
        ->assertSeeText(trans('shop::app.customers.account.orders.view.information.product-name'))
        ->assertSeeText(trans('shop::app.customers.account.orders.view.information.total-due'))
        ->assertSeeText(trans('shop::app.customers.account.orders.view.page-title', ['order_id' => $order->increment_id]));
});

it('should cancel the customer order', function () {
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

    CustomerAddress::factory()->create([
        'cart_id'      => $cartId,
        'customer_id'  => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cartId,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cartId,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    CartPayment::factory()->create([
        'cart_id'      => $cartId,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
    ]);

    $order = Order::factory()->create([
        'cart_id'             => $cartId,
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
        'cart_id'      => $cartId,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    OrderAddress::factory()->create([
        'cart_id'      => $cartId,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.customers.account.orders.cancel', $order->id))
        ->assertRedirect();

    $this->assertModelWise([
        Order::class => [
            [
                'id'     => $order->id,
                'status' => 'canceled',
            ],
        ],
    ]);
});

it('should print the order invoice', function () {
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

    CustomerAddress::factory()->create([
        'cart_id'      => $cartId,
        'customer_id'  => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cartId,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    CartAddress::factory()->create([
        'cart_id'      => $cartId,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    CartPayment::factory()->create([
        'cart_id'      => $cartId,
        'method'       => $paymentMethod = 'cashondelivery',
        'method_title' => core()->getConfigData('sales.payment_methods.'.$paymentMethod.'.title'),
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

    OrderAddress::factory()->create([
        'cart_id'      => $cartId,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    OrderAddress::factory()->create([
        'cart_id'      => $cartId,
        'customer_id'  => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    $invoice = Invoice::factory([
        'order_id' => $order->id,
        'state'    => 'paid',
    ])->create();

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
    $this->loginAsCustomer($customer);

    getJson(route('shop.customers.account.orders.print-invoice', $invoice->id))
        ->assertDownload('invoice-'.$invoice->created_at->format('d-m-Y').'.pdf');
});
