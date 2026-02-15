<?php

use Webkul\Marketplace\Models\SellerProduct;

it('uses the correct database table', function () {
    expect((new SellerProduct)->getTable())->toBe('marketplace_seller_products');
});

it('has fillable attributes properly defined', function () {
    $model = new SellerProduct;

    expect($model->getFillable())->toContain(
        'seller_id',
        'product_id',
        'is_approved',
        'condition',
        'price',
        'description',
    );
});

it('casts is_approved to boolean and price to decimal', function () {
    $casts = (new SellerProduct)->getCasts();

    expect($casts['is_approved'])->toBe('boolean')
        ->and($casts['price'])->toBe('decimal:4');
});

it('has a belongs-to seller relationship', function () {
    $model = new SellerProduct;

    expect($model->seller())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

it('has a belongs-to product relationship', function () {
    $model = new SellerProduct;

    expect($model->product())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
