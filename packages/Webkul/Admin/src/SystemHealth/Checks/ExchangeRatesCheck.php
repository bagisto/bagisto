<?php

namespace Webkul\Admin\SystemHealth\Checks;

use Webkul\Admin\SystemHealth\Heartbeat;
use Webkul\Core\Repositories\CurrencyRepository;

class ExchangeRatesCheck extends AbstractCheck
{
    /**
     * The heartbeat a successful rate update keeps.
     */
    public const HEARTBEAT = 'exchange-rates';

    /**
     * Seconds the rates may go without an update, by schedule: a day more than the schedule's own gap.
     */
    public const SILENCE_LIMITS = [
        'daily' => 172800,
        'weekly' => 691200,
        'monthly' => 2764800,
    ];

    /**
     * Create a new check instance.
     */
    public function __construct(
        protected Heartbeat $heartbeat,
        protected CurrencyRepository $currencyRepository
    ) {}

    /**
     * The translation key segment naming what this check looks after.
     */
    public function name(): string
    {
        return 'exchange-rates';
    }

    /**
     * Whether scheduled updates are expected and none has succeeded in time.
     */
    public function failing(): bool
    {
        if (! $this->applies()) {
            return false;
        }

        $frequency = core()->getConfigData('general.exchange_rates.schedule.frequency') ?: 'daily';

        return $this->heartbeat->silence(self::HEARTBEAT) > (self::SILENCE_LIMITS[$frequency] ?? self::SILENCE_LIMITS['daily']);
    }

    /**
     * Whether it is expected to run but has not been heard from even once.
     */
    public function unconfirmed(): bool
    {
        return $this->applies()
            && ! $this->heartbeat->heard(self::HEARTBEAT);
    }

    /**
     * Whether scheduled updates are on for a store with more than one currency.
     */
    public function applies(): bool
    {
        return (bool) core()->getConfigData('general.exchange_rates.schedule.enabled')
            && $this->currencyRepository->count() > 1;
    }
}
