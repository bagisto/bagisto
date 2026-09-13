<?php

use Carbon\Carbon;
use Webkul\Admin\Helpers\Reporting\Cart as CartReporting;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Core\Models\Channel;
use Webkul\Customer\Models\Customer;
use Webkul\Sales\Repositories\RefundRepository;

use function Pest\Laravel\get;

// ============================================================================
// Index
// ============================================================================

it('should return the sales reporting index page', function () {
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.reporting.sales.index.title'))
        ->assertSeeText(trans('admin::app.reporting.sales.index.refunds'))
        ->assertSeeText(trans('admin::app.reporting.sales.index.total-sales'));
});

it('should deny guest access to the sales reporting page', function () {
    get(route('admin.reporting.sales.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Stats
// ============================================================================

it('should return total sales stats', function () {
    $channel = Channel::factory()->create();

    $order = $this->createOrder(['channel_id' => $channel->id], [['price' => 100]]);

    $this->invoiceOrder($order);

    $this->loginAsAdmin();

    $response = get(route('admin.reporting.sales.stats', ['type' => 'total-sales', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonPath('statistics.sales.previous', 0)
        ->assertJsonPath('statistics.sales.progress', 100)
        ->assertJsonPath('statistics.sales.formatted_total', core()->formatBasePrice(100));

    expect($response->json('statistics.sales.current'))->toBePrice(100)
        ->and(collect($response->json('statistics.over_time.current'))->sum('count'))->toBe(1)
        ->and(collect($response->json('statistics.over_time.current'))->sum('total'))->toBePrice(100);
});

it('should return abandoned carts stats', function () {
    $channel = Channel::factory()->create();

    $product = $this->createSimpleProduct();

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'channel_id' => $channel->id,
        'customer_id' => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
        'customer_email' => $customer->email,
        'is_guest' => false,
        'is_active' => 1,
        'created_at' => Carbon::now()->subMonth()->toDateString(),
    ]);

    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'sku' => $product->sku,
        'quantity' => 1,
        'name' => $product->name,
        'price' => $product->price,
        'base_price' => $product->price,
        'total' => $product->price,
        'base_total' => $product->price,
        'weight' => $product->weight ?? 0,
        'type' => $product->type,
    ]);

    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'start' => Carbon::now()->subMonth()->toDateString(),
        'type' => 'abandoned-carts',
        'channel' => $channel->code,
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.carts.current', 1)
        ->assertJsonPath('statistics.carts.progress', 100)
        ->assertJsonCount(1, 'statistics.products')
        ->assertJsonPath('statistics.products.0.id', $product->id)
        ->assertJsonPath('statistics.products.0.count', 1);
});

it('should keep the reporting window intact while working out the abandoned cart figures', function () {
    $channel = Channel::factory()->create();

    Cart::factory()->create([
        'channel_id' => $channel->id,
        'is_active' => true,
        'created_at' => now()->subDays(3),
    ]);

    $reporting = app(CartReporting::class)->setChannel($channel->code);

    $endDate = $reporting->getEndDate()->copy();

    expect($reporting->getTotalAbandonedCartRate($reporting->getStartDate(), $reporting->getEndDate()))->toBe(100.0)
        ->and($reporting->getTotalAbandonedCartRateProgress()['current'])->toBe(100.0)
        ->and($reporting->getTotalAbandonedCartsProgress()['current'])->toBe(1)
        ->and($reporting->getEndDate()->equalTo($endDate))->toBeTrue();
});

it('should return total orders stats', function () {
    $channel = Channel::factory()->create();

    $this->createOrder(['channel_id' => $channel->id]);
    $this->createOrder(['channel_id' => $channel->id]);

    $this->loginAsAdmin();

    $response = get(route('admin.reporting.sales.stats', ['type' => 'total-orders', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonPath('statistics.orders.previous', 0)
        ->assertJsonPath('statistics.orders.current', 2)
        ->assertJsonPath('statistics.orders.progress', 100);

    expect(collect($response->json('statistics.over_time.current'))->sum('count'))->toBe(2);
});

it('should return average sales stats', function () {
    $channel = Channel::factory()->create();

    $this->invoiceOrder($this->createOrder(['channel_id' => $channel->id], [['price' => 100]]));
    $this->invoiceOrder($this->createOrder(['channel_id' => $channel->id], [['price' => 300]]));

    $this->loginAsAdmin();

    $response = get(route('admin.reporting.sales.stats', ['type' => 'average-sales', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonPath('statistics.sales.progress', 100)
        ->assertJsonPath('statistics.sales.formatted_total', core()->formatBasePrice(200));

    expect($response->json('statistics.sales.current'))->toBePrice(200)
        ->and(collect($response->json('statistics.over_time.current'))->sum('count'))->toBe(2);
});

it('should return shipping collected stats', function () {
    $channel = Channel::factory()->create();

    $order = $this->createOrder([
        'channel_id' => $channel->id,
        'shipping_amount' => 10,
        'base_shipping_amount' => 10,
    ]);

    $this->invoiceOrder($order);

    $this->loginAsAdmin();

    $response = get(route('admin.reporting.sales.stats', ['type' => 'shipping-collected', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonPath('statistics.shipping_collected.progress', 100)
        ->assertJsonPath('statistics.shipping_collected.formatted_total', core()->formatBasePrice(10))
        ->assertJsonCount(1, 'statistics.top_methods')
        ->assertJsonPath('statistics.top_methods.0.title', $order->shipping_title);

    expect($response->json('statistics.shipping_collected.current'))->toBePrice(10)
        ->and($response->json('statistics.top_methods.0.total'))->toBePrice(10);
});

it('should return tax collected stats', function () {
    $channel = Channel::factory()->create();

    $order = $this->createOrder(['channel_id' => $channel->id], [['tax_amount' => 5, 'base_tax_amount' => 5]]);

    $this->invoiceOrder($order);

    $this->loginAsAdmin();

    $response = get(route('admin.reporting.sales.stats', ['type' => 'tax-collected', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonPath('statistics.tax_collected.progress', 100)
        ->assertJsonPath('statistics.tax_collected.formatted_total', core()->formatBasePrice(5));

    expect($response->json('statistics.tax_collected.current'))->toBePrice(5);
});

it('should return refunds stats', function () {
    $channel = Channel::factory()->create();

    $order = $this->createOrder(['channel_id' => $channel->id], [['price' => 100]]);

    $this->invoiceOrder($order);

    app(RefundRepository::class)->create([
        'order_id' => $order->id,
        'refund' => [
            'items' => [$order->items->first()->id => 1],
            'shipping' => 0,
            'adjustment_refund' => 0,
            'adjustment_fee' => 0,
        ],
    ]);

    $this->loginAsAdmin();

    $response = get(route('admin.reporting.sales.stats', ['type' => 'refunds', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonPath('statistics.refunds.progress', 100)
        ->assertJsonPath('statistics.refunds.formatted_total', core()->formatBasePrice(100));

    expect($response->json('statistics.refunds.current'))->toBePrice(100);
});

it('should return top payment methods stats', function () {
    $channel = Channel::factory()->create();

    $this->createOrder(['channel_id' => $channel->id, 'payment_method' => 'cashondelivery']);
    $this->createOrder(['channel_id' => $channel->id, 'payment_method' => 'cashondelivery']);
    $this->createOrder(['channel_id' => $channel->id, 'payment_method' => 'moneytransfer']);

    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', ['type' => 'top-payment-methods', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonCount(2, 'statistics')
        ->assertJsonPath('statistics.0.method', 'cashondelivery')
        ->assertJsonPath('statistics.0.total', 2)
        ->assertJsonPath('statistics.1.method', 'moneytransfer')
        ->assertJsonPath('statistics.1.total', 1);
});

// ============================================================================
// View
// ============================================================================

it('should return the sales report view page', function () {
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.view', ['type' => 'total-sales']))
        ->assertOk();
});

// ============================================================================
// Export
// ============================================================================

it('should export the sales stats', function () {
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.export', ['type' => 'total-sales', 'period' => 'day', 'format' => 'csv']))
        ->assertOk()
        ->assertDownload();
});
