<?php

namespace Webkul\Product\Jobs\Search;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Webkul\Core\Jobs\Concerns\BoundedByRetryAfter;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Services\Search\SearchEngineManager;

class IndexProducts implements ShouldQueue
{
    use BoundedByRetryAfter, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Bounded by the connection's `retry_after`, so a batch that is slow to reach the search
     * engine is not handed to a second worker while this one is still sending it.
     */
    public int $timeout;

    /**
     * Create a new job instance.
     */
    public function __construct(protected array $productIds)
    {
        $this->timeout = static::timeoutWithinRetryAfter();
    }

    /**
     * Execute the job.
     */
    public function handle(SearchEngineManager $manager, ProductRepository $productRepository): void
    {
        $products = $productRepository
            ->whereIn('id', $this->productIds)
            ->orderByRaw(db_grammar()->orderByField('id', $this->productIds))
            ->get();

        $manager->indexer()->indexBatch($products->all());
    }
}
