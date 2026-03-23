<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Omnibus\Helpers\OmnibusHelper;
use Webkul\Omnibus\Repositories\OmnibusPriceRepository;

beforeEach(function () {
    config(['catalog.products.omnibus.is_enabled' => true]);
    $this->helper = app(OmnibusHelper::class);
    $this->repository = app(OmnibusPriceRepository::class);

    // Create base product
    $this->product = (new ProductFaker)->getSimpleProductFactory()->create();
    $this->channelId = core()->getCurrentChannel()->id;
    $this->currencyCode = core()->getCurrentCurrencyCode();
});

it('ignores prices older than 30 days and considers prices strictly within the 30-day window', function () {
    // Arrange: Create a price from 35 days ago (should be ignored)
    $this->repository->create([
        'product_id' => $this->product->id,
        'channel_id' => $this->channelId,
        'currency_code' => $this->currencyCode,
        'price' => 50.00,
        'recorded_at' => Carbon::now()->subDays(35),
    ]);

    // Arrange: Create a price from 29 days ago (should be captured)
    $this->repository->create([
        'product_id' => $this->product->id,
        'channel_id' => $this->channelId,
        'currency_code' => $this->currencyCode,
        'price' => 80.00,
        'recorded_at' => Carbon::now()->subDays(29),
    ]);

    // Act
    $lowestPrice = $this->helper->getLowestPrice($this->product);

    // Assert
    expect((float) $lowestPrice)->toBe(80.0);
});

it('returns the lowest price strictly prior to the current discount start date', function () {
    // Arrange: Price history 120 -> 100 -> 80
    // 20 days ago, price was 120
    $this->repository->create([
        'product_id' => $this->product->id,
        'channel_id' => $this->channelId,
        'currency_code' => $this->currencyCode,
        'price' => 120.00,
        'recorded_at' => Carbon::now()->subDays(20),
    ]);

    // 10 days ago, price was 100
    $this->repository->create([
        'product_id' => $this->product->id,
        'channel_id' => $this->channelId,
        'currency_code' => $this->currencyCode,
        'price' => 100.00,
        'recorded_at' => Carbon::now()->subDays(10),
    ]);

    // Product goes on sale TODAY for 80
    $this->product->special_price = 80.00;
    // Current promotion started 5 days ago
    $this->product->special_price_from = Carbon::now()->subDays(5);

    // 2 days ago, the cron logged the 80 price
    $this->repository->create([
        'product_id' => $this->product->id,
        'channel_id' => $this->channelId,
        'currency_code' => $this->currencyCode,
        'price' => 80.00,
        'recorded_at' => Carbon::now()->subDays(2),
    ]);

    // Act
    $lowestPrice = $this->helper->getLowestPrice($this->product);

    // Assert: The system MUST ignore the recent 80.00 log since the promotion started 5 days ago
    // Thus the lowest price PRIOR to the discount start within the 30-day window is 100.
    expect((float) $lowestPrice)->toBe(100.0);
});

it('returns null or product generic price when history is completely empty', function () {
    // Arrange
    $this->product->price = 150.00;

    // Act
    $lowestPrice = $this->helper->getLowestPrice($this->product);

    // Assert
    expect($lowestPrice)->toBeNull();

    $formatted = $this->helper->getLowestPriceFormatted($this->product);
    expect($formatted)->toBeNull();
});

it('isolates channels and currencies strictly', function () {
    Schema::disableForeignKeyConstraints();

    // Arrange: Add price for Channel 2 and USD
    $this->repository->create([
        'product_id' => $this->product->id,
        'channel_id' => 99,
        'currency_code' => 'USD',
        'price' => 10.00,
        'recorded_at' => Carbon::now()->subDays(5),
    ]);

    Schema::enableForeignKeyConstraints();

    // Arrange: Add price for Current Channel and Current Currency
    $this->repository->create([
        'product_id' => $this->product->id,
        'channel_id' => $this->channelId,
        'currency_code' => $this->currencyCode,
        'price' => 25.00,
        'recorded_at' => Carbon::now()->subDays(5),
    ]);

    // Act
    $lowestPrice = $this->helper->getLowestPrice($this->product);

    // Assert: Should grab 25.00, COMPLETELY ignoring the 10.00 USD entry on Channel 99
    expect((float) $lowestPrice)->toBe(25.0);
});
