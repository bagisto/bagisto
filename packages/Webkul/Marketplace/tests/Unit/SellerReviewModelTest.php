<?php

use Webkul\Marketplace\Models\SellerReview;

it('uses the correct database table', function () {
    expect((new SellerReview)->getTable())->toBe('marketplace_seller_reviews');
});

it('has fillable attributes properly defined', function () {
    $model = new SellerReview;

    expect($model->getFillable())->toContain(
        'seller_id',
        'customer_id',
        'rating',
        'title',
        'comment',
        'status',
    );
});

it('casts rating to integer', function () {
    $casts = (new SellerReview)->getCasts();

    expect($casts['rating'])->toBe('integer');
});

it('has a belongs-to seller relationship', function () {
    expect((new SellerReview)->seller())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

it('has a belongs-to customer relationship', function () {
    expect((new SellerReview)->customer())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
