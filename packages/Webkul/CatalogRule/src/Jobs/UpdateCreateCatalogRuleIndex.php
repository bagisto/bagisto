<?php

namespace Webkul\CatalogRule\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;
use Webkul\CatalogRule\Contracts\CatalogRule;
use Webkul\CatalogRule\Helpers\CatalogRuleIndex;
use Webkul\Product\Helpers\Indexers\Price as PriceIndexer;
use Webkul\Product\Repositories\ProductRepository;

class UpdateCreateCatalogRuleIndex implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of products reindexed per batch.
     */
    protected const BATCH_SIZE = 100;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected CatalogRule $catalogRule) {}

    /**
     * Reindex the rule and the prices of the products it applies to; for a disabled rule, rules an
     * `end_other_rules` rule held back are not reapplied yet.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->catalogRule->status) {
            app(CatalogRuleIndex::class)->reIndexRule($this->catalogRule);

            $this->catalogRule->unsetRelation('catalog_rule_products');

            $productIds = $this->catalogRule->catalog_rule_products->pluck('product_id')->unique();
        } else {
            $productIds = $this->catalogRule->catalog_rule_products->pluck('product_id')->unique();

            app(CatalogRuleIndex::class)->cleanProductIndices($productIds);
        }

        Event::dispatch('promotions.catalog_rule.reindex.before', [$productIds->values()->all()]);

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

        Event::dispatch('promotions.catalog_rule.reindex.after', [$productIds->values()->all()]);
    }
}
