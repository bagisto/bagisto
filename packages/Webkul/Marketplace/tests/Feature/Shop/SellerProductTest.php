<?php

use Webkul\Marketplace\Models\SellerProduct;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\deleteJson;

it('should display the seller products listing page', function () {
    $seller = $this->createSeller();
    $this->loginAsSeller($seller);

    get(route('marketplace.seller.products.index'))
        ->assertOk();
});

it('should display the product create page', function () {
    $seller = $this->createSeller();
    $this->loginAsSeller($seller);

    get(route('marketplace.seller.products.create'))
        ->assertOk();
});

it('should validate required fields when storing a product', function () {
    $seller = $this->createSeller();
    $this->loginAsSeller($seller);

    postJson(route('marketplace.seller.products.store'), [])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('product_id')
        ->assertJsonValidationErrorFor('condition');
});

it('should validate condition must be new, used, or refurbished', function () {
    $seller = $this->createSeller();
    $this->loginAsSeller($seller);

    postJson(route('marketplace.seller.products.store'), [
        'product_id' => 1,
        'condition'  => 'broken',
    ])->assertUnprocessable()
        ->assertJsonValidationErrorFor('condition');
});

it('should prevent a seller from editing another seller product', function () {
    $seller1 = $this->createSeller();
    $seller2 = $this->createSeller();

    $product = SellerProduct::create([
        'seller_id'   => $seller1->id,
        'product_id'  => 1,
        'is_approved' => true,
        'condition'   => 'new',
    ]);

    $this->loginAsSeller($seller2);

    get(route('marketplace.seller.products.edit', $product->id))
        ->assertForbidden();
});

it('should prevent a seller from updating another seller product', function () {
    $seller1 = $this->createSeller();
    $seller2 = $this->createSeller();

    $product = SellerProduct::create([
        'seller_id'   => $seller1->id,
        'product_id'  => 1,
        'is_approved' => true,
        'condition'   => 'new',
    ]);

    $this->loginAsSeller($seller2);

    putJson(route('marketplace.seller.products.update', $product->id), [
        'condition' => 'used',
    ])->assertForbidden();
});

it('should prevent a seller from deleting another seller product', function () {
    $seller1 = $this->createSeller();
    $seller2 = $this->createSeller();

    $product = SellerProduct::create([
        'seller_id'   => $seller1->id,
        'product_id'  => 1,
        'is_approved' => true,
        'condition'   => 'new',
    ]);

    $this->loginAsSeller($seller2);

    deleteJson(route('marketplace.seller.products.delete', $product->id))
        ->assertForbidden();
});

it('should redirect to registration if non-seller accesses products', function () {
    $this->loginAsCustomer();

    get(route('marketplace.seller.products.index'))
        ->assertRedirect(route('marketplace.seller.register'));
});
