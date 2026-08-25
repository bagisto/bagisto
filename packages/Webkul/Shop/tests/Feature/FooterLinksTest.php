<?php

use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\Theme\Models\Section;

use function Pest\Laravel\get;

/**
 * The full page cache keeps the storefront in a store of its own, which outlives the
 * run, so a page rendered by an earlier test would answer for this one.
 */
beforeEach(function () {
    config(['responsecache.enabled' => false]);

    ResponseCache::clear();
});

/**
 * Give the current channel a footer with the links provided.
 */
function makeFooterLinks(array $links): Section
{
    $channel = core()->getCurrentChannel();

    Section::query()->where('type', Section::FOOTER_LINKS)->delete();

    $section = Section::factory()->create([
        'channel_id' => $channel->id,
        'theme_code' => $channel->theme ?: 'default',
        'type' => Section::FOOTER_LINKS,
        'status' => 1,
    ]);

    $section->translateOrNew(app()->getLocale())->options = ['column_1' => $links];

    $section->save();

    return $section;
}

it('resolves a footer link recorded as a path against the site serving the request', function () {
    makeFooterLinks([
        ['url' => 'page/about-us', 'title' => 'About Us'],
    ]);

    get(route('shop.home.index'))
        ->assertOk()
        ->assertSee('href="'.url('page/about-us').'"', false);
});

it('leaves a footer link to somewhere else exactly as it was entered', function (string $url) {
    makeFooterLinks([
        ['url' => $url, 'title' => 'Elsewhere'],
    ]);

    get(route('shop.home.index'))
        ->assertOk()
        ->assertSee('href="'.$url.'"', false);
})->with([
    'external https' => 'https://facebook.com/bagisto',
    'external http' => 'http://twitter.com/bagisto',
    'protocol relative' => '//cdn.example.com/promo',
    'mail address' => 'mailto:hello@example.com',
    'telephone' => 'tel:+1234567890',
    'anchor' => '#top',
]);

it('renders internal and external footer links side by side', function () {
    makeFooterLinks([
        ['url' => 'page/about-us', 'title' => 'About Us'],
        ['url' => 'https://facebook.com/bagisto', 'title' => 'Facebook'],
    ]);

    get(route('shop.home.index'))
        ->assertOk()
        ->assertSee('href="'.url('page/about-us').'"', false)
        ->assertSee('href="https://facebook.com/bagisto"', false);
});

it('still resolves a footer link recorded as a whole url before links became paths', function () {
    makeFooterLinks([
        ['url' => config('app.url').'/page/about-us', 'title' => 'About Us'],
    ]);

    get(route('shop.home.index'))
        ->assertOk()
        ->assertSee('href="'.config('app.url').'/page/about-us"', false);
});
