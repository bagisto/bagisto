<?php

use Illuminate\Support\Facades\View;
use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;

use function Pest\Laravel\get;

// ============================================================================
// Paths Under The Admin Url
// ============================================================================

it('should answer a path under the admin url that no admin route matches as not found, showing no storefront page', function (string $path) {
    $shown = false;

    View::composer(['shop::home.index', 'shop::categories.view', 'shop::products.view'], function () use (&$shown) {
        $shown = true;
    });

    get('/'.trim(config('app.admin_url'), '/').'/'.$path)
        ->assertNotFound();

    expect($shown)->toBeFalse();
})->with([
    'path naming a storefront category' => [fn () => Category::factory()->has(CategoryTranslation::factory(), 'translations')->create()->slug],
    'path the slug pattern rejects' => ['report.html'],
]);
