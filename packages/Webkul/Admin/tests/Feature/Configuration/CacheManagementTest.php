<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\Admin\Services\CacheManagerService;

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

it('drops the page cache along with the application caches when clearing everything', function () {
    $request = cacheStorefrontPage();

    expect(ResponseCache::hasBeenCached($request))->toBeTrue();

    $this->loginAsAdmin();

    $response = postJson(route('admin.configuration.cache-management.execute'), [
        'action' => 'clear-all',
    ])->assertOk();

    expect(ResponseCache::hasBeenCached($request))->toBeFalse()
        ->and($response->json('command'))->toContain('responsecache:clear');
});

it('offers every action a sentence of its own', function () {
    $manager = app(CacheManagerService::class);

    $actions = array_keys(array_merge(
        $manager->getClearActions(),
        $manager->getBuildActions(),
        $manager->getPageActions()
    ));

    foreach ($actions as $action) {
        $key = 'admin::app.configuration.index.cache-management.results.'.$action;

        expect(trans($key))->not->toBe($key, "the {$action} action has no sentence of its own");
    }
});

it('answers a cache action with its own sentence rather than the command it ran', function (string $action) {
    $this->loginAsAdmin();

    $response = postJson(route('admin.configuration.cache-management.execute'), [
        'action' => $action,
    ])->assertOk();

    expect($response->json('message'))
        ->toBe(trans('admin::app.configuration.index.cache-management.results.'.$action))
        ->not->toContain(':');
})->with([
    'clear-all',
    'clear-config',
    'clear-cache',
    'clear-compiled',
    'clear-events',
    'clear-routes',
    'clear-views',
    'clear-page-cache',
]);

it('falls back to naming an action the way its button does when it has no sentence', function () {
    app()->bind(CacheManagerService::class, fn () => new class extends CacheManagerService
    {
        protected array $clearActions = [
            'clear-views' => 'view:clear',
            'clear-unnamed' => 'view:clear',
        ];
    });

    $this->loginAsAdmin();

    $response = postJson(route('admin.configuration.cache-management.execute'), [
        'action' => 'clear-unnamed',
    ])->assertOk();

    expect($response->json('message'))
        ->toBe(trans('admin::app.configuration.index.cache-management.action-success', ['action' => 'clear-unnamed']))
        ->not->toContain('view:clear');
});

it('still reports the command it ran, which the cache console prints', function () {
    $this->loginAsAdmin();

    $response = postJson(route('admin.configuration.cache-management.execute'), [
        'action' => 'clear-views',
    ])->assertOk();

    expect($response->json('command'))->toBe('view:clear');
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
