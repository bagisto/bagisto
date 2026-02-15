<?php

use Webkul\Marketplace\Repositories\SellerReviewRepository;

it('returns only approved reviews for a seller', function () {
    $seller = $this->createSeller();

    $this->createSellerReview($seller, null, ['status' => 'approved']);
    $this->createSellerReview($seller, null, ['status' => 'pending']);
    $this->createSellerReview($seller, null, ['status' => 'rejected']);
    $this->createSellerReview($seller, null, ['status' => 'approved']);

    $approved = app(SellerReviewRepository::class)->getApprovedReviews($seller->id);

    expect($approved)->toHaveCount(2);

    foreach ($approved as $review) {
        expect($review->status)->toBe('approved');
    }
});

it('returns empty collection when seller has no approved reviews', function () {
    $seller = $this->createSeller();

    $this->createSellerReview($seller, null, ['status' => 'pending']);

    $approved = app(SellerReviewRepository::class)->getApprovedReviews($seller->id);

    expect($approved)->toBeEmpty();
});
