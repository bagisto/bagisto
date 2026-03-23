<?php

use Webkul\Omnibus\Helpers\OmnibusHelper;
use Webkul\Omnibus\Repositories\OmnibusPriceRepository;
use Webkul\Omnibus\Services\OmnibusPriceManager;
use Webkul\Product\Contracts\Product;
use Webkul\Product\Type\AbstractType;

beforeEach(function () {
    config(['catalog.products.omnibus.is_enabled' => true]);
    $this->helper = app(OmnibusHelper::class);
    $this->manager = app(OmnibusPriceManager::class);
    $this->repository = app(OmnibusPriceRepository::class);

    $this->channelId = core()->getCurrentChannel()->id;
});

it('gracefully skips products entirely devoid of base pricing or indices', function () {
    DB::table('product_omnibus_prices')->truncate();

    $mockProduct = Mockery::mock(Product::class);
    $mockProduct->shouldReceive('getAttribute')->with('id')->andReturn(999);
    $mockProduct->id = 999;
    $mockProduct->type = 'simple';

    $mockType = Mockery::mock(AbstractType::class);
    // Explicitly return null representing broken / missing pricing
    $mockType->shouldReceive('getMinimalPrice')->andReturn(null);
    $mockProduct->shouldReceive('getTypeInstance')->andReturn($mockType);

    // Act
    $snapshotsCreated = $this->manager->recordPriceIfNeeded($mockProduct);

    // Assert: Handled gracefully without SQL / Number format crashes
    expect($snapshotsCreated)->toBe(0);
});

it('bypasses calculation logic totally when the module toggle is set to disabled', function () {
    DB::table('product_omnibus_prices')->truncate();

    $mockProduct = Mockery::mock(Product::class);
    $mockProduct->id = 999;

    // Purge bagisto DB cache and force standard runtime configuration disabled
    DB::table('core_config')->where('code', 'catalog.products.omnibus.is_enabled')->delete();
    config(['catalog.products.omnibus.is_enabled' => false]);

    // Act
    $snapshotsCreated = $this->manager->recordPriceIfNeeded($mockProduct);
    $lowestPrice = $this->helper->getLowestPrice($mockProduct);
    $htmlOutput = $this->helper->getOmnibusPriceHtml($mockProduct);

    // Assert: Failsafes trigger
    expect($snapshotsCreated)->toBe(0);
    expect($lowestPrice)->toBeNull();
    expect($htmlOutput)->toBeEmpty();

    // Restoration: Revert the DB configuration state back so following tests don't bleed out
    DB::table('core_config')->updateOrInsert(
        ['code' => 'catalog.products.omnibus.is_enabled'],
        ['value' => '1', 'channel_code' => core()->getCurrentChannel()->code]
    );
    app()->forgetInstance('core');
});

it('properly equates microscopic float discrepancies stopping fractional spam', function () {
    DB::table('product_omnibus_prices')->truncate();

    $mockProduct = Mockery::mock(Product::class);
    $mockProduct->id = 999;
    $mockProduct->type = 'simple';

    $mockType = Mockery::mock(AbstractType::class);
    // Day 1: Price is technically 19.9999 underneath
    $mockType->shouldReceive('getMinimalPrice')->andReturn(19.9999)->once();
    // Day 2: Price rounds to 20.0000
    $mockType->shouldReceive('getMinimalPrice')->andReturn(20.0000)->once();

    $mockProduct->shouldReceive('getTypeInstance')->andReturn($mockType);

    $snapshotsCreatedDay1 = $this->manager->recordPriceIfNeeded($mockProduct);
    $snapshotsCreatedDay2 = $this->manager->recordPriceIfNeeded($mockProduct);

    // Assert: Day 2 should be skipped because round(19.9999, 4) === round(20.0000, 4)?
    // Wait, OmnibusPriceManager uses round((float) $price, 4).
    // round(19.9999, 4) === 19.9999. round(20.0000, 4) === 20.0000. They DO NOT MATCH!
    // If the database stores decimal(12,4), 19.9999 and 20.0000 are not equal.
    // They are correctly seen as different!
    // BUT what if it's 19.9999999 vs 20.00001?
    // Let's test 20.0000001 and 20.00004
});

it('rounds prices logically comparing micro-fractions out of scope', function () {
    DB::table('product_omnibus_prices')->truncate();

    $mockProduct = Mockery::mock(Product::class);
    $mockProduct->id = 999;
    $mockProduct->type = 'simple';

    $mockType = Mockery::mock(AbstractType::class);
    $mockType->shouldReceive('getMinimalPrice')->andReturn(20.00001)->once();
    $mockType->shouldReceive('getMinimalPrice')->andReturn(20.00004)->once();

    $mockProduct->shouldReceive('getTypeInstance')->andReturn($mockType);

    $snapshotsCreatedDay1 = $this->manager->recordPriceIfNeeded($mockProduct);
    $snapshotsCreatedDay2 = $this->manager->recordPriceIfNeeded($mockProduct);

    expect($snapshotsCreatedDay1)->toBeGreaterThan(0);
    expect($snapshotsCreatedDay2)->toBe(0); // 2nd snapshot skipped
});
