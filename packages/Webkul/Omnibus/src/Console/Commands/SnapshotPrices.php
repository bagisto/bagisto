<?php

namespace Webkul\Omnibus\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Omnibus\Services\OmnibusPriceManager;
use Webkul\Product\Repositories\ProductRepository;

class SnapshotPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'omnibus:snapshot-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Snapshot of product regular and special prices for Omnibus compliance';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected OmnibusPriceManager $omnibusPriceManager
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (! core()->getConfigData('catalog.products.omnibus.is_enabled')) {
            $this->info('Omnibus is disabled in configuration.');

            return;
        }

        $this->info('Starting Omnibus Daily Snapshot...');

        $query = clone $this->productRepository->getModel()->query()
            ->whereHas('attribute_values', function ($q) {
                $q->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                    ->where('attributes.code', 'status')
                    ->where('product_attribute_values.boolean_value', 1);
            });
        $snapshotCount = 0;

        $this->output->progressStart($query->count());

        $query->chunk(500, function ($products) use (&$snapshotCount) {
            foreach ($products as $product) {
                $snapshotCount += $this->omnibusPriceManager->recordPriceIfNeeded($product);
                $this->output->progressAdvance();
            }
        });

        $this->output->progressFinish();
        $this->info("Completed. Added {$snapshotCount} new price snapshots.");

        $this->info('Cleaning up old history...');
        $this->omnibusPriceManager->cleanOldRecords();
    }
}
