<?php

use function Pest\Laravel\get;

it('should allow access to dashboard with dashboard permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    $response = get(route('admin.dashboard.index'));

    expect($response->status())->not->toBe(401);
});

it('should deny access to dashboard without dashboard permission', function () {
    $this->loginAsAdminWithPermissions(['catalog']);

    get(route('admin.dashboard.index'))
        ->assertStatus(401);
});
