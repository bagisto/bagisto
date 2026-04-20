<?php

use Webkul\Omnibus\PriceProviders\BundleOmnibusPriceProvider;
use Webkul\Omnibus\PriceProviders\ConfigurableOmnibusPriceProvider;
use Webkul\Omnibus\PriceProviders\DefaultOmnibusPriceProvider;
use Webkul\Omnibus\PriceProviders\GroupedOmnibusPriceProvider;
use Webkul\Omnibus\Services\OmnibusPriceProviderResolver;

// ============================================================================
// Resolver Mapping
// ============================================================================

it('resolves the default provider for leaf product types', function (string $type) {
    $product = match ($type) {
        'simple' => $this->createSimpleProduct(),
        'virtual' => $this->createVirtualProduct(),
        'downloadable' => $this->createDownloadableProduct(),
    };

    expect(app(OmnibusPriceProviderResolver::class)->resolve($product))
        ->toBeInstanceOf(DefaultOmnibusPriceProvider::class);
})->with(['simple', 'virtual', 'downloadable']);

it('resolves the configurable provider for configurable products', function () {
    $product = $this->createConfigurableProduct();

    expect(app(OmnibusPriceProviderResolver::class)->resolve($product))
        ->toBeInstanceOf(ConfigurableOmnibusPriceProvider::class);
});

it('resolves the grouped provider for grouped products', function () {
    $product = $this->createGroupedProduct();

    expect(app(OmnibusPriceProviderResolver::class)->resolve($product))
        ->toBeInstanceOf(GroupedOmnibusPriceProvider::class);
});

it('resolves the bundle provider for bundle products', function () {
    $product = $this->createBundleProduct();

    expect(app(OmnibusPriceProviderResolver::class)->resolve($product))
        ->toBeInstanceOf(BundleOmnibusPriceProvider::class);
});

// ============================================================================
// Descendant Resolution — leaf types
// ============================================================================

it('returns no descendants for leaf product types', function (string $type) {
    $product = match ($type) {
        'simple' => $this->createSimpleProduct(),
        'virtual' => $this->createVirtualProduct(),
        'downloadable' => $this->createDownloadableProduct(),
    };

    $provider = app(OmnibusPriceProviderResolver::class)->resolve($product);

    expect($provider->getDescendantProductIds($product))->toBe([]);
})->with(['simple', 'virtual', 'downloadable']);

// ============================================================================
// Descendant Resolution — composite types
// ============================================================================

it('returns every variant id for a configurable product', function () {
    $configurable = $this->createConfigurableProduct([100, 200]);

    $provider = app(OmnibusPriceProviderResolver::class)->resolve($configurable);

    $variantIds = $configurable->variants->pluck('id')->sort()->values()->all();
    $descendantIds = collect($provider->getDescendantProductIds($configurable))->sort()->values()->all();

    expect($descendantIds)->toBe($variantIds);
});

it('returns every associated product id for a grouped product', function () {
    $grouped = $this->createGroupedProduct([100, 200]);

    $provider = app(OmnibusPriceProviderResolver::class)->resolve($grouped);

    $associatedIds = $grouped->grouped_products->pluck('associated_product_id')->sort()->values()->all();
    $descendantIds = collect($provider->getDescendantProductIds($grouped))->sort()->values()->all();

    expect($descendantIds)->toBe($associatedIds);
});

it('returns option product ids for a bundle product (not the parent id)', function () {
    $bundle = $this->createBundleProduct([100, 200]);

    $provider = app(OmnibusPriceProviderResolver::class)->resolve($bundle);

    $descendantIds = $provider->getDescendantProductIds($bundle);

    // Must contain option products, never the bundle parent.
    expect($descendantIds)->not->toContain($bundle->id);
    expect($descendantIds)->not->toBeEmpty();

    // Each id must exist as a real product distinct from the parent.
    foreach ($descendantIds as $id) {
        expect($id)->not->toBe($bundle->id);
    }
});
