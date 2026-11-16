<?php

namespace Webkul\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Helpers\Exchange\ExchangeRate;

class ExchangeRateUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange-rate:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically updates currency exchange rates';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            Event::dispatch('core.exchange_rate.update_rates.before');

            ExchangeRate::resolve()->updateRates();

            Event::dispatch('core.exchange_rate.update_rates.after');

            $this->info('Exchange rates updated successfully.');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to update exchange rates: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
