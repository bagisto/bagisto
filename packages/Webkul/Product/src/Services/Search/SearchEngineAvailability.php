<?php

namespace Webkul\Product\Services\Search;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use Webkul\Product\Contracts\SearchEngineConnection;
use Webkul\Product\Enums\SearchEngineEnum;
use Webkul\Product\Enums\SearchEngineStatusEnum;

class SearchEngineAvailability
{
    /**
     * Container alias each engine's connection is bound at.
     */
    const CONNECTION_BINDING = 'product.search.connection.%s';

    /**
     * Cache key each engine's recorded verdict is held under.
     */
    const CACHE_KEY = 'search_engines.%s.availability';

    /**
     * How long a recorded verdict is trusted for, in seconds.
     */
    const CACHE_TTL = 300;

    /**
     * Verdicts already read during this request, keyed by engine.
     */
    protected array $memoized = [];

    /**
     * Create a new instance.
     */
    public function __construct(
        protected Application $app,
    ) {}

    /**
     * Whether the engine answers to a connection at all.
     *
     * An engine that needs nothing to reach, such as the database, has none.
     */
    public function isConnectable(SearchEngineEnum $engine): bool
    {
        return $this->app->bound($this->binding($engine));
    }

    /**
     * Read the engine's last recorded verdict without touching the network.
     */
    public function cached(SearchEngineEnum $engine): ?array
    {
        if (array_key_exists($engine->value, $this->memoized)) {
            return $this->memoized[$engine->value];
        }

        return $this->memoized[$engine->value] = Cache::get($this->cacheKey($engine));
    }

    /**
     * Whether the engine's last recorded verdict says it is usable.
     */
    public function isAvailable(SearchEngineEnum $engine): bool
    {
        $status = $this->cached($engine)['status'] ?? null;

        return SearchEngineStatusEnum::tryFrom((string) $status)?->isUsable() ?? false;
    }

    /**
     * Ask the engine where it stands and record the verdict.
     */
    public function probe(SearchEngineEnum $engine): array
    {
        if (! $this->isConnectable($engine)) {
            return $this->record($engine, ['status' => SearchEngineStatusEnum::AVAILABLE->value]);
        }

        return $this->record($engine, $this->connection($engine)->probe());
    }

    /**
     * Resolve the engine's connection.
     */
    public function connection(SearchEngineEnum $engine): SearchEngineConnection
    {
        return $this->app->make($this->binding($engine));
    }

    /**
     * The cache key an engine's verdict is held under.
     */
    public function cacheKey(SearchEngineEnum $engine): string
    {
        return sprintf(self::CACHE_KEY, $engine->value);
    }

    /**
     * Record a verdict for later reads.
     */
    protected function record(SearchEngineEnum $engine, array $verdict): array
    {
        Cache::put($this->cacheKey($engine), $verdict, self::CACHE_TTL);

        return $this->memoized[$engine->value] = $verdict;
    }

    /**
     * The container alias an engine's connection is bound at.
     */
    protected function binding(SearchEngineEnum $engine): string
    {
        return sprintf(self::CONNECTION_BINDING, $engine->value);
    }
}
