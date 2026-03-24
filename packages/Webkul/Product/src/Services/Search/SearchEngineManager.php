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
            ? 'catalog.products.search.admin_mode'
            : 'catalog.products.search.storefront_mode';

        $value = core()->getConfigData($modeKey);

        return SearchEngineEnum::tryFrom($value) ?? SearchEngineEnum::DATABASE;
    }

    /**
     * Get the master engine setting.
     */
    public function getMasterEngine(): SearchEngineEnum
    {
        $value = core()->getConfigData('catalog.products.search.engine');

        return SearchEngineEnum::tryFrom($value) ?? SearchEngineEnum::DATABASE;
    }
}
