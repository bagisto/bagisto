<?php

use Webkul\Core\Models\Channel;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Models\ProductReview;

use function Pest\Laravel\get;

// ============================================================================
// Index
// ============================================================================

it('should return the customer reporting index page', function () {
    $this->loginAsAdmin();

    get(route('admin.reporting.customers.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.reporting.customers.index.title'))
        ->assertSeeText(trans('admin::app.reporting.customers.index.customers-with-most-orders'))
        ->assertSeeText(trans('admin::app.reporting.customers.index.customers-with-most-reviews'))
        ->assertSeeText(trans('admin::app.reporting.customers.index.customers-with-most-sales'))
        ->assertSeeText(trans('admin::app.reporting.customers.index.top-customer-groups'))
        ->assertSeeText(trans('admin::app.reporting.customers.index.total-customers'));
});

it('should deny guest access to the customer reporting page', function () {
    get(route('admin.reporting.customers.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Stats
// ============================================================================

it('should return total customers stats', function () {
    $channel = Channel::factory()->create();

    Customer::factory()->count(2)->create(['channel_id' => $channel->id]);

    $this->loginAsAdmin();

    $response = get(route('admin.reporting.customers.stats', ['type' => 'total-customers', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonPath('statistics.customers.previous', 0)
        ->assertJsonPath('statistics.customers.current', 2)
        ->assertJsonPath('statistics.customers.progress', 100);

    expect(collect($response->json('statistics.over_time.current'))->sum('total'))->toBe(2);
});

it('should return customers with most reviews stats', function () {
    $channel = Channel::factory()->create();

    $product = $this->createSimpleProduct();

    $product->channels()->attach($channel->id);

    $customer = Customer::factory()->create(['channel_id' => $channel->id]);

    ProductReview::factory()->count(2)->create([
        'status' => 'approved',
        'customer_id' => $customer->id,
        'name' => $customer->name,
        'product_id' => $product->id,
    ]);

    $this->loginAsAdmin();

    get(route('admin.reporting.customers.stats', ['type' => 'customers-with-most-reviews', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonCount(1, 'statistics')
        ->assertJsonPath('statistics.0.id', $customer->id)
        ->assertJsonPath('statistics.0.email', $customer->email)
        ->assertJsonPath('statistics.0.reviews', 2);
});

it('should return top customer groups stats', function () {
    $channel = Channel::factory()->create();

    $customer = Customer::factory()->create(['channel_id' => $channel->id]);

    $this->loginAsAdmin();

    get(route('admin.reporting.customers.stats', ['type' => 'top-customer-groups', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonCount(1, 'statistics')
        ->assertJsonPath('statistics.0.id', $customer->group->id)
        ->assertJsonPath('statistics.0.group_name', $customer->group->name)
        ->assertJsonPath('statistics.0.total', 1);
});

it('should return customers with most orders stats', function () {
    $channel = Channel::factory()->create();

    $customer = Customer::factory()->create();

    $this->createOrder(['channel_id' => $channel->id], customer: $customer);
    $this->createOrder(['channel_id' => $channel->id], customer: $customer);
    $this->createOrder(['channel_id' => $channel->id]);

    $this->loginAsAdmin();

    get(route('admin.reporting.customers.stats', ['type' => 'customers-with-most-orders', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonCount(2, 'statistics')
        ->assertJsonPath('statistics.0.id', $customer->id)
        ->assertJsonPath('statistics.0.email', $customer->email)
        ->assertJsonPath('statistics.0.full_name', $customer->name)
        ->assertJsonPath('statistics.0.orders', 2);
});

it('should return customers with most sales stats', function () {
    $channel = Channel::factory()->create();

    $customer = Customer::factory()->create();

    $this->invoiceOrder($this->createOrder(['channel_id' => $channel->id], [['price' => 300]], $customer));
    $this->invoiceOrder($this->createOrder(['channel_id' => $channel->id], [['price' => 100]]));

    $this->loginAsAdmin();

    $response = get(route('admin.reporting.customers.stats', ['type' => 'customers-with-most-sales', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonCount(2, 'statistics')
        ->assertJsonPath('statistics.0.id', $customer->id)
        ->assertJsonPath('statistics.0.email', $customer->email)
        ->assertJsonPath('statistics.0.full_name', $customer->name)
        ->assertJsonPath('statistics.0.formatted_total', core()->formatBasePrice(300));

    expect($response->json('statistics.0.total'))->toBePrice(300)
        ->and($response->json('statistics.0.progress'))->toBePrice(75);
});
