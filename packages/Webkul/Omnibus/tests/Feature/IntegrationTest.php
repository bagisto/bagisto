<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Faker\Helpers\Product;
use Webkul\Omnibus\Repositories\OmnibusPriceRepository;
use Webkul\Omnibus\Services\OmnibusPriceManager;
use Webkul\Product\Models\ProductPriceIndex;

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

it('separates products prices strictly', function () {
    $configurableProduct = (new Product)->getConfigurableProductFactory()->create();

    DB::table('product_omnibus_prices')->delete();

    ProductPriceIndex::updateOrCreate([
        'product_id' => $configurableProduct->id,
        'customer_group_id' => app(CustomerRepository::class)->getCurrentGroup()->id,
        'channel_id' => $this->channelId,
    ], [
        'min_price' => 100.00,
        'regular_min_price' => 100.00,
    ]);

    foreach ($configurableProduct->variants as $variant) {
        ProductPriceIndex::updateOrCreate([
            'product_id' => $variant->id,
            'customer_group_id' => app(CustomerRepository::class)->getCurrentGroup()->id,
            'channel_id' => $this->channelId,
        ], [
            'min_price' => 50.00,
            'regular_min_price' => 50.00,
        ]);
    }

    $configurableProduct->refresh();
    $configurableProduct->load('price_indices');

    foreach ($configurableProduct->variants as $variant) {
        $variant->refresh();
        $variant->load('price_indices');
    }

    $originalChannel = core()->getCurrentChannel();
    $originalCurrency = core()->getCurrentCurrency();

    $this->manager->recordPriceIfNeeded($configurableProduct);

    core()->setCurrentChannel($originalChannel);
    core()->setCurrentCurrency($originalCurrency);

    $records = $this->repository->whereIn('product_id', $configurableProduct->variants->pluck('id')->toArray())
        ->orderBy('price', 'asc')
        ->get();

    expect(round((float) $records->first()->price, 2))->toBe(50.00);
});

it('captures simple products prices accurately', function () {
    $product = (new Product)->getSimpleProductFactory()->create();

    DB::table('product_omnibus_prices')->delete();

    ProductPriceIndex::updateOrCreate([
        'product_id' => $product->id,
        'customer_group_id' => app(CustomerRepository::class)->getCurrentGroup()->id,
        'channel_id' => $this->channelId,
    ], [
        'min_price' => 100.00,
        'regular_min_price' => 100.00,
    ]);

    $product->refresh();
    $product->load('price_indices');

    $originalChannel = core()->getCurrentChannel();
    $originalCurrency = core()->getCurrentCurrency();

    $this->manager->recordPriceIfNeeded($product);

    core()->setCurrentChannel($originalChannel);
    core()->setCurrentCurrency($originalCurrency);

    $records = $this->repository->where('product_id', $product->id)->get();

    expect(round((float) $records->first()->price, 2))->toBe(100.00);
});
