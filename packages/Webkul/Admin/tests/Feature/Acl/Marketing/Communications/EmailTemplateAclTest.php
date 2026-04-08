<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to email templates with marketing.communications.email_templates permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.communications', 'marketing.communications.email_templates']);

    $response = get(route('admin.marketing.communications.email_templates.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to email templates without marketing.communications.email_templates permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.marketing.communications.email_templates.index'))
        ->assertStatus(401);
});
