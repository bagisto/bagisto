<?php

use Webkul\Marketplace\Repositories\SellerOrderRepository;

it('returns empty earnings summary for a seller with no orders', function () {
    $seller = $this->createSeller();

    $earnings = app(SellerOrderRepository::class)->getEarningsSummary($seller->id);

    expect($earnings)->toBeArray()
        ->and($earnings['total_sales'])->toBe(0.0)
        ->and($earnings['total_commission'])->toBe(0.0)
        ->and($earnings['total_earnings'])->toBe(0.0)
        ->and($earnings['total_orders'])->toBe(0);
});

it('returns correct earnings summary keys', function () {
    $seller = $this->createSeller();

    $earnings = app(SellerOrderRepository::class)->getEarningsSummary($seller->id);

    expect($earnings)->toHaveKeys([
        'total_sales',
        'total_commission',
        'total_earnings',
        'total_orders',
    ]);
});
