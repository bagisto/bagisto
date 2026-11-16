<?php

namespace Webkul\Admin\Notifications\Types;

class ExchangeRatesStale extends SystemProblem
{
    /**
     * The exchange rate configuration screen.
     */
    public function url(array $data): ?string
    {
        return route('admin.configuration.index', ['slug' => 'general', 'slug2' => 'exchange_rates']);
    }

    /**
     * The translation key segment holding this problem's title and message.
     */
    protected function name(): string
    {
        return 'exchange-rates-stale';
    }
}
