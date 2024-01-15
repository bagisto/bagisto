<?php

use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Models\Customer;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

afterEach(function () {
    // Cleaning up the row  which are creating
    Customer::query()->delete();
    OrderAddress::query()->delete();
    Order::query()->delete();
    OrderPayment::query()->delete();
    CartItem::query()->delete();
    Cart::query()->delete();
    Invoice::query()->delete();
    Product::query()->delete();
});

it('should store the index page customers orders', function () {
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

    OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.customers.account.orders.cancel', $order->id))
        ->assertRedirect();

    $this->assertDatabaseHas('orders', [
        'id'     => $order->id,
        'status' => 'canceled',
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

    OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    $invoice = Invoice::factory([
        'order_id' => $order->id,
        'state'    => 'paid',
    ])->create();

    // Act and Assert
    $this->loginAsCustomer($customer);

    getJson(route('shop.customers.account.orders.print-invoice', $invoice->id))
        ->assertDownload('invoice-' . $invoice->created_at->format('d-m-Y') . '.pdf');
});
