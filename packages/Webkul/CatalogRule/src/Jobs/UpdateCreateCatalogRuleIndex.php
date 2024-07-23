<?php

namespace Webkul\CatalogRule\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Webkul\CatalogRule\Contracts\CatalogRule;
use Webkul\CatalogRule\Helpers\CatalogRuleIndex;
use Webkul\Product\Helpers\Indexers\Price as PriceIndexer;
use Webkul\Product\Repositories\ProductRepository;

class UpdateCreateCatalogRuleIndex implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Default batch size
     */
    protected const BATCH_SIZE = 100;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected CatalogRule $catalogRule) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->catalogRule->status) {
            app(CatalogRuleIndex::class)->reIndexRule($this->catalogRule);

            /**
             * Reindex price index for the products associated with the catalog rule.
             */
            $productIds = $this->catalogRule->catalog_rule_products->pluck('product_id')->unique();
        } else {
            $productIds = $this->catalogRule->catalog_rule_products->pluck('product_id')->unique();

            app(CatalogRuleIndex::class)->cleanProductIndices($productIds);
        }

        while (true) {
            $paginator = app(ProductRepository::class)
                ->whereIn('id', $productIds)
                ->cursorPaginate(self::BATCH_SIZE);

            /**
             * TODO:
             *
             * If the catalog rule is disabled and 'end_other_rules' flag is set,
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
