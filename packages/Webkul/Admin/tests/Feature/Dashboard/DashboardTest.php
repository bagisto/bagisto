<?php

use function Pest\Laravel\get;

it('should return the dashboard index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.dashboard.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.dashboard.index.title'))
        ->assertSeeText(trans('admin::app.dashboard.index.overall-details'))
        ->assertSeeText(trans('admin::app.dashboard.index.total-sales'))
        ->assertSeeText(trans('admin::app.dashboard.index.product-image'))
        ->assertSeeText(trans('admin::app.dashboard.index.today-sales'));
});

it('should ')