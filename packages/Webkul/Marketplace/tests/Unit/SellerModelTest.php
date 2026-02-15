<?php

use Webkul\Marketplace\Models\Seller;
use Webkul\Marketplace\Models\SellerOrder;
use Webkul\Marketplace\Models\SellerProduct;
use Webkul\Marketplace\Models\SellerReview;
use Webkul\Marketplace\Models\SellerTransaction;

it('can create a seller with factory', function () {
    $seller = $this->createSeller();

    expect($seller)->toBeInstanceOf(Seller::class)
        ->and($seller->id)->toBeGreaterThan(0)
        ->and($seller->shop_title)->not->toBeEmpty()
        ->and($seller->url)->not->toBeEmpty()
        ->and($seller->is_approved)->toBeTrue()
        ->and($seller->status)->toBeTrue();

    $this->assertDatabaseHas('marketplace_sellers', [
        'id'          => $seller->id,
        'customer_id' => $seller->customer_id,
        'shop_title'  => $seller->shop_title,
    ]);
});

it('has a belongs-to customer relationship', function () {
    $seller = $this->createSeller();

    expect($seller->customer)->not->toBeNull()
        ->and($seller->customer->id)->toBe($seller->customer_id);
});

it('has a has-many products relationship', function () {
    $seller = $this->createSeller();

    expect($seller->products)->toBeEmpty();

    // The relationship method should return the correct type
    expect($seller->products())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

it('has a has-many orders relationship', function () {
    $seller = $this->createSeller();

    expect($seller->orders())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

it('has a has-many transactions relationship', function () {
    $seller = $this->createSeller();

    expect($seller->transactions())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

it('has a has-many reviews relationship', function () {
    $seller = $this->createSeller();

    expect($seller->reviews())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

it('computes average rating from approved reviews', function () {
    $seller = $this->createSeller();

    // No reviews = 0 rating
    expect($seller->average_rating)->toBe(0.0);

    // Add approved reviews
    $this->createSellerReview($seller, null, ['rating' => 5, 'status' => 'approved']);
    $this->createSellerReview($seller, null, ['rating' => 3, 'status' => 'approved']);

    // Add a pending review that should NOT count
    $this->createSellerReview($seller, null, ['rating' => 1, 'status' => 'pending']);

    $seller->refresh();

    // Average of 5 and 3 = 4.0 (pending review excluded)
    expect($seller->average_rating)->toBe(4.0);
});

it('returns only approved reviews via approvedReviews scope', function () {
    $seller = $this->createSeller();

    $this->createSellerReview($seller, null, ['status' => 'approved']);
    $this->createSellerReview($seller, null, ['status' => 'pending']);
    $this->createSellerReview($seller, null, ['status' => 'rejected']);

    expect($seller->approvedReviews)->toHaveCount(1);
});

it('casts is_approved and status to boolean', function () {
    $seller = $this->createSeller(['is_approved' => 1, 'status' => 1]);

    expect($seller->is_approved)->toBeBool()
        ->and($seller->status)->toBeBool();
});

it('can use unapproved factory state', function () {
    $customer = \Webkul\Faker\Helpers\Customer::class;
    $customer = (new $customer)->factory()->create();

    $seller = Seller::factory()->unapproved()->create(['customer_id' => $customer->id]);

    expect($seller->is_approved)->toBeFalse();
});

it('can use inactive factory state', function () {
    $customer = \Webkul\Faker\Helpers\Customer::class;
    $customer = (new $customer)->factory()->create();

    $seller = Seller::factory()->inactive()->create(['customer_id' => $customer->id]);

    expect($seller->status)->toBeFalse();
});

it('has fillable attributes properly defined', function () {
    $seller = new Seller;

    expect($seller->getFillable())->toContain(
        'customer_id',
        'shop_title',
        'url',
        'description',
        'commission_percentage',
        'is_approved',
        'status',
        'phone',
        'city',
        'country',
    );
});

it('uses the correct database table', function () {
    $seller = new Seller;

    expect($seller->getTable())->toBe('marketplace_sellers');
});
