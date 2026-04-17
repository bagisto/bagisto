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

    $this->helper = app(OmnibusHelper::class);
    $this->manager = app(OmnibusPriceManager::class);
    $this->repository = app(OmnibusPriceRepository::class);

    $this->now = Carbon::parse('2026-04-16 12:00:00');
    Carbon::setTestNow($this->now);
});

afterEach(function () {
    Carbon::setTestNow();
});

it('avoids inserting duplicate prices if price remains exactly the same', function () {
    $product = (new \Webkul\Faker\Helpers\Product)->getSimpleProductFactory()->create();

    DB::table('product_omnibus_prices')->delete();

    ProductPriceIndex::updateOrCreate([
        'product_id' => $product->id,
        'customer_group_id' => app(\Webkul\Customer\Repositories\CustomerRepository::class)->getCurrentGroup()->id,
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
    $this->manager->recordPriceIfNeeded($product);

    core()->setCurrentChannel($originalChannel);
    core()->setCurrentCurrency($originalCurrency);

    $records = $this->repository->where('product_id', $product->id)->get();

    expect($records->count())->toBe(1);

    expect(round((float) $records->first()->price, 2))->toBe(100.00);
});