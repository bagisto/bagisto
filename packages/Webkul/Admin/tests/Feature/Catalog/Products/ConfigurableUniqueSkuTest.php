<?php

use Illuminate\Support\Facades\Validator;
use Webkul\Admin\Validations\ConfigurableUniqueSku;
use Webkul\Product\Models\Product;

/**
 * Validate the posted variant skus the way the product form does, against the given sibling ids.
 */
function validateVariantSkus(array $variants, array $currentIds = [])
{
    request()->merge(['variants' => $variants]);

    return Validator::make(
        ['variants' => $variants],
        ['variants.*.sku' => [new ConfigurableUniqueSku($currentIds)]]
    );
}

// ============================================================================
// Variant SKU Uniqueness
// ============================================================================

it('should accept variant skus no other product holds', function () {
    $validator = validateVariantSkus([
        ['sku' => 'alpaca-jumper-s'],
        ['sku' => 'alpaca-jumper-m'],
    ]);

    expect($validator->passes())->toBeTrue();
});

it('should refuse a variant sku another product already holds', function () {
    $other = Product::factory()->create();

    $validator = validateVariantSkus([
        ['sku' => $other->sku],
    ]);

    expect($validator->fails())->toBeTrue();
});

it('should refuse the same sku twice within one submission', function () {
    $validator = validateVariantSkus([
        ['sku' => 'alpaca-jumper-s'],
        ['sku' => 'alpaca-jumper-s'],
    ]);

    expect($validator->fails())->toBeTrue();
});

it('should let a configurable keep the skus of its own variants', function () {
    $variant = Product::factory()->create();

    $validator = validateVariantSkus([
        ['sku' => $variant->sku],
    ], [$variant->id]);

    expect($validator->passes())->toBeTrue();
});

it('should report the taken sku with the admin message', function () {
    $other = Product::factory()->create();

    $validator = validateVariantSkus([
        ['sku' => $other->sku],
    ]);

    expect($validator->errors()->first('variants.0.sku'))
        ->toBe(trans('admin::app.catalog.products.index.already-taken', ['name' => 'variants.0.sku']));
});
