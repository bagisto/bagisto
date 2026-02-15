<?php

use Webkul\Marketplace\Repositories\SellerRepository;

it('can find a seller by customer id', function () {
    $seller = $this->createSeller();

    $found = app(SellerRepository::class)->findByCustomerId($seller->customer_id);

    expect($found)->not->toBeNull()
        ->and($found->id)->toBe($seller->id);
});

it('returns null when customer id has no seller', function () {
    $found = app(SellerRepository::class)->findByCustomerId(999999);

    expect($found)->toBeNull();
});

it('can find a seller by url slug', function () {
    $seller = $this->createSeller();

    $found = app(SellerRepository::class)->findByUrl($seller->url);

    expect($found)->not->toBeNull()
        ->and($found->id)->toBe($seller->id);
});

it('returns null for non-existent url', function () {
    $found = app(SellerRepository::class)->findByUrl('non-existent-slug-xyz');

    expect($found)->toBeNull();
});

it('returns only approved and active sellers', function () {
    $activeSeller = $this->createSeller(['is_approved' => true, 'status' => true]);
    $unapproved = $this->createSeller(['is_approved' => false, 'status' => true]);
    $inactive = $this->createSeller(['is_approved' => true, 'status' => false]);

    $active = app(SellerRepository::class)->getActiveSellers();

    $activeIds = $active->pluck('id')->toArray();

    expect($activeIds)->toContain($activeSeller->id)
        ->and($activeIds)->not->toContain($unapproved->id)
        ->and($activeIds)->not->toContain($inactive->id);
});
