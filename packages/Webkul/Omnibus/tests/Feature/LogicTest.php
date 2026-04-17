<?php

use Illuminate\Support\Facades\DB;
use Webkul\Installer\Database\Seeders\AttributeFamilyTableSeeder;
use Webkul\Omnibus\Helpers\OmnibusHelper;
use Webkul\Omnibus\Repositories\OmnibusPriceRepository;
use Webkul\Omnibus\Services\OmnibusPriceManager;
use Webkul\Product\Models\ProductPriceIndex;
use Webkul\Core\Core;
use Carbon\Carbon;

beforeEach(function () {
    $channelResult = DB::table('channels')->orderBy('id')->first();
    $this->channelId = $channelResult->id ?? 1;
    $this->currencyCode = core()->getCurrentCurrency()->code;

    config(['products.omnibus.is_enabled' => 1]);

    $this->manager = app(OmnibusPriceManager::class);
    $this->repository = app(OmnibusPriceRepository::class);

    $this->now = Carbon::parse('2026-04-16 12:00:00');
    Carbon::setTestNow($this->now);
});

afterEach(function () {
    Carbon::setTestNow();
});

it('calculates the lowest price correctly for the past 30 days', function () {
    $product = (new \Webkul\Faker\Helpers\Product)->getSimpleProductFactory()->create();

    DB::table('product_omnibus_prices')->delete();

    ProductPriceIndex::updateOrCreate([
        'product_id' => $product->id,
        'customer_group_id' => app(\Webkul\Customer\Repositories\CustomerRepository::class)->getCurrentGroup()->id,
        'channel_id' => $this->channelId,
    ], [
        'min_price' => 150.00,
        'regular_min_price' => 150.00,
    ]);

    $product->refresh();
    $product->load('price_indices');

    $originalChannel = core()->getCurrentChannel();
    $originalCurrency = core()->getCurrentCurrency();

    // Today
    $this->manager->recordPriceIfNeeded($product);

    // 10 days ago (lowest price)
    Carbon::setTestNow($this->now->copy()->subDays(10));
    ProductPriceIndex::where('product_id', $product->id)->update(['min_price' => 100.00]);

    $product->refresh();
    $product->load('price_indices');
    $this->manager->recordPriceIfNeeded($product);

    // 35 days ago (should be ignored)
    Carbon::setTestNow($this->now->copy()->subDays(35));
    ProductPriceIndex::where('product_id', $product->id)->update(['min_price' => 50.00]);

    $product->refresh();
    $product->load('price_indices');
    $this->manager->recordPriceIfNeeded($product);

    Carbon::setTestNow($this->now);

    core()->setCurrentChannel($originalChannel);
    core()->setCurrentCurrency($originalCurrency);

    $records = $this->repository->where('product_id', $product->id)
        ->where('recorded_at', '>=', now()->subDays(30))
        ->orderBy('price', 'asc')
        ->get();

    expect(round((float) $records->first()->price, 2))->toBe(100.00);
});