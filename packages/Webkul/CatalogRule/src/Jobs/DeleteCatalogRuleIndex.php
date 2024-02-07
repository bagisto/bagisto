<?php

namespace Webkul\CatalogRule\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Webkul\Product\Helpers\Indexers\Price as PriceIndexer;
use Webkul\Product\Repositories\ProductRepository;

class DeleteCatalogRuleIndex implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Default batch size
     */
    protected const BATCH_SIZE = 100;

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
        /**
         * Reindex price index for the products associated with the catalog rule.
         */
        while (true) {
            $paginator = app(ProductRepository::class)
                ->whereIn('id', $this->productIds)
                ->cursorPaginate(self::BATCH_SIZE);

            /**
             * TODO:
             *
             * If the 'end_other_rules' flag is set for this catalog rule,
             * it indicates that this rule might have preempted the
             * application of other rules on the products. In such a scenario,
             * it's necessary to reindex the remaining rules for these products.
             */
            app(PriceIndexer::class)->reindexBatch($paginator->items());

            if (! $cursor = $paginator->nextCursor()) {
                break;
            }

            request()->query->add(['cursor' => $cursor->encode()]);
        }
    }
}
