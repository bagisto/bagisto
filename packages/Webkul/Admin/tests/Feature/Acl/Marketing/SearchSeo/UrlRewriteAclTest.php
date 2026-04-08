<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to url rewrites with marketing.search_seo.url_rewrites permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.search_seo', 'marketing.search_seo.url_rewrites']);

    $response = get(route('admin.marketing.search_seo.url_rewrites.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to url rewrites without marketing.search_seo.url_rewrites permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.marketing.search_seo.url_rewrites.index'))
        ->assertStatus(401);
});
