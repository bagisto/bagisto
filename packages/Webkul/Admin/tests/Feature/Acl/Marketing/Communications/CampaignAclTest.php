<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to campaigns with marketing.communications.campaigns permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.communications', 'marketing.communications.campaigns']);

    $response = get(route('admin.marketing.communications.campaigns.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to campaigns without marketing.communications.campaigns permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.marketing.communications.campaigns.index'))
        ->assertStatus(401);
});
