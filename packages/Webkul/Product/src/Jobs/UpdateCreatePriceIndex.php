<?php

namespace Webkul\Product\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Webkul\Core\Jobs\Concerns\BoundedByRetryAfter;
use Webkul\Core\Jobs\Concerns\RetriesOnDeadlock;
use Webkul\Product\Helpers\Indexers\Price as PriceIndexer;
use Webkul\Product\Repositories\ProductRepository;

class UpdateCreatePriceIndex implements ShouldQueue
{
    use BoundedByRetryAfter, Dispatchable, InteractsWithQueue, Queueable, RetriesOnDeadlock, SerializesModels;

    /**
     * Bounded by the connection's `retry_after`, so the queue can never hand this job to a
     * second worker while this one still holds it and have both write the same index rows.
     */
    public int $timeout;

    /**
     * Create a new job instance.
     *
     * @param  array  $productIds
     * @return void
     */
    public function __construct(protected $productIds)
    {
        $this->productIds = $productIds;

        $this->timeout = static::timeoutWithinRetryAfter();
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

        $this->retryOnDeadlock(fn () => app(PriceIndexer::class)->reindexRows($products));
    }
}
