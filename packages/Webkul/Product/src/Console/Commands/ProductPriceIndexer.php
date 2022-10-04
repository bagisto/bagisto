<?php

namespace Webkul\Product\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Jobs\ProductPriceIndexer as ProductPriceIndexerJob;

class ProductPriceIndexer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:price-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically updates product price indexes (eg. min_price and max_price for customer_group_id)';

    /**
     * Create a new command instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @return void
     */
    public function __construct(protected ProductRepository $productRepository)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $batch = Bus::batch([])->dispatch();

        while (true) {
            $paginator = $this->productRepository->cursorPaginate(10);

            $batch->add(new ProductPriceIndexerJob($paginator->items()));

            if (! $cursor = $paginator->nextCursor()) {
                break;
            }

            request()->query->add(['cursor' => $cursor->encode()]);
        }
    }
}