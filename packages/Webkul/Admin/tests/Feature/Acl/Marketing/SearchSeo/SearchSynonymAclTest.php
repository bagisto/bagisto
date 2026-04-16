<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to search synonyms with marketing.search_seo.search_synonyms permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.search_seo', 'marketing.search_seo.search_synonyms']);

    $response = get(route('admin.marketing.search_seo.search_synonyms.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to search synonyms without marketing.search_seo.search_synonyms permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.marketing.search_seo.search_synonyms.index'))
        ->assertStatus(401);
});
