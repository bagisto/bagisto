<?php

namespace Webkul\Product\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Webkul\Product\Helpers\Indexers\Price as PriceIndexer;
use Webkul\Product\Repositories\ProductRepository;

class UpdateCreatePriceIndex implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param  array  $productIds
     * @return void
     */
    public function __construct(protected $productIds)
    {
        $this->productIds = $productIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (! count($this->productIds)) {
            return;
        }

        $products = app(ProductRepository::class)
            ->whereIn('id', $this->productIds)
            ->orderByRaw(db_grammar()->orderByField('id', $this->productIds))
            ->get();

        app(PriceIndexer::class)->reindexRows($products);
    }
}
