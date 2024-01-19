<?php

namespace Webkul\Core\Console\Commands;

use Illuminate\Console\Command;

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
    protected $description = 'Automatically updates currency exchange rates ';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        try {
            app(config('services.exchange_api.'.config('services.exchange_api.default').'.class'))->updateRates();
        } catch (\Exception $e) {

        }
    }
}
