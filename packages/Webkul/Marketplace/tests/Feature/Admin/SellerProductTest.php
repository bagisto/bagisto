<?php

use Webkul\Marketplace\Models\SellerProduct;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\deleteJson;

it('should display seller products listing page for admin', function () {
    $this->loginAsAdmin();

    get(route('admin.marketplace.seller-products.index'))
        ->assertOk();
});

it('should allow admin to approve a seller product', function () {
    $seller = $this->createSeller();

    $sellerProduct = SellerProduct::create([
        'seller_id'   => $seller->id,
        'product_id'  => 1,
        'is_approved' => false,
        'condition'   => 'new',
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.marketplace.seller-products.approve', $sellerProduct->id))
        ->assertOk()
        ->assertJsonFragment(['message' => trans('marketplace::app.admin.sellers.product-approve-success')]);

    $this->assertDatabaseHas('marketplace_seller_products', [
        'id'          => $sellerProduct->id,
        'is_approved' => true,
    ]);
});

it('should allow admin to delete a seller product', function () {
    $seller = $this->createSeller();

    $sellerProduct = SellerProduct::create([
        'seller_id'   => $seller->id,
        'product_id'  => 1,
        'is_approved' => true,
        'condition'   => 'new',
    ]);

    $this->loginAsAdmin();

    deleteJson(route('admin.marketplace.seller-products.delete', $sellerProduct->id))
        ->assertOk()
        ->assertJsonFragment(['message' => trans('marketplace::app.admin.sellers.product-delete-success')]);

    $this->assertDatabaseMissing('marketplace_seller_products', [
        'id' => $sellerProduct->id,
    ]);
});
