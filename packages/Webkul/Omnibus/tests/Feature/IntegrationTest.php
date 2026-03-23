<?php

use Carbon\Carbon;
use Webkul\Omnibus\Helpers\OmnibusHelper;
use Webkul\Omnibus\Repositories\OmnibusPriceRepository;
use Webkul\Omnibus\Services\OmnibusPriceManager;
use Webkul\Faker\Helpers\Product as ProductFaker;

beforeEach(function () {
    config(['catalog.products.omnibus.is_enabled' => true]);
    $this->helper = app(OmnibusHelper::class);
    $this->manager = app(OmnibusPriceManager::class);
    $this->repository = app(OmnibusPriceRepository::class);
    
    $this->channelId = core()->getCurrentChannel()->id;
    $this->currencyCode = core()->getCurrentCurrencyCode();
});

it('propagates the snapshot recursively down to configurable variants', function () {
    $configurableProduct = (new ProductFaker([
        'attributes' => [1 => 'color', 2 => 'size'],
        'attribute_value' => [
            'color' => ['boolean_value' => true],
            'size' => ['boolean_value' => true],
        ],
    ]))->getConfigurableProductFactory()->create();

    \DB::table('product_omnibus_prices')->truncate(); // Purge factory event noise

    \DB::table('product_price_indices')->updateOrInsert([
        'product_id' => $configurableProduct->id,
        'customer_group_id' => 1,
        'channel_id' => $this->channelId,
    ], [
        'min_price' => 100.00,
        'regular_min_price' => 100.00,
    ]);

    foreach ($configurableProduct->variants as $variant) {
        \DB::table('product_price_indices')->updateOrInsert([
            'product_id' => $variant->id,
            'customer_group_id' => 1,
            'channel_id' => $this->channelId,
        ], [
            'min_price' => 50.00,
            'regular_min_price' => 50.00,
        ]);
    }

    $snapshotsCreated = $this->manager->recordPriceIfNeeded($configurableProduct);

    expect($snapshotsCreated)->toBeGreaterThanOrEqual(1); // Iterates variants
    
    $parentLog = $this->repository->where('product_id', $configurableProduct->id)->first();
    expect($parentLog)->not->toBeNull();
});

it('prevents duplicated records when prices have not changed consecutively', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();
    
    \DB::table('product_omnibus_prices')->truncate();

    \DB::table('product_price_indices')->updateOrInsert([
        'product_id' => $product->id,
        'customer_group_id' => 1,
        'channel_id' => $this->channelId,
    ], [
        'min_price' => 100.00,
        'regular_min_price' => 100.00,
    ]);

    $snapshotsCreatedDay1 = $this->manager->recordPriceIfNeeded($product);
    $snapshotsCreatedDay2 = $this->manager->recordPriceIfNeeded($product);

    expect($snapshotsCreatedDay1)->toBeGreaterThan(0);
    expect($snapshotsCreatedDay2)->toBe(0); 

    $totalLogs = $this->repository->where('product_id', $product->id)->count();
    expect($totalLogs)->toBe(count(core()->getCurrentChannel()->currencies));
});

it('purges records older than 35 days silently', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    \DB::table('product_omnibus_prices')->truncate();

    $this->repository->create([
        'product_id' => $product->id,
        'channel_id' => $this->channelId,
        'currency_code' => $this->currencyCode,
        'price' => 20.00,
        'recorded_at' => Carbon::now()->subDays(40)->toDateTimeString(),
    ]);

    $record20 = $this->repository->create([
        'product_id' => $product->id,
        'channel_id' => $this->channelId,
        'currency_code' => $this->currencyCode,
        'price' => 50.00,
        'recorded_at' => Carbon::now()->subDays(20)->toDateTimeString(),
    ]);

    $this->manager->cleanOldRecords();

    $logs = $this->repository->where('product_id', $product->id)->get();
    
    expect($logs->count())->toBe(1);
    expect($logs->first()->id)->toBe($record20->id);
});
