<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Webkul\Core\Facades\SystemConfig;
use Webkul\Customer\Models\Customer;
use Webkul\FPC\CacheProfiles\FullPageCacheProfile;
use Webkul\FPC\FullPageCache;

/**
 * Save a Full Page Cache setting for the current channel and locale.
 */
function saveFullPageCacheSetting(string $field, mixed $value): void
{
    test()->setConfig('cache_management.full_page_cache.settings.'.$field, $value);
}

/**
 * A request on a route the page cache middleware is attached to.
 */
function cacheableRequest(): Request
{
    return requestOnRoute(['web', 'shop', 'cache.response']);
}

/**
 * A request on a route the page cache middleware is not attached to.
 */
function uncacheableRequest(): Request
{
    return requestOnRoute(['web', 'shop']);
}

/**
 * A GET request bound to a route carrying the given middleware.
 */
function requestOnRoute(array $middleware): Request
{
    $request = Request::create('/');

    $request->setRouteResolver(fn () => (new Route('GET', '/', []))->middleware($middleware));

    return $request;
}

beforeEach(function () {
    $this->profile = app(FullPageCacheProfile::class);

    $this->request = Request::create('/');

    config(['responsecache.enabled' => true]);
});

// ============================================================================
// Enablement
// ============================================================================

it('should serve pages from the cache once the deployment switch and the admin setting are both on', function () {
    saveFullPageCacheSetting('enabled', '1');

    expect($this->profile->enabled($this->request))->toBeTrue();
});

it('should stop serving pages from the cache when the admin turns the setting off', function () {
    saveFullPageCacheSetting('enabled', '0');

    expect($this->profile->enabled($this->request))->toBeFalse();
});

it('should serve pages from the cache when the setting has never been saved', function () {
    expect($this->profile->enabled($this->request))->toBeTrue();
});

it('should never run the page cache for a signed-in customer, so their own pages are neither served nor stored', function () {
    saveFullPageCacheSetting('enabled', '1');

    auth()->guard('customer')->login(Customer::factory()->create());

    expect($this->profile->enabled($this->request))->toBeFalse()
        ->and(FullPageCache::willCache(cacheableRequest()))->toBeFalse();
});

it('should keep the deployment switch as the final word over the admin setting', function () {
    config(['responsecache.enabled' => false]);

    saveFullPageCacheSetting('enabled', '1');

    expect($this->profile->enabled($this->request))->toBeFalse();
});

it('should let the request through when the settings store cannot be read', function () {
    SystemConfig::shouldReceive('getConfigData')
        ->andThrow(new RuntimeException('the database is not usable yet'));

    expect($this->profile->enabled($this->request))->toBeTrue();
});

// ============================================================================
// Cache Lifetime
// ============================================================================

it('should cache a page for the number of minutes configured in the admin panel', function () {
    saveFullPageCacheSetting('lifetime', '15');

    expect($this->profile->cacheLifetimeInSeconds($this->request))->toBe(15 * 60);
});

it('should fall back to the application lifetime when no admin lifetime is set', function () {
    expect($this->profile->cacheLifetimeInSeconds($this->request))
        ->toBe((int) config('responsecache.cache.lifetime_in_seconds'));
});

it('should fall back to the application lifetime when the admin lifetime is cleared', function () {
    config(['responsecache.cache.lifetime_in_seconds' => 3600]);

    saveFullPageCacheSetting('lifetime', '');

    expect($this->profile->cacheLifetimeInSeconds($this->request))->toBe(3600);
});

it('should override the lifetime method the response cache actually calls', function () {
    $method = new ReflectionMethod(FullPageCacheProfile::class, 'cacheLifetimeInSeconds');

    expect($method->getDeclaringClass()->getName())->toBe(FullPageCacheProfile::class);
});

// ============================================================================
// Cacheable Requests
// ============================================================================

it('should keep caching successful storefront GET requests', function () {
    expect($this->profile->shouldCacheRequest($this->request))->toBeTrue()
        ->and($this->profile->shouldCacheRequest(Request::create('/', 'POST')))->toBeFalse();
});

it('should report a storefront page as one the cache will serve to everyone', function () {
    saveFullPageCacheSetting('enabled', '1');

    expect(FullPageCache::willCache(cacheableRequest()))->toBeTrue();
});

it('should report a page as uncached once the setting is off, so views keep the visitor\'s own state', function () {
    saveFullPageCacheSetting('enabled', '0');

    expect(FullPageCache::willCache(cacheableRequest()))->toBeFalse();
});

it('should report a route without the page cache middleware as uncached', function () {
    saveFullPageCacheSetting('enabled', '1');

    expect(FullPageCache::willCache(uncacheableRequest()))->toBeFalse();
});
