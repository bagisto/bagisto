<?php

namespace Webkul\CatalogRule\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;
use Webkul\Product\Helpers\Indexers\Price as PriceIndexer;
use Webkul\Product\Repositories\ProductRepository;

class DeleteCatalogRuleIndex implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of products reindexed per batch.
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
     * Reindex the prices of the products a removed rule applied to; rules an `end_other_rules` rule held
     * back are not reapplied yet.
     *
     * @return void
     */
    public function handle()
    {
        Event::dispatch('promotions.catalog_rule.reindex.before', [$this->productIds]);

        while (true) {
            $paginator = app(ProductRepository::class)
                ->whereIn('id', $this->productIds)
                ->cursorPaginate(self::BATCH_SIZE);

            app(PriceIndexer::class)->reindexBatch($paginator->items());

            if (! $cursor = $paginator->nextCursor()) {
                break;
            }

            request()->query->add(['cursor' => $cursor->encode()]);
        }

        Event::dispatch('promotions.catalog_rule.reindex.after', [$this->productIds]);
    }
}
