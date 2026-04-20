<?php

use Carbon\Carbon;
use Webkul\Omnibus\Models\OmnibusPrice;
use Webkul\Omnibus\Repositories\OmnibusPriceRepository;
use Webkul\Product\Repositories\ProductRepository;

use function Pest\Laravel\get;

// ============================================================================
// Setup
// ============================================================================

beforeEach(function () {
    $this->repository = app(OmnibusPriceRepository::class);

    $this->setOmnibusEnabled(true);

    OmnibusPrice::query()->delete();

    $this->now = Carbon::parse('2026-04-16 12:00:00');
    Carbon::setTestNow($this->now);
});

afterEach(function () {
    Carbon::setTestNow();
});

// ============================================================================
// Product Page Injection
// ============================================================================

it('renders the shop product page without error when Omnibus is enabled', function () {
    $product = $this->createSimpleProduct();

    $this->repository->create([
        'product_id' => $product->id,
        'channel_id' => core()->getCurrentChannel()->id,
        'currency_code' => core()->getCurrentCurrencyCode(),
        'price' => 99.00,
        'recorded_at' => $this->now->copy()->subDays(10),
    ]);

    app(ProductRepository::class)->update([
        'channel' => core()->getCurrentChannel()->code,
        'locale' => core()->getCurrentLocale()->code,
        'status' => 1,
        'visible_individually' => 1,
        'url_key' => $product->url_key,
        'price' => 150.00,
        'special_price' => 80.00,
        'special_price_from' => $this->now->copy()->subDays(1)->toDateTimeString(),
    ], $product->id);

    $response = get(route('shop.product_or_category.index', $product->url_key));

    $response->assertOk();
});
