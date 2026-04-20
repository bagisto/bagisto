<?php

namespace Webkul\Omnibus\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Core\Repositories\ChannelRepository;
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
    protected $description = 'Capture Omnibus price snapshots for every active product across configured channels and currencies.';

    /**
     * Create a new command instance.
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ChannelRepository $channelRepository,
        protected OmnibusPriceManager $omnibusPriceManager
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $hasEnabledChannel = $this->channelRepository->all()->contains(
            fn ($channel) => core()->getConfigData('catalog.products.omnibus.is_enabled', $channel->code)
        );

        if (! $hasEnabledChannel) {
            $this->components->warn(trans('omnibus::app.console.disabled-all-channels'));

            return;
        }

        $query = $this->productRepository->getModel()
            ->whereHas('attribute_values', function ($query) {
                $query->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
                    ->where('attributes.code', 'status')
                    ->where('product_attribute_values.boolean_value', true);
            });

        $snapshotCount = 0;

        $this->output->progressStart($query->count());

        $query->chunk(500, function ($products) use (&$snapshotCount) {
            $snapshotCount += $this->omnibusPriceManager->recordBulkPrice(
                $products,
                null,
                fn () => $this->output->progressAdvance()
            );
        });

        $this->output->progressFinish();

        $this->components->success(trans('omnibus::app.console.snapshots-captured', ['count' => $snapshotCount]));
    }
}
