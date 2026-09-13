<?php

use Webkul\Core\Models\Channel;
use Webkul\Customer\Models\Customer;

use function Pest\Laravel\get;

// ============================================================================
// Index
// ============================================================================

it('should return the dashboard index page', function () {
    $this->loginAsAdmin();

    get(route('admin.dashboard.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.dashboard.index.title'))
        ->assertSeeText(trans('admin::app.dashboard.index.overall-details'))
        ->assertSeeText(trans('admin::app.dashboard.index.total-sales'))
        ->assertSeeText(trans('admin::app.dashboard.index.today-sales'));
});

it('should deny guest access to the dashboard', function () {
    get(route('admin.dashboard.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Stats
// ============================================================================

it('should return the overall dashboard stats', function () {
    $channel = Channel::factory()->create();

    $customer = Customer::factory()->create(['channel_id' => $channel->id]);

    $order = $this->createOrder(['channel_id' => $channel->id], [['price' => 100]], $customer);

    $this->invoiceOrder($order);

    $this->loginAsAdmin();

    $response = get(route('admin.dashboard.stats', ['type' => 'over-all', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonPath('statistics.total_customers.current', 1)
        ->assertJsonPath('statistics.total_orders.current', 1)
        ->assertJsonPath('statistics.total_sales.formatted_total', core()->formatBasePrice(100))
        ->assertJsonPath('statistics.avg_sales.formatted_total', core()->formatBasePrice(100));

    expect($response->json('statistics.total_sales.current'))->toBePrice(100)
        ->and($response->json('statistics.avg_sales.current'))->toBePrice(100);
});

it('should return the today dashboard stats', function () {
    $channel = Channel::factory()->create();

    $order = $this->createOrder(['channel_id' => $channel->id], [['price' => 100]]);

    $this->invoiceOrder($order);

    $this->loginAsAdmin();

    get(route('admin.dashboard.stats', ['type' => 'today', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonPath('statistics.total_orders.current', 1)
        ->assertJsonPath('statistics.total_sales.formatted_total', core()->formatBasePrice(100))
        ->assertJsonCount(1, 'statistics.orders')
        ->assertJsonPath('statistics.orders.0.id', $order->id)
        ->assertJsonPath('statistics.orders.0.customer_email', $order->customer_email)
        ->assertJsonPath('statistics.orders.0.formatted_base_grand_total', core()->formatBasePrice(100));
});

it('should return the stock threshold products stats', function () {
    $channel = Channel::factory()->create();

    $product = $this->createSimpleProduct();

    $product->channels()->attach($channel->id);

    $this->loginAsAdmin();

    $response = get(route('admin.dashboard.stats', ['type' => 'stock-threshold-products', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonCount(1, 'statistics')
        ->assertJsonPath('statistics.0.id', $product->id)
        ->assertJsonPath('statistics.0.sku', $product->sku);

    expect($response->json('statistics.0.total_qty'))->toEqual($product->inventories()->sum('qty'));
});

it('should return the total sales stats', function () {
    $channel = Channel::factory()->create();

    $order = $this->createOrder(['channel_id' => $channel->id], [['price' => 100]]);

    $this->invoiceOrder($order);

    $this->loginAsAdmin();

    $response = get(route('admin.dashboard.stats', ['type' => 'total-sales', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonPath('statistics.total_orders.current', 1)
        ->assertJsonPath('statistics.total_sales.formatted_total', core()->formatBasePrice(100));

    expect(collect($response->json('statistics.over_time'))->sum('count'))->toBe(1)
        ->and(collect($response->json('statistics.over_time'))->sum('total'))->toBePrice(100);
});

it('should return the top selling products stats', function () {
    $channel = Channel::factory()->create();

    $product = $this->createSimpleProduct();

    $order = $this->createOrder(['channel_id' => $channel->id], [['product' => $product, 'price' => 20, 'qty_ordered' => 5]]);

    $this->invoiceOrder($order);

    $this->loginAsAdmin();

    $response = get(route('admin.dashboard.stats', ['type' => 'top-selling-products', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonCount(1, 'statistics')
        ->assertJsonPath('statistics.0.id', $product->id)
        ->assertJsonPath('statistics.0.name', $product->name)
        ->assertJsonPath('statistics.0.formatted_revenue', core()->formatBasePrice(100));

    expect($response->json('statistics.0.revenue'))->toBePrice(100);
});

it('should return the top customers stats', function () {
    $channel = Channel::factory()->create();

    $customer = Customer::factory()->create();

    $order = $this->createOrder(['channel_id' => $channel->id], [['price' => 100]], $customer);

    $this->invoiceOrder($order);

    $this->loginAsAdmin();

    $response = get(route('admin.dashboard.stats', ['type' => 'top-customers', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonCount(1, 'statistics')
        ->assertJsonPath('statistics.0.id', $customer->id)
        ->assertJsonPath('statistics.0.email', $customer->email)
        ->assertJsonPath('statistics.0.full_name', $customer->name)
        ->assertJsonPath('statistics.0.orders', 1)
        ->assertJsonPath('statistics.0.formatted_total', core()->formatBasePrice(100));

    expect($response->json('statistics.0.total'))->toBePrice(100);
});
