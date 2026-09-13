<?php

use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to tax categories with settings.taxes.tax_categories permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.taxes', 'settings.taxes.tax_categories']);

    get(route('admin.settings.taxes.categories.index'))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to tax categories without settings.taxes.tax_categories permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.settings.taxes.categories.index'))
        ->assertUnauthorized();
});
