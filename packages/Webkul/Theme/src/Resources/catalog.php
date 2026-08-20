<?php

/*
|--------------------------------------------------------------------------
| Shop Theme Catalog
|--------------------------------------------------------------------------
|
| Themes offered for the storefront, mirroring https://bagisto.com/en/bagisto-themes/.
|
| An entry whose `code` matches a theme in `config/themes.php` is treated as installed.
| `screenshot` is a remote url, or a path relative to the admin asset directory.
|
*/

$screenshots = 'https://bagisto.com/wp-content/themes/bagisto/images/theme-listing/themes';

return [
    [
        'code' => 'default',
        'name' => 'Default',
        'author' => 'Webkul',
        'version' => '2.4',
        'url' => null,
        'demo_url' => 'https://commerce.bagisto.com/',
        'screenshot' => 'images/themes/default.jpg',
        'rating' => null,
        'tags' => ['free', 'starter'],
        'description' => 'The theme Bagisto ships with. A clean, responsive storefront that works out of the box for any catalog.',
    ], [
        'code' => 'ethereal-fashion',
        'name' => 'Ethereal Fashion',
        'author' => null,
        'version' => null,
        'url' => 'https://store.webkul.com/bagisto-fashion-commerce.html',
        'demo_url' => 'https://demo.bagisto.com/ethereal-fashion/',
        'screenshot' => $screenshots.'/fashion-theme.png',
        'rating' => null,
        'tags' => ['premium', 'fashion'],
        'description' => 'Elegant fashion theme designed for modern clothing brands and boutiques.',
    ], [
        'code' => 'vape-commerce',
        'name' => 'Vape Commerce',
        'author' => null,
        'version' => null,
        'url' => 'https://store.webkul.com/vape-commerce.html',
        'demo_url' => 'https://demo.bagisto.com/breeze-vape/',
        'screenshot' => $screenshots.'/vape-commerce-theme.png',
        'rating' => null,
        'tags' => ['premium', 'vape'],
        'description' => 'Dedicated Vape Commerce solution, alongside custom theme-building resources and a native blog module.',
    ], [
        'code' => 'quick-commerce',
        'name' => 'Quick Commerce',
        'author' => null,
        'version' => null,
        'url' => 'https://store.webkul.com/bagisto-quick-commerce.html',
        'demo_url' => 'https://demo.bagisto.com/commercia-retail/',
        'screenshot' => $screenshots.'/quick-commerce-theme.png',
        'rating' => null,
        'tags' => ['premium', 'quick-commerce'],
        'description' => 'Build lightning-fast online stores for quick commerce businesses effortlessly.',
    ], [
        'code' => 'jewellery-commerce',
        'name' => 'Jewellery Commerce',
        'author' => null,
        'version' => null,
        'url' => 'https://store.webkul.com/jewellery-commerce.html',
        'demo_url' => 'https://demo.bagisto.com/adornments-jewellery/',
        'screenshot' => $screenshots.'/dornments-theme.png',
        'rating' => null,
        'tags' => ['premium', 'jewellery'],
        'description' => 'Elegant jewelry theme crafted for premium online stores and brands.',
    ], [
        'code' => 'velora-perfume',
        'name' => 'Velora Perfume',
        'author' => null,
        'version' => null,
        'url' => 'https://store.webkul.com/bagisto-perfume-theme.html',
        'demo_url' => 'https://demo.bagisto.com/perfume-theme/',
        'screenshot' => $screenshots.'/perfume-commerce-theme.png',
        'rating' => null,
        'tags' => ['premium', 'perfume'],
        'description' => 'Velora perfume theme, built for fragrance catalogs and beauty storefronts.',
    ], [
        'code' => 'electronic',
        'name' => 'Electronic Theme',
        'author' => null,
        'version' => null,
        'url' => 'https://store.webkul.com/bagisto-laravel-electronic-theme.html',
        'demo_url' => 'https://demo.bagisto.com/electronic-theme/',
        'screenshot' => $screenshots.'/electronic-commerce-theme.png',
        'rating' => null,
        'tags' => ['premium', 'electronics'],
        'description' => 'Modern electronics theme designed for high-converting online stores.',
    ], [
        'code' => 'elvix',
        'name' => 'Bagisto Elvix',
        'author' => null,
        'version' => null,
        'url' => null,
        'demo_url' => 'https://demo.bagisto.com/elvix-theme/',
        'screenshot' => $screenshots.'/elvix-theme.png',
        'rating' => null,
        'tags' => ['electronics'],
        'description' => 'Bagisto Elvix theme.',
    ], [
        'code' => 'autrivo',
        'name' => 'Autrivo Automobile',
        'author' => null,
        'version' => null,
        'url' => null,
        'demo_url' => 'https://demo.bagisto.com/automobile-theme/',
        'screenshot' => $screenshots.'/autrivo-theme.png',
        'rating' => null,
        'tags' => ['automobile'],
        'description' => 'Autrivo automobile theme, built for vehicle and parts catalogs.',
    ],
];
