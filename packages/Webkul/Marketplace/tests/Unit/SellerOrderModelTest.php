<?php

use Webkul\Marketplace\Models\SellerOrder;

it('uses the correct database table', function () {
    expect((new SellerOrder)->getTable())->toBe('marketplace_seller_orders');
});

it('has fillable attributes properly defined', function () {
    $model = new SellerOrder;

    expect($model->getFillable())->toContain(
        'order_id',
        'seller_id',
        'order_item_id',
        'base_sub_total',
        'base_grand_total',
        'base_commission',
        'base_seller_total',
        'commission_percentage',
        'status',
    );
});

it('casts financial fields to decimal', function () {
    $casts = (new SellerOrder)->getCasts();

    expect($casts['base_sub_total'])->toBe('decimal:4')
        ->and($casts['base_grand_total'])->toBe('decimal:4')
        ->and($casts['base_commission'])->toBe('decimal:4')
        ->and($casts['base_seller_total'])->toBe('decimal:4')
        ->and($casts['commission_percentage'])->toBe('decimal:2');
});

it('has a belongs-to seller relationship', function () {
    expect((new SellerOrder)->seller())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

it('has a belongs-to order relationship', function () {
    expect((new SellerOrder)->order())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

it('has a belongs-to order-item relationship', function () {
    expect((new SellerOrder)->orderItem())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
