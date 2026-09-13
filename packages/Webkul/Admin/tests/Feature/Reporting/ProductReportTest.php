<?php

use Webkul\Core\Models\Channel;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\Wishlist;
use Webkul\Marketing\Models\SearchTerm;
use Webkul\Product\Models\ProductReview;

use function Pest\Laravel\get;

// ============================================================================
// Index
// ============================================================================

it('should return the product reporting index page', function () {
    $this->loginAsAdmin();

    get(route('admin.reporting.products.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.reporting.products.index.title'))
        ->assertSeeText(trans('admin::app.reporting.products.index.last-search-terms'))
        ->assertSeeText(trans('admin::app.reporting.products.index.products-with-most-reviews'))
        ->assertSeeText(trans('admin::app.reporting.products.index.top-selling-products-by-quantity'))
        ->assertSeeText(trans('admin::app.reporting.products.index.top-selling-products-by-revenue'))
        ->assertSeeText(trans('admin::app.reporting.products.index.top-search-terms'));
});

it('should deny guest access to the product reporting page', function () {
    get(route('admin.reporting.products.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Stats
// ============================================================================

it('should return total sold quantities stats', function () {
    $channel = Channel::factory()->create();

    $product = $this->createSimpleProduct();

    $order = $this->createOrder(['channel_id' => $channel->id], [['product' => $product, 'qty_ordered' => 3]]);

    $this->invoiceOrder($order);

    $this->loginAsAdmin();

    get(route('admin.reporting.products.stats', ['type' => 'total-sold-quantities', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonPath('statistics.quantities.previous', 0)
        ->assertJsonPath('statistics.quantities.current', 3)
        ->assertJsonPath('statistics.quantities.progress', 100);
});

it('should return total products added to wishlist stats', function () {
    $channel = Channel::factory()->create();

    $product = $this->createSimpleProduct();

    $customer = Customer::factory()->create();

    Wishlist::factory()->create([
        'product_id' => $product->id,
        'customer_id' => $customer->id,
        'channel_id' => $channel->id,
    ]);

    $this->loginAsAdmin();

    get(route('admin.reporting.products.stats', ['type' => 'total-products-added-to-wishlist', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonPath('statistics.wishlist.previous', 0)
        ->assertJsonPath('statistics.wishlist.current', 1)
        ->assertJsonPath('statistics.wishlist.progress', 100);
});

it('should return top selling products by revenue stats', function () {
    $channel = Channel::factory()->create();

    $expensive = $this->createSimpleProduct();

    $cheap = $this->createSimpleProduct();

    $order = $this->createOrder(['channel_id' => $channel->id], [
        ['product' => $expensive, 'price' => 300],
        ['product' => $cheap, 'price' => 100, 'qty_ordered' => 2],
    ]);

    $this->invoiceOrder($order);

    $this->loginAsAdmin();

    $response = get(route('admin.reporting.products.stats', ['type' => 'top-selling-products-by-revenue', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonCount(2, 'statistics')
        ->assertJsonPath('statistics.0.id', $expensive->id)
        ->assertJsonPath('statistics.0.formatted_revenue', core()->formatBasePrice(300))
        ->assertJsonPath('statistics.1.id', $cheap->id)
        ->assertJsonPath('statistics.1.formatted_revenue', core()->formatBasePrice(200));

    expect($response->json('statistics.0.revenue'))->toBePrice(300)
        ->and($response->json('statistics.0.progress'))->toBePrice(60);
});

it('should return top selling products by quantity stats', function () {
    $channel = Channel::factory()->create();

    $popular = $this->createSimpleProduct();

    $other = $this->createSimpleProduct();

    $order = $this->createOrder(['channel_id' => $channel->id], [
        ['product' => $popular, 'qty_ordered' => 5],
        ['product' => $other, 'qty_ordered' => 1],
    ]);

    $this->invoiceOrder($order);

    $this->loginAsAdmin();

    $response = get(route('admin.reporting.products.stats', ['type' => 'top-selling-products-by-quantity', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonCount(2, 'statistics')
        ->assertJsonPath('statistics.0.id', $popular->id)
        ->assertJsonPath('statistics.1.id', $other->id);

    expect($response->json('statistics.0.total_qty_ordered'))->toEqual(5)
        ->and($response->json('statistics.1.total_qty_ordered'))->toEqual(1);
});

it('should return products with most reviews stats', function () {
    $channel = Channel::factory()->create();

    $product = $this->createSimpleProduct();

    $product->channels()->attach($channel->id);

    $customer = Customer::factory()->create();

    ProductReview::factory()->count(2)->create([
        'status' => 'approved',
        'customer_id' => $customer->id,
        'name' => $customer->name,
        'product_id' => $product->id,
    ]);

    $this->loginAsAdmin();

    get(route('admin.reporting.products.stats', ['type' => 'products-with-most-reviews', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonCount(1, 'statistics')
        ->assertJsonPath('statistics.0.product_id', $product->id)
        ->assertJsonPath('statistics.0.product_name', $product->name)
        ->assertJsonPath('statistics.0.reviews', 2);
});

it('should return last search terms stats', function () {
    $channel = Channel::factory()->create();

    $older = SearchTerm::factory()->create(['channel_id' => $channel->id, 'updated_at' => now()->subDay()]);

    $latest = SearchTerm::factory()->create(['channel_id' => $channel->id]);

    $this->loginAsAdmin();

    get(route('admin.reporting.products.stats', ['type' => 'last-search-terms', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonCount(2, 'statistics')
        ->assertJsonPath('statistics.0.id', $latest->id)
        ->assertJsonPath('statistics.0.term', $latest->term)
        ->assertJsonPath('statistics.1.id', $older->id);
});

it('should return top search terms stats', function () {
    $channel = Channel::factory()->create();

    $popular = SearchTerm::factory()->create(['channel_id' => $channel->id, 'uses' => 5]);

    $rare = SearchTerm::factory()->create(['channel_id' => $channel->id, 'uses' => 1]);

    $this->loginAsAdmin();

    get(route('admin.reporting.products.stats', ['type' => 'top-search-terms', 'channel' => $channel->code]))
        ->assertOk()
        ->assertJsonCount(2, 'statistics')
        ->assertJsonPath('statistics.0.id', $popular->id)
        ->assertJsonPath('statistics.0.uses', 5)
        ->assertJsonPath('statistics.1.id', $rare->id);
});

// ============================================================================
// View
// ============================================================================

it('should return the product report view page', function () {
    $this->loginAsAdmin();

    get(route('admin.reporting.products.view', ['type' => 'total-sold-quantities']))
        ->assertOk();
});

// ============================================================================
// Export
// ============================================================================

it('should export the product stats', function () {
    $this->loginAsAdmin();

    get(route('admin.reporting.products.export', ['type' => 'total-sold-quantities', 'period' => 'day', 'format' => 'csv']))
        ->assertOk()
        ->assertDownload();
});
