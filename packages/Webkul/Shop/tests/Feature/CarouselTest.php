<?php

use Illuminate\Support\Facades\Blade;

it('server-renders the first carousel image so the LCP image is discoverable', function () {
    $html = Blade::render('<x-shop::carousel :options="$options" />', [
        'options' => [
            'images' => [
                ['image' => 'storage/theme/1/hero.webp', 'title' => 'Hero banner', 'link' => ''],
            ],
        ],
    ]);

    expect($html)
        ->toContain('fetchpriority="high"')
        ->toContain('src="storage/theme/1/hero.webp"')
        ->toContain('cache/medium/theme/1/hero.webp')
        ->toContain('alt="Hero banner"')
        ->not->toContain('loading="lazy"');
});

it('falls back to a shimmer placeholder when the carousel has no images', function () {
    $html = Blade::render('<x-shop::carousel :options="$options" />', [
        'options' => ['images' => []],
    ]);

    expect($html)
        ->toContain('shimmer')
        ->not->toContain('fetchpriority="high"');
});
