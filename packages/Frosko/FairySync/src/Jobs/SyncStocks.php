<?php

declare(strict_types=1);

namespace Frosko\FairySync\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Webkul\Product\Contracts\Product;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;

class SyncStocks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ProductInventoryRepository $pIR;

    public function handle(
        ProductInventoryRepository $productInventoryRepository,
        ProductRepository $productRepository,
        ?string $sku = null
    ) {
        $this->pIR = $productInventoryRepository;

        $this->getStocks($sku)
            ->chunk(300)
            ->each(function ($allStocks) use ($productRepository) {
                foreach ($allStocks as $sku => $stock) {
                    $product = $productRepository->findOneByField('sku', $sku);

                    if ($product) {
                        $this->updateProductInventory($product, $stock);
                        $this->updateStockSyncedFlag($sku);
                    } else {
                        unset($allStocks[$sku]);
                    }
                }
            });
    }


    private function updateProductInventory(Product $product, int $qty, int $sourceId = 1, int $vendorId = 0): void
    {
        Event::dispatch('catalog.product.update.before', $product->id);

        $this->pIR->saveInventories([
            'inventories' => [
                $sourceId => $qty,
            ],
            'vendor_id' => $vendorId,
        ], $product);

        Event::dispatch('catalog.product.update.after', $product);
    }

    private function getStocks(?string $sku = null): Collection
    {
        return DB::connection('sync')
            ->table('product_stocks')
            ->when($sku, fn ($query) => $query->where('sku', 'like', "$sku%"))
            ->get()
            ->pluck('stock', 'sku');
    }

    private function updateStockSyncedFlag(string $sku): void
    {
        DB::connection('sync')
            ->table('product_stocks')
            ->where('sku', $sku)
            ->update(['synced' => 1]);
    }
}
