<?php

namespace Webkul\Product\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Webkul\Core\Jobs\Concerns\RetriesOnDeadlock;
use Webkul\Product\Helpers\Indexers\Inventory as InventoryIndexer;
use Webkul\Product\Repositories\ProductRepository;

class UpdateCreateInventoryIndex implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, RetriesOnDeadlock, SerializesModels;

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

        $this->retryOnDeadlock(fn () => app(InventoryIndexer::class)->reindexRows($products));
    }
}
