<?php

declare(strict_types=1);

namespace Frosko\FairySync\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Controllers\Catalog\ProductController;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Contracts\Product;

class SyncProductsFromOC implements ShouldQueue
{
    protected int $localeID = 1;

    protected int $channelID = 1;

    protected array $colours = [];

    protected array $sizes = [];

    protected $parentSku = 'a123456';

    private ?ProductRepository $productRepository = null;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(
        ProductRepository $productRepository
    ) {
        $this->productRepository = $productRepository;

        app('request')->merge([
            'type',
            'attribute_family_id',
            'sku',
            'super_attributes',
            'family',
        ]);

        dd(
            app(ProductController::class)->store()
        );

    }

    private function createProduct($sku, array $colors, array $sizes): Product
    {
        Event::dispatch('catalog.product.create.before');

        $product = $this->productRepository->create([
            'type'                => 'configurable',
            'attribute_family_id' => 1,
            'sku'                 => $sku,
            'super_attributes'    => [
                'colors' => $colors,
                'sizes'  => $sizes,
            ],
            'family' => 'default',
        ]);

        Event::dispatch('catalog.product.create.after', $product);

        return $product;
    }

    public function updateProduct($id): Product
    {
        Event::dispatch('catalog.product.update.before', $id);

        $product = $this->productRepository->update(request()->all(), $id);

        Event::dispatch('catalog.product.update.after', $product);

        return $product;
    }

    public function getConfigProductData(): array
    {
        return [
            'sku'    => $this->parentSku,
            'colors' => [], // ids of colors
            'sizes'  => [], // ids of sizes
        ];
    }
}
