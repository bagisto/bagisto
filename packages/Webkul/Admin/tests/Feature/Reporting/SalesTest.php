<?php

use Carbon\Carbon;
use Webkul\Checkout\Models\CartItem;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;

use function Pest\Laravel\get;

afterEach(function () {
    // Cleaning up the row  which are creating

});

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

    $order = Order::factory()->create([
        'cart_id' => $cartId = CartItem::factory()->create([
            'product_id' => $product->id,
        ])->id,
    ]);

    OrderItem::factory()->create([
        'product_id' => $product->id,
        'order_id'   => $order->id,
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
        'order_id' => $order->id,
        'state'    => 'paid',
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

    $order = Order::factory()->create([
        'cart_id' => $cartId = CartItem::factory()->create([
            'product_id' => $product->id,
        ])->id,
    ]);

    OrderItem::factory()->create([
        'product_id' => $product->id,
        'order_id'   => $order->id,
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
        'order_id' => $order->id,
        'state'    => 'paid',
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

    $order = Order::factory()->create([
        'cart_id' => $cartId = CartItem::factory()->create([
            'product_id' => $product->id,
        ])->id,
    ]);

    OrderItem::factory()->create([
        'product_id' => $product->id,
        'order_id'   => $order->id,
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
        'order_id' => $order->id,
        'state'    => 'paid',
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
        ->assertDownload('total-sales.' . $format);
});
