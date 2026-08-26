<?php

namespace Webkul\Product\Services\Search;

use Webkul\Product\Enums\SearchEngineEnum;

class SearchEngineOptions
{
    /**
     * Value standing for "inherit the default engine".
     */
    const INHERIT = '';

    /**
     * The engines a store may choose as its default.
     */
    public function getEngineOptions(): array
    {
        return array_map(
            fn (SearchEngineEnum $engine) => $this->option($engine),
            SearchEngineEnum::cases(),
        );
    }

    /**
     * The engines a context may choose, plus the option to inherit the default.
     */
    public function getContextOptions(): array
    {
        return array_merge([[
            'title' => 'admin::app.configuration.index.search-engines.general.settings.inherit',
            'value' => self::INHERIT,
        ]], $this->getEngineOptions());
    }

    /**
     * Build one option.
     */
    protected function option(SearchEngineEnum $engine): array
    {
        return [
            'title' => "admin::app.configuration.index.search-engines.engines.{$engine->value}",
            'value' => $engine->value,
        ];
    }
}
