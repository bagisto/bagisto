<?php

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

it('should display the public sellers listing page', function () {
    $this->createSeller(['is_approved' => true, 'status' => true]);

    get(route('marketplace.sellers.index'))
        ->assertOk();
});

it('should display a seller public profile page', function () {
    $seller = $this->createSeller(['is_approved' => true, 'status' => true]);

    get(route('marketplace.seller.show', $seller->url))
        ->assertOk();
});

it('should return 404 for non-existent seller url', function () {
    get(route('marketplace.seller.show', 'non-existent-seller'))
        ->assertNotFound();
});

it('should return 404 for unapproved seller profile', function () {
    $seller = $this->createUnapprovedSeller();

    get(route('marketplace.seller.show', $seller->url))
        ->assertNotFound();
});

it('should return 404 for inactive seller profile', function () {
    $seller = $this->createInactiveSeller();

    get(route('marketplace.seller.show', $seller->url))
        ->assertNotFound();
});

it('should allow an authenticated customer to submit a review', function () {
    $seller = $this->createSeller();
    $customer = $this->loginAsCustomer();

    postJson(route('marketplace.seller.review.store', $seller->url), [
        'rating'  => 5,
        'title'   => 'Excellent seller',
        'comment' => 'Very fast shipping and great products!',
    ])->assertRedirect();

    $this->assertDatabaseHas('marketplace_seller_reviews', [
        'seller_id'   => $seller->id,
        'customer_id' => $customer->id,
        'rating'      => 5,
        'title'       => 'Excellent seller',
        'status'      => 'pending',
    ]);
});

it('should validate review data when submitting', function () {
    $seller = $this->createSeller();
    $this->loginAsCustomer();

    postJson(route('marketplace.seller.review.store', $seller->url), [])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('rating')
        ->assertJsonValidationErrorFor('title')
        ->assertJsonValidationErrorFor('comment');
});

it('should validate rating is between 1 and 5', function () {
    $seller = $this->createSeller();
    $this->loginAsCustomer();

    postJson(route('marketplace.seller.review.store', $seller->url), [
        'rating'  => 6,
        'title'   => 'Test',
        'comment' => 'Test comment',
    ])->assertUnprocessable()
        ->assertJsonValidationErrorFor('rating');

    postJson(route('marketplace.seller.review.store', $seller->url), [
        'rating'  => 0,
        'title'   => 'Test',
        'comment' => 'Test comment',
    ])->assertUnprocessable()
        ->assertJsonValidationErrorFor('rating');
});

it('should require authentication to submit a review', function () {
    $seller = $this->createSeller();

    postJson(route('marketplace.seller.review.store', $seller->url), [
        'rating'  => 5,
        'title'   => 'Test',
        'comment' => 'Test comment',
    ])->assertStatus(302);
});
