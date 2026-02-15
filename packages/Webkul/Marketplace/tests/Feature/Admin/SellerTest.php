<?php

use function Pest\Laravel\get;
use function Pest\Laravel\putJson;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\postJson;

it('should display the sellers listing page for admin', function () {
    $this->loginAsAdmin();

    get(route('admin.marketplace.sellers.index'))
        ->assertOk();
});

it('should display the seller edit page for admin', function () {
    $seller = $this->createSeller();

    $this->loginAsAdmin();

    get(route('admin.marketplace.sellers.edit', $seller->id))
        ->assertOk();
});

it('should allow admin to update a seller', function () {
    $seller = $this->createSeller();

    $this->loginAsAdmin();

    putJson(route('admin.marketplace.sellers.update', $seller->id), [
        'commission_percentage' => 15,
        'is_approved'           => true,
        'status'                => true,
    ])->assertRedirect();

    $this->assertDatabaseHas('marketplace_sellers', [
        'id'                    => $seller->id,
        'commission_percentage' => 15.00,
        'is_approved'           => true,
    ]);
});

it('should validate commission percentage is between 0 and 100', function () {
    $seller = $this->createSeller();

    $this->loginAsAdmin();

    putJson(route('admin.marketplace.sellers.update', $seller->id), [
        'commission_percentage' => 150,
    ])->assertUnprocessable()
        ->assertJsonValidationErrorFor('commission_percentage');
});

it('should allow admin to approve a seller', function () {
    $seller = $this->createUnapprovedSeller();

    $this->loginAsAdmin();

    postJson(route('admin.marketplace.sellers.approve', $seller->id))
        ->assertRedirect();

    $this->assertDatabaseHas('marketplace_sellers', [
        'id'          => $seller->id,
        'is_approved' => true,
    ]);
});

it('should allow admin to delete a seller', function () {
    $seller = $this->createSeller();

    $this->loginAsAdmin();

    deleteJson(route('admin.marketplace.sellers.delete', $seller->id))
        ->assertOk()
        ->assertJsonFragment(['message' => trans('marketplace::app.admin.sellers.delete-success')]);

    $this->assertDatabaseMissing('marketplace_sellers', [
        'id' => $seller->id,
    ]);
});

it('should require admin authentication to access sellers page', function () {
    get(route('admin.marketplace.sellers.index'))
        ->assertStatus(302);
});
