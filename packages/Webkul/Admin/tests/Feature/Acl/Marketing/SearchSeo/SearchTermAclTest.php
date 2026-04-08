<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to search terms with marketing.search_seo.search_terms permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.search_seo', 'marketing.search_seo.search_terms']);

    $response = get(route('admin.marketing.search_seo.search_terms.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to search terms without marketing.search_seo.search_terms permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.marketing.search_seo.search_terms.index'))
        ->assertStatus(401);
});
