<?php

declare(strict_types=1);

namespace Frosko\FairySync\Jobs;

use Frosko\FairySync\Models\Sync\ProductAttribute;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Type\Configurable;

class SyncProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(
        ProductRepository $productRepository
    ) {
        // 1. Detect products that are not in the database.
        $remoteParentSkus = array_unique(
            ProductAttribute::query()
                ->where('key', 'ParentSku')
                ->toBase()
                ->get(['value'])->pluck('value')->toArray()
        );

        $parentSkus = array_unique(
            \Webkul\Product\Models\Product::query()
                ->whereNull('parent_id')
                ->where('type', 'configurable')
                ->toBase()
                ->get(['sku'])->pluck('sku')->toArray()
        );

        $missing = array_diff($remoteParentSkus, $parentSkus);

        if (count($missing) > 0) {
            Bus::batch(
                Arr::sort(
                    Arr::map($missing, fn ($parentSku) => new SyncProduct($parentSku))
                )
            )->allowFailures()->dispatch();
        }
    }
}
