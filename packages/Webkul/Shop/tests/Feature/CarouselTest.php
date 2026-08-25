<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;

it('server-renders the first carousel image so the LCP image is discoverable', function () {
    $html = Blade::render('<x-shop::carousel :options="$options" />', [
        'options' => [
            'images' => [
                ['image' => 'themes/default/sections/1/hero.webp', 'title' => 'Hero banner', 'link' => ''],
            ],
        ],
    ]);

    expect($html)
        ->toContain('fetchpriority="high"')
        ->toContain('src="'.Storage::url('themes/default/sections/1/hero.webp').'"')
        ->toContain('cache/medium/themes/default/sections/1/hero.webp')
        ->toContain('alt="Hero banner"')
        ->not->toContain('loading="lazy"');
});

it('resolves a stored carousel path against the disk rather than assuming a storage url', function () {
    $html = Blade::render('<x-shop::carousel :options="$options" />', [
        'options' => [
            'images' => [
                ['image' => 'themes/default/sections/1/hero.webp', 'title' => 'Hero', 'link' => ''],
            ],
        ],
    ]);

    expect($html)
        ->toContain(Storage::url('themes/default/sections/1/hero.webp'))
        ->not->toContain('src="themes/default/sections/1/hero.webp"');
});

it('still renders a path recorded with the old storage prefix', function () {
    $html = Blade::render('<x-shop::carousel :options="$options" />', [
        'options' => [
            'images' => [
                ['image' => 'storage/themes/default/sections/1/hero.webp', 'title' => 'Hero', 'link' => ''],
            ],
        ],
    ]);

    expect($html)
        ->toContain(Storage::url('themes/default/sections/1/hero.webp'))
        ->toContain('cache/medium/themes/default/sections/1/hero.webp')
        ->not->toContain('cache/medium/storage/');
});

it('skips a carousel entry that records no image', function () {
    $html = Blade::render('<x-shop::carousel :options="$options" />', [
        'options' => [
            'images' => [
                ['image' => '', 'title' => 'Empty', 'link' => ''],
                ['image' => 'themes/default/sections/1/hero.webp', 'title' => 'Hero', 'link' => ''],
            ],
        ],
    ]);

    expect($html)
        ->toContain('alt="Hero"')
        ->not->toContain('alt="Empty"');
});

it('falls back to a shimmer placeholder when the carousel has no images', function () {
    $html = Blade::render('<x-shop::carousel :options="$options" />', [
        'options' => ['images' => []],
    ]);

    expect($html)
        ->toContain('shimmer')
        ->not->toContain('fetchpriority="high"');
});
