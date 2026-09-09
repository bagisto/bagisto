<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\ResponseCache\Facades\ResponseCache;

use function Pest\Laravel\postJson;

/**
 * Store a rendered storefront page, in a cache of this test's own.
 */
function cacheStorefrontPage(): Request
{
    config([
        'responsecache.enabled' => true,
        'responsecache.cache_store' => 'array',
    ]);

    $request = Request::create(url('/'), 'GET');

    ResponseCache::cacheResponse(
        $request,
        new Response('cached', 200, ['Content-Type' => 'text/html']),
        3600
    );

    return $request;
}

it('flushes every cached storefront page from the full page cache settings', function () {
    $request = cacheStorefrontPage();

    expect(ResponseCache::hasBeenCached($request))->toBeTrue();

    $this->loginAsAdmin();

    postJson(route('admin.configuration.cache-management.execute'), [
        'action' => 'clear-page-cache',
    ])->assertOk();

    expect(ResponseCache::hasBeenCached($request))->toBeFalse();
});

it('tells the operator what was flushed rather than naming the command it ran', function () {
    $this->loginAsAdmin();

    $response = postJson(route('admin.configuration.cache-management.execute'), [
        'action' => 'clear-page-cache',
    ])->assertOk();

    expect($response->json('message'))
        ->toBe(trans('admin::app.configuration.index.cache-management.full-page-cache.settings.flush-success'))
        ->not->toContain('responsecache:clear');
});

it('still names the command for the actions the cache console lists', function () {
    $this->loginAsAdmin();

    $response = postJson(route('admin.configuration.cache-management.execute'), [
        'action' => 'clear-views',
    ])->assertOk();

    expect($response->json('message'))->toContain('view:clear');
});

it('refuses a cache action it does not know', function () {
    $this->loginAsAdmin();

    postJson(route('admin.configuration.cache-management.execute'), [
        'action' => 'clear-everything-please',
    ])->assertStatus(422);
});

it('denies a guest the cache actions', function () {
    postJson(route('admin.configuration.cache-management.execute'), [
        'action' => 'clear-page-cache',
    ])->assertRedirect(route('admin.session.create'));
});
