<?php

// ============================================================================
// Channel Themes
// ============================================================================

it('should build each storefront theme with the views namespace and vite build it registers', function () {
    config(['themes.shop.namespaced' => array_merge(config('themes.shop.default'), [
        'name' => 'Namespaced',
        'views_namespace' => 'namespaced',
        'vite' => [
            'hot_file' => 'shop-namespaced-vite.hot',
            'build_directory' => 'themes/shop/namespaced/build',
            'package_assets_directory' => 'src/Resources/assets',
        ],
    ])]);

    $theme = collect(themes()->getChannelThemes())->firstWhere('code', 'namespaced');

    expect($theme->viewsNamespace)->toBe('namespaced')
        ->and($theme->vite)->toBe(config('themes.shop.namespaced.vite'));
});
