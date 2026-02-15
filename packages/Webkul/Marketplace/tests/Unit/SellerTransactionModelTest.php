<?php

use Webkul\Marketplace\Models\SellerTransaction;

it('uses the correct database table', function () {
    expect((new SellerTransaction)->getTable())->toBe('marketplace_seller_transactions');
});

it('has fillable attributes properly defined', function () {
    $model = new SellerTransaction;

    expect($model->getFillable())->toContain(
        'seller_id',
        'transaction_id',
        'type',
        'amount',
        'base_amount',
        'comment',
        'method',
    );
});

it('casts amount fields to decimal', function () {
    $casts = (new SellerTransaction)->getCasts();

    expect($casts['amount'])->toBe('decimal:4')
        ->and($casts['base_amount'])->toBe('decimal:4');
});

it('has a belongs-to seller relationship', function () {
    expect((new SellerTransaction)->seller())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
