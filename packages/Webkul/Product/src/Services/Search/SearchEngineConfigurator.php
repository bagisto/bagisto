<?php

namespace Webkul\Product\Services\Search;

use Webkul\Product\Enums\SearchEngineEnum;

class SearchEngineConfigurator
{
    /**
     * Create a new instance.
     */
    public function __construct(
        protected SearchEngineAvailability $availability,
    ) {}

    /**
     * Point every connectable engine at the settings the admin recorded.
     */
    public function configure(): void
    {
        foreach (SearchEngineEnum::cases() as $engine) {
            if (! $this->availability->isConnectable($engine)) {
                continue;
            }

            $this->availability->connection($engine)->configure();
        }
    }
}
