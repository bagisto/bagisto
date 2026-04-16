<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to events with marketing.communications.events permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.communications', 'marketing.communications.events']);

    $response = get(route('admin.marketing.communications.events.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to events without marketing.communications.events permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.marketing.communications.events.index'))
        ->assertStatus(401);
});
