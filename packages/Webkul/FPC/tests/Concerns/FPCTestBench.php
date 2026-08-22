<?php

namespace Webkul\FPC\Tests\Concerns;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\Core\Models\Currency;
use Webkul\Core\Models\Locale;

trait FPCTestBench
{
    /**
     * The locale `addSecondScope()` added to the current channel.
     */
    public ?Locale $secondLocale = null;

    /**
     * The currency `addSecondScope()` added to the current channel.
     */
    public ?Currency $secondCurrency = null;

    /**
     * Point the page cache at an in-memory store and switch it on.
     *
     * The configured store is the developer's real one, so a test that used it would evict pages
     * a running storefront is serving, and would read whatever that storefront left behind.
     */
    public function useIsolatedPageCache(): void
    {
        config([
            'responsecache.enabled' => true,
            'responsecache.cache_store' => 'array',
        ]);
    }

    /**
     * Store a rendered page for the given storefront path, in the given cache scope.
     */
    public function cachePage(string $path, ?string $scope = null): Request
    {
        $request = $this->pageRequest($path, $scope);

        ResponseCache::cacheResponse(
            $request,
            new Response('cached', 200, ['Content-Type' => 'text/html']),
            3600
        );

        return $request;
    }

    /**
     * The request the page cache keys a storefront path under, in the given scope.
     */
    public function pageRequest(string $path, ?string $scope = null): Request
    {
        $request = Request::create(url($path), 'GET');

        $request->attributes->add([
            'responsecache.cacheNameSuffix' => $scope ?? $this->currentScope(),
        ]);

        return $request;
    }

    /**
     * The scope a guest browsing the current channel, locale and currency has pages cached under.
     */
    public function currentScope(): string
    {
        return core()->getCurrentChannel()->code
            .'-'.core()->getCurrentLocale()->code
            .'-'.core()->getCurrentCurrency()->code
            .'-';
    }

    /**
     * Give the current channel a second locale and currency, and return the scope they form.
     *
     * A single-locale store cannot tell a listener that forgets one scope from one that forgets
     * them all, which is the distinction most of these tests turn on.
     */
    public function addSecondScope(): string
    {
        $channel = core()->getCurrentChannel();

        $this->secondLocale = Locale::factory()->create();

        $this->secondCurrency = Currency::factory()->create();

        $channel->locales()->attach($this->secondLocale->id);

        $channel->currencies()->attach($this->secondCurrency->id);

        return $channel->code.'-'.$this->secondLocale->code.'-'.$this->secondCurrency->code.'-';
    }

    /**
     * Assert the page cache still holds the given request.
     */
    public function assertPageCached(Request $request, string $message = ''): void
    {
        $this->assertTrue(ResponseCache::hasBeenCached($request), $message);
    }

    /**
     * Assert the page cache no longer holds the given request.
     */
    public function assertPageNotCached(Request $request, string $message = ''): void
    {
        $this->assertFalse(ResponseCache::hasBeenCached($request), $message);
    }
}
