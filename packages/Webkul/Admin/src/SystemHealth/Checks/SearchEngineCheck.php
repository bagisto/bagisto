<?php

namespace Webkul\Admin\SystemHealth\Checks;

use Webkul\Product\Enums\SearchEngineStatusEnum;
use Webkul\Product\Services\Search\SearchEngineAvailability;
use Webkul\Product\Services\Search\SearchEngineManager;

class SearchEngineCheck extends AbstractCheck
{
    /**
     * Create a new check instance.
     */
    public function __construct(
        protected SearchEngineManager $searchEngines,
        protected SearchEngineAvailability $searchAvailability
    ) {}

    /**
     * The translation key segment naming what this check looks after.
     */
    public function name(): string
    {
        return 'search-engine';
    }

    /**
     * Whether the catalog's search engine has a server behind it that does not answer as usable.
     */
    public function failing(): bool
    {
        if (! $this->applies()) {
            return false;
        }

        $status = $this->searchAvailability->probe($this->searchEngines->getMasterEngine())['status'] ?? null;

        return ! (SearchEngineStatusEnum::tryFrom((string) $status)?->isUsable() ?? false);
    }

    /**
     * Whether the catalog searches through an engine that runs on a server of its own.
     */
    public function applies(): bool
    {
        return $this->searchAvailability->isConnectable($this->searchEngines->getMasterEngine());
    }
}
