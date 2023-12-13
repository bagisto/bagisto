<?php

namespace Webkul\CatalogRule\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
     * @param  \Webkul\CatalogRule\Contracts\CatalogRule  $catalogRule
     * @return void
     */
    public function __construct(protected $catalogRule)
    {
        $this->catalogRule = $catalogRule;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        app(CatalogRuleIndex::class)->reIndexRule($this->catalogRule);

        /**
         * Reindex price index for the products associated with the catalog rule.
         */
        $productIds = $this->catalogRule->catalog_rule_products->pluck('product_id')->unique();

        while (true) {
            $paginator = app(ProductRepository::class)
                ->whereIn('id', $productIds)
                ->cursorPaginate(self::BATCH_SIZE);

            app(PriceIndexer::class)->reindexBatch($paginator->items());

            if (! $cursor = $paginator->nextCursor()) {
                break;
            }

            request()->query->add(['cursor' => $cursor->encode()]);
        }
    }
}
