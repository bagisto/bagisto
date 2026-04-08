<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to sitemaps with marketing.search_seo.sitemaps permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.search_seo', 'marketing.search_seo.sitemaps']);

    $response = get(route('admin.marketing.search_seo.sitemaps.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to sitemaps without marketing.search_seo.sitemaps permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.marketing.search_seo.sitemaps.index'))
        ->assertStatus(401);
});
