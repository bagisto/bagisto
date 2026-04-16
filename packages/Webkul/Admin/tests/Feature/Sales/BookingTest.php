<?php

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;

// ============================================================================
// Index
// ============================================================================

it('should return the bookings index page', function () {
    $this->loginAsAdmin();

    get(route('admin.sales.bookings.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.booking.index.title'));
});

it('should deny guest access to the bookings index page', function () {
    get(route('admin.sales.bookings.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Calendar View
// ============================================================================

it('should return bookings data for calendar view', function () {
    $this->loginAsAdmin();

    getJson(route('admin.sales.bookings.get', ['view_type' => 'week']))
        ->assertOk()
        ->assertJsonStructure(['bookings']);
});
