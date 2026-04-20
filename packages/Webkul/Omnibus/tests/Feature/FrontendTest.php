<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Omnibus\Repositories\OmnibusPriceRepository;
use Webkul\Omnibus\Services\OmnibusPriceManager;
use Webkul\Product\Models\ProductPriceIndex;
use Webkul\Product\Repositories\ProductRepository;

use function Pest\Laravel\get;

beforeEach(function () {
    $channelResult = DB::table('channels')->orderBy('id')->first();
    $this->channelId = $channelResult->id ?? 1;

    config(['products.omnibus.is_enabled' => 1]);

    $this->repository = app(OmnibusPriceRepository::class);

    $this->now = Carbon::parse('2026-04-16 12:00:00');
    Carbon::setTestNow($this->now);
});

afterEach(function () {
    Carbon::setTestNow();
});

it('renders the omnibus directive string correctly for simple products', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $this->repository->create([
        'product_id' => $product->id,
        'channel_id' => $this->channelId,
        'currency_code' => core()->getCurrentCurrency()->code,
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

    $product->refresh();

    ProductPriceIndex::updateOrCreate([
        'product_id' => $product->id,
        'customer_group_id' => app(CustomerRepository::class)->getCurrentGroup()->id ?? 1,
        'channel_id' => $this->channelId,
    ], [
        'min_price' => 80.00,
        'regular_min_price' => 150.00,
    ]);

    $originalChannel = core()->getCurrentChannel();
    $originalCurrency = core()->getCurrentCurrency();

    app(OmnibusPriceManager::class)->recordPrice($product);

    core()->setCurrentChannel($originalChannel);
    core()->setCurrentCurrency($originalCurrency);

    $response = get(route('shop.product_or_category.index', $product->url_key));

    $response->assertStatus(200);

    $formattedPrice = core()->formatPrice(99.00, core()->getCurrentCurrency()->code);
    preg_match('/[0-9]+[.,][0-9]+/', $formattedPrice, $matches);
    $safeNumericString = $matches[0] ?? '99.00';

    $response->assertSee($safeNumericString, false);
});
