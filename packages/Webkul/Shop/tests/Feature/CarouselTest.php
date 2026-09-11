<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FilesystemAdapter;

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
        ->toContain('src="'.Storage::url('theme/1/hero.webp').'"')
        ->toContain(url('cache/medium/theme/1/hero.webp'))
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

it('should size every slide through the image cache templates rather than rewriting its path', function () {
    $html = Blade::render('<x-shop::carousel :options="$options" />', [
        'options' => [
            'images' => [
                ['image' => 'storage/themes/default/sections/1/storage-hero.webp', 'title' => 'Hero', 'link' => ''],
            ],
        ],
    ]);

    $original = Storage::url('themes/default/sections/1/storage-hero.webp');

    expect($html)->toContain('srcset="'.$original.' 1920w, '
        .url('cache/large/themes/default/sections/1/storage-hero.webp').' 1280w, '
        .url('cache/medium/themes/default/sections/1/storage-hero.webp').' 1024w, '
        .url('cache/small/themes/default/sections/1/storage-hero.webp').' 768w"');
});

it('should leave out a slide that has no image yet', function () {
    $html = Blade::render('<x-shop::carousel :options="$options" />', [
        'options' => [
            'images' => [
                ['image' => '', 'title' => 'Empty slide', 'link' => ''],
                ['image' => 'storage/theme/1/second.webp', 'title' => 'Second', 'link' => ''],
            ],
        ],
    ]);

    expect($html)
        ->toContain('alt="Second"')
        ->not->toContain('Empty slide');
});

it('should link every slide size to its stored file on a disk that is not local', function () {
    Storage::shouldReceive('getAdapter')->andReturn(Mockery::mock(FilesystemAdapter::class));

    Storage::shouldReceive('url')->andReturnUsing(fn ($path) => 'https://cdn.example.com/'.$path);

    $html = Blade::render('<x-shop::carousel :options="$options" />', [
        'options' => [
            'images' => [
                ['image' => 'storage/theme/1/hero.webp', 'title' => 'Hero', 'link' => ''],
            ],
        ],
    ]);

    expect($html)
        ->toContain('src="https://cdn.example.com/theme/1/hero.webp"')
        ->not->toContain('cache/');
});
