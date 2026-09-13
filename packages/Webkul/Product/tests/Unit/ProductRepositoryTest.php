<?php

use Webkul\Product\Repositories\ProductRepository;

// ============================================================================
// Composite Parents
// ============================================================================

it('should return the configurable parent of its variants once', function () {
    $configurable = $this->createConfigurableProduct([100, 200]);

    $parentIds = app(ProductRepository::class)->getCompositeParentIds($configurable->variants->pluck('id')->all());

    expect($parentIds)->toEqual([$configurable->id]);
});

it('should return the grouped parent of its associated products once', function () {
    $grouped = $this->createGroupedProduct([100, 200]);

    $parentIds = app(ProductRepository::class)->getCompositeParentIds($grouped->grouped_products->pluck('associated_product_id')->all());

    expect($parentIds)->toEqual([$grouped->id]);
});

it('should return the bundle parent of its option products once', function () {
    $bundle = $this->createBundleProduct([100, 200]);

    $optionProductIds = $bundle->bundle_options->first()->bundle_option_products->pluck('product_id')->all();

    expect(app(ProductRepository::class)->getCompositeParentIds($optionProductIds))->toEqual([$bundle->id]);
});

it('should return every composite parent built from a mixed set of products', function () {
    $configurable = $this->createConfigurableProduct([100]);

    $grouped = $this->createGroupedProduct([100]);

    $bundle = $this->createBundleProduct([100]);

    $parentIds = app(ProductRepository::class)->getCompositeParentIds([
        $configurable->variants->first()->id,
        $grouped->grouped_products->first()->associated_product_id,
        $bundle->bundle_options->first()->bundle_option_products->first()->product_id,
    ]);

    expect($parentIds)->toEqualCanonicalizing([$configurable->id, $grouped->id, $bundle->id]);
});

it('should return nothing for products no composite product is built from', function () {
    $product = $this->createSimpleProduct();

    expect(app(ProductRepository::class)->getCompositeParentIds([$product->id]))->toBe([]);
});

it('should return nothing for no products', function () {
    expect(app(ProductRepository::class)->getCompositeParentIds([]))->toBe([]);
});
