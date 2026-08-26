<?php

namespace Webkul\Product\Services\Search;

use Illuminate\Contracts\Foundation\Application;
use Webkul\Product\Contracts\SearchEngine;
use Webkul\Product\Contracts\SearchIndexer;
use Webkul\Product\Enums\SearchContextEnum;
use Webkul\Product\Enums\SearchEngineEnum;
use Webkul\Product\Services\Search\Indexers\NullIndexer;

class SearchEngineManager
{
    /**
     * Whether an external search engine may be used at all.
     */
    const ENABLED_KEY = 'search_engines.general.settings.enabled';

    /**
     * The engine a context falls back to.
     */
    const ENGINE_KEY = 'search_engines.general.settings.engine';

    /**
     * The engine the admin panel searches with.
     */
    const ADMIN_MODE_KEY = 'search_engines.general.products.admin_mode';

    /**
     * The engine the storefront searches with.
     */
    const STOREFRONT_MODE_KEY = 'search_engines.general.products.storefront_mode';

    /**
     * Create a new instance.
     */
    public function __construct(
        protected Application $app,
    ) {}

    /**
     * Resolve the search engine for a given context.
     */
    public function engine(SearchContextEnum $context = SearchContextEnum::STOREFRONT): SearchEngine
    {
        $driver = $this->resolveDriver($context);

        return $this->app->make("product.search.engine.{$driver->value}");
    }

    /**
     * Resolve the search indexer for the configured engine.
     */
    public function indexer(): SearchIndexer
    {
        if (! $this->isExternalEngineEnabled()) {
            return $this->app->make(NullIndexer::class);
        }

        $engine = $this->getMasterEngine();

        return $this->app->make("product.search.indexer.{$engine->value}");
    }

    /**
     * Whether an external search engine (non-database) is enabled.
     */
    public function isExternalEngineEnabled(): bool
    {
        return $this->getMasterEngine() !== SearchEngineEnum::DATABASE;
    }

    /**
     * Resolve which driver to use for a given context.
     */
    public function resolveDriver(SearchContextEnum $context = SearchContextEnum::STOREFRONT): SearchEngineEnum
    {
        $master = $this->getMasterEngine();

        if ($master === SearchEngineEnum::DATABASE) {
            return SearchEngineEnum::DATABASE;
        }

        $modeKey = $context === SearchContextEnum::ADMIN
            ? self::ADMIN_MODE_KEY
            : self::STOREFRONT_MODE_KEY;

        return $this->toEngine(core()->getConfigData($modeKey), $master);
    }

    /**
     * Get the master engine setting.
     */
    public function getMasterEngine(): SearchEngineEnum
    {
        if (! core()->getConfigData(self::ENABLED_KEY)) {
            return SearchEngineEnum::DATABASE;
        }

        return $this->toEngine(core()->getConfigData(self::ENGINE_KEY));
    }

    /**
     * Read a stored setting as an engine, falling back when it is unset or unknown.
     */
    protected function toEngine(mixed $value, SearchEngineEnum $fallback = SearchEngineEnum::DATABASE): SearchEngineEnum
    {
        if (! is_string($value) || $value === '') {
            return $fallback;
        }

        return SearchEngineEnum::tryFrom($value) ?? $fallback;
    }
}
