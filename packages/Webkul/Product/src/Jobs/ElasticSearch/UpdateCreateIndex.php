<?php

namespace Webkul\Product\Jobs\ElasticSearch;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Webkul\Product\Helpers\Indexers\ElasticSearch;
use Webkul\Product\Repositories\ProductRepository;

class UpdateCreateIndex implements ShouldQueue
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
        if (core()->getConfigData('catalog.products.search.engine') != 'elastic') {
            return;
        }

        $ids = implode(',', $this->productIds);

        $products = app(ProductRepository::class)
            ->whereIn('id', $this->productIds)
            ->orderByRaw("FIELD(id, $ids)")
            ->get();

        app(ElasticSearch::class)->reindexRows($products);
    }
}
