<?php

use Webkul\Marketplace\Repositories\SellerProductRepository;

it('can get all products for a seller', function () {
    $seller = $this->createSeller();

    $repo = app(SellerProductRepository::class);
    $products = $repo->getSellerProducts($seller->id);

    expect($products)->toBeEmpty();
});

it('can get only approved products for a seller', function () {
    $seller = $this->createSeller();

    $repo = app(SellerProductRepository::class);
    $approved = $repo->getApprovedProducts($seller->id);

    expect($approved)->toBeEmpty();
});
