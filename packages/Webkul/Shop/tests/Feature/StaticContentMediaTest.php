<?php

use Illuminate\Support\Facades\Storage;
use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\Theme\Enums\SectionTypeEnum;
use Webkul\Theme\Models\Section;
use Webkul\Theme\ThemeStorage;

use function Pest\Laravel\get;

/**
 * Give the current channel a static content section carrying the markup provided.
 */
function makeStaticContent(string $html, string $css = ''): Section
{
    $channel = core()->getCurrentChannel();

    Section::query()->where('type', SectionTypeEnum::STATIC_CONTENT->value)->delete();

    $section = Section::factory()->create([
        'channel_id' => $channel->id,
        'theme_code' => $channel->theme ?: 'default',
        'type' => SectionTypeEnum::STATIC_CONTENT->value,
        'status' => 1,
    ]);

    $section->translateOrNew(app()->getLocale())->options = [
        'html' => $html,
        'css' => $css,
    ];

    $section->save();

    return $section;
}

beforeEach(function () {
    config(['responsecache.enabled' => false]);

    ResponseCache::clear();

    Storage::fake();
});

// ============================================================================
// Media In Static Content
// ============================================================================

it('should resolve a media reference in the markup to the url it is served from', function () {
    makeStaticContent('<img data-src="'.ThemeStorage::MEDIA_REFERENCE.'themes/default/sections/1/a.webp" alt="">');

    get(route('shop.home.index'))
        ->assertOk()
        ->assertSee('data-src="/storage/themes/default/sections/1/a.webp"', false)
        ->assertDontSee(ThemeStorage::MEDIA_REFERENCE, false);
});

it('should resolve a media reference in the section stylesheet', function () {
    makeStaticContent(
        '<div class="hero"></div>',
        '.hero{background-image:url('.ThemeStorage::MEDIA_REFERENCE.'themes/default/sections/1/a.webp)}'
    );

    get(route('shop.home.index'))
        ->assertOk()
        ->assertSee('url(/storage/themes/default/sections/1/a.webp)', false)
        ->assertDontSee(ThemeStorage::MEDIA_REFERENCE, false);
});

it('should render every spelling a section has ever recorded', function (string $authored) {
    makeStaticContent('<img data-src="'.$authored.'" alt="">');

    get(route('shop.home.index'))
        ->assertOk()
        ->assertSee('data-src="/storage/themes/default/sections/1/a.webp"', false);
})->with([
    'reference' => '__media__/themes/default/sections/1/a.webp',
    'addressed from the site root' => '/storage/themes/default/sections/1/a.webp',
    'addressed relative to the page' => 'storage/themes/default/sections/1/a.webp',
]);

it('should leave markup that references nothing stored exactly as it was authored', function () {
    makeStaticContent('<img src="https://cdn.example.com/a.webp" alt=""><a href="/page/about-us">About</a>');

    get(route('shop.home.index'))
        ->assertOk()
        ->assertSee('<img src="https://cdn.example.com/a.webp" alt="">', false)
        ->assertSee('<a href="/page/about-us">About</a>', false);
});
