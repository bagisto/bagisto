<?php

use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Core\Models\Visit;
use Webkul\Customer\Models\Customer;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductReview;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;

use function Pest\Laravel\get;

afterEach(function () {
    // Cleaning up the row which are creating.
    Customer::query()->delete();
    OrderAddress::query()->delete();
    Order::query()->delete();
    OrderPayment::query()->delete();
    CartItem::query()->delete();
    Cart::query()->delete();
    Product::query()->delete();
    Invoice::query()->delete();
    Customer::query()->delete();
    ProductReview::query()->delete();
    Visit::query()->delete();
});

it('should return the index page of customers reporting', function () {
    // Act and Assert
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
    // Act and Assert
    $this->loginAsAdmin();

    Customer::factory()->count(2)->create();

    get(route('admin.reporting.customers.stats', [
        'type' => 'total-customers',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.over_time.current.30.total', 2);
});

it('should return the customers with most reviews stats report', function () {
    // Arrange
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

    ProductReview::factory()->count(2)->create([
        'customer_id' => $customer->id,
        'name'        => $customer->name,
        'status'      => 'approved',
        'product_id'  => $product->id,
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.customers.stats', [
        'type' => 'customers-with-most-reviews',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.0.email', $customer->email)
        ->assertJsonPath('statistics.0.id', $customer->id)
        ->assertJsonPath('statistics.0.reviews', 2);
});

it('should return the top customers group stats report', function () {
    // Arrange
    $customer = Customer::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.customers.stats', [
        'type' => 'top-customer-groups',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.0.id', $customer->id)
        ->assertJsonPath('statistics.0.group_name', 'General');
});

it('should return the customers traffic stats report', function () {
    // Arrange
    $customer = Customer::factory()->create();

    visitor()->visit($customer);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.customers.stats', [
        'type' => 'customers-traffic',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.total.current', 1)
        ->assertJsonPath('statistics.unique.current', 1);
});

it('should return the customers with most orders stats report', function () {
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

    $order = Order::factory()->create([
        'cart_id' => $cartId = CartItem::factory()->create([
            'product_id' => $product->id,
            'sku'        => $product->sku,
            'type'       => $product->type,
            'name'       => $product->name,
        ])->id,
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

    Invoice::factory()->create([
        'order_id'      => $order->id,
        'state'         => 'paid',
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.customers.stats', [
        'type' => 'customers-with-most-orders',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.0.id', $customer->id)
        ->assertJsonPath('statistics.0.email', $customer->email)
        ->assertJsonPath('statistics.0.full_name', $customer->name)
        ->assertJsonPath('statistics.0.orders', $customer->orders()->count());
});

it('should return the customers with most sales stats report', function () {
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

    $order = Order::factory()->create([
        'cart_id' => $cartId = CartItem::factory()->create([
            'product_id' => $product->id,
            'sku'        => $product->sku,
            'type'       => $product->type,
            'name'       => $product->name,
        ])->id,
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

    Invoice::factory()->create([
        'order_id'      => $order->id,
        'state'         => 'paid',
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.customers.stats', [
        'type' => 'customers-with-most-sales',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.0.id', $customer->id)
        ->assertJsonPath('statistics.0.email', $customer->email)
        ->assertJsonPath('statistics.0.full_name', $customer->name)
        ->assertJsonPath('statistics.0.orders', $customer->orders()->count());
});
