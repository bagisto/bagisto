<?php

use Webkul\Omnibus\PriceProviders\BundleOmnibusPriceProvider;
use Webkul\Omnibus\PriceProviders\ConfigurableOmnibusPriceProvider;
use Webkul\Omnibus\PriceProviders\DefaultOmnibusPriceProvider;
use Webkul\Omnibus\PriceProviders\GroupedOmnibusPriceProvider;
use Webkul\Omnibus\Services\OmnibusPriceProviderResolver;

// ============================================================================
// Resolver Mapping
// ============================================================================

it('should resolve the default provider for leaf product types', function (string $type) {
    $product = $this->createProductOfType($type);

    expect(app(OmnibusPriceProviderResolver::class)->resolve($product))
        ->toBeInstanceOf(DefaultOmnibusPriceProvider::class);
})->with(['simple', 'virtual', 'downloadable']);

it('should resolve the configurable provider for configurable products', function () {
    $product = $this->createConfigurableProduct();

    expect(app(OmnibusPriceProviderResolver::class)->resolve($product))
        ->toBeInstanceOf(ConfigurableOmnibusPriceProvider::class);
});

it('should resolve the grouped provider for grouped products', function () {
    $product = $this->createGroupedProduct();

    expect(app(OmnibusPriceProviderResolver::class)->resolve($product))
        ->toBeInstanceOf(GroupedOmnibusPriceProvider::class);
});

it('should resolve the bundle provider for bundle products', function () {
    $product = $this->createBundleProduct();

    expect(app(OmnibusPriceProviderResolver::class)->resolve($product))
        ->toBeInstanceOf(BundleOmnibusPriceProvider::class);
});

// ============================================================================
// Descendant Resolution For Leaf Types
// ============================================================================

it('should return no descendants for leaf product types', function (string $type) {
    $product = $this->createProductOfType($type);

    $provider = app(OmnibusPriceProviderResolver::class)->resolve($product);

    expect($provider->getDescendantProductIds($product))->toBe([]);
})->with(['simple', 'virtual', 'downloadable']);

// ============================================================================
// Descendant Resolution For Composite Types
// ============================================================================

it('should return every variant id for a configurable product', function () {
    $configurable = $this->createConfigurableProduct([100, 200]);

    $provider = app(OmnibusPriceProviderResolver::class)->resolve($configurable);

    $variantIds = $configurable->variants->pluck('id')->sort()->values()->all();
    $descendantIds = collect($provider->getDescendantProductIds($configurable))->sort()->values()->all();

    expect($descendantIds)->toBe($variantIds);
});

it('should return every associated product id for a grouped product', function () {
    $grouped = $this->createGroupedProduct([100, 200]);

    $provider = app(OmnibusPriceProviderResolver::class)->resolve($grouped);

    $associatedIds = $grouped->grouped_products->pluck('associated_product_id')->sort()->values()->all();
    $descendantIds = collect($provider->getDescendantProductIds($grouped))->sort()->values()->all();

    expect($descendantIds)->toBe($associatedIds);
});

it('should return the option product ids for a bundle product, not the parent id', function () {
    $bundle = $this->createBundleProduct([100, 200]);

    $provider = app(OmnibusPriceProviderResolver::class)->resolve($bundle);

    $descendantIds = $provider->getDescendantProductIds($bundle);

    expect($descendantIds)->not->toContain($bundle->id)
        ->and($descendantIds)->not->toBeEmpty();

    foreach ($descendantIds as $id) {
        expect($id)->not->toBe($bundle->id);
    }
});
