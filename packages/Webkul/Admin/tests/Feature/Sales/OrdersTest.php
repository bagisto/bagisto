<?php

use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Models\Customer;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

afterEach(function () {
    // Cleaning up the row  which are creating
    Customer::query()->delete();
    Order::query()->delete();
    OrderPayment::query()->delete();
    CartItem::query()->delete();
    Cart::query()->delete();
    Product::query()->delete();
});

it('should return the index page of Orders page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.sales.orders.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.orders.index.title'));
});

it('should return the view page of order', function () {
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
        'cart_id' => CartItem::factory()->create([
            'product_id' => $product->id,
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

    // Act And Assert
    $this->loginAsAdmin();

    get(route('admin.sales.orders.view', $order->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.orders.view.' . $order->status))
        ->assertSeeText(trans('admin::app.sales.orders.view.title', ['order_id' => $order->increment_id]))
        ->assertSeeText(trans('admin::app.sales.orders.view.summary-tax'))
        ->assertSeeText(trans('admin::app.sales.orders.view.summary-grand-total'))
        ->assertSeeText(trans('admin::app.sales.orders.view.comments'));
});

it('should cancel the order', function () {
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
        'cart_id' => CartItem::factory()->create([
            'product_id' => $product->id,
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

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.sales.orders.cancel', $order->id))
        ->assertRedirect(route('admin.sales.orders.view', $order->id))
        ->isRedirection();

    $this->assertDatabaseHas('orders', [
        'id'     => $order->id,
        'status' => 'canceled',
    ]);
});

it('should comment to the order', function () {
    // Act and Assert
    $this->loginAsAdmin();

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
        'cart_id' => CartItem::factory()->create([
            'product_id' => $product->id,
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

    postJson(route('admin.sales.orders.comment', $order->id), [
        'comment' => $comment = fake()->word(),
    ])
        ->assertRedirect(route('admin.sales.orders.view', $order->id))
        ->isRedirection();

    $this->assertDatabaseHas('order_comments', [
        'order_id' => $order->id,
        'comment'  => $comment,
    ]);
});

it('should search the order', function () {
    // Act and Assert
    $this->loginAsAdmin();

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
        'cart_id' => CartItem::factory()->create([
            'product_id' => $product->id,
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

    get(route('admin.sales.orders.search'), [
        'query' => fake()->randomElement(['pending', 'completed', 'processing']),
    ])
        ->assertOk()
        ->assertJsonPath('total', 1)
        ->assertJsonPath('to', 1)
        ->assertJsonPath('path', route('admin.sales.orders.search'))
        ->assertJsonPath('data.0.id', $order->id)
        ->assertJsonPath('data.0.status', $order->status)
        ->assertJsonPath('data.0.customer_email', $order->customer_email);
});
