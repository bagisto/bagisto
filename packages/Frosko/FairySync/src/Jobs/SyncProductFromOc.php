<?php

declare(strict_types=1);

namespace Frosko\FairySync\Jobs;

use Frosko\FairySync\Models\Sync\Product;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Type\Configurable;

class SyncProductFromOc implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly string $sku,
    ) {}

    public function handle(
        ProductRepository $productRepository,
        AttributeRepository $attributeRepository,
        AttributeOptionRepository $attributeOptionRepository,
    ) {
        /** @var \Webkul\Product\Contracts\Product $configProduct */
        $configProduct = $this->getConfigProduct($productRepository);

        if (! $configProduct) {
            Log::warning('Configurable product not found', ['sku' => $this->sku]);

            return;
        }

        $productCollection = $this->getOCProductData();

        $variants = [];
        /** @var Product $variant */
        foreach ($configProduct->variants as $key => $variant) {
            $variants = $variant->toArray();
        }


        $variants = [];
        /** @var \Webkul\Product\Models\Product $variant */
        foreach ($configProduct->variants as $variant) {
            $varOldSku = $this->detectOldSkuByVariantSku($variant->sku, $attributeOptionRepository, $pData);

            $varOld = $pData->where('sku', $varOldSku)->first()->productAttributes->pluck('value', 'key')->toArray();

            $variants[$variant->id] = [
                'sku'               => $varOldSku,
                'url_key'           => Str::slug($varOld['Title'] ?? ''),
                'name'              => $varOld['Title'] ?? '',
                'price'             => $varOld['Price'] ?? 0,
                'weight'            => $varOld['Weight'] ?? 0,
                'short_description' => $varOld['Description'] ?? '',
                'description'       => $varOld['Description'] ?? '',
            ];
        }

        // Update the product with the full data.
        Event::dispatch('catalog.product.update.before', $configProduct->id);

        $name = Str::of($oldProd->pa['Title'] ?? '')->before('(')->trim()->toString();
        $product = $configProduct->getTypeInstance()->update([
            'name'              => $name,
            'meta_title'        => $name,
            'brand'             => $oldProd->pa['Brand'] ?? '',
            'barcode'           => $oldProd->pa['Barcode'] ?? '',
            'origin'            => $oldProd->pa['Origin'] ?? '',
            'meta_description'  => $oldProd->pa['Description'] ?? '',
            'description'       => $oldProd->pa['Description'] ?? '',
            'short_description' => $oldProd->pa['Description'] ?? '',
            'price'             => $oldProd->pa['Price'] ?? '',
            'weight'            => $oldProd->pa['Weight'] ?? '',
            'status'            => 0,
            'url_key'           => Str::slug($name),
            'variants'          => $variants,
        ], $configProduct->id);
        Event::dispatch('catalog.product.update.after', $product);


        Event::dispatch('catalog.product.update.before', $configProduct->id);

        /** @var \Webkul\Product\Models\Product $variant */
        foreach ($configProduct->variants as $variant) {
            $pVar = $pData->where('sku', $variant->sku)->first();
            $description = $pVar->productAttributes->where('key', 'Description')->first()->value;

            $configurable->updateVariant([
                'sku'               => $variant->sku,
                'description'       => $description,
                'short_description' => $description,
                'channel'           => 'default',
                'locale'            => 'bg',
            ], $variant->id);

            $configurable->updateVariant([
                'sku'               => $variant->sku,
                'description'       => $description,
                'short_description' => $description,
                'channel'           => 'default',
                'locale'            => 'en',
            ], $variant->id);
        }
        Event::dispatch('catalog.product.update.after', $product);
    }

    private function getOCProductData(): \Illuminate\Support\Collection
    {
        return Product::query()
            ->with('productAttributes')
            ->where('sku', 'like', $this->sku.'%')
            ->get();
    }

    private function isObuvki(Collection $syncData): bool
    {
        // ToDo: Implement logic to determine if the product is a Drexa product.
        //  For now, we assume that all products are Drexa products.
        return $syncData->filter(
            fn (Product $product) => Str::contains(
                $product->productAttributes->where('key', 'Size')->first()->value,
                'номер'
            ) || Str::contains(
                $product->productAttributes->where('key', 'CategoryFullName')->first()->value,
                'обувки'
            )
        )->isNotEmpty();
    }

    private function getSubAttribute(
        string $attrName,
        Collection $syncData,
        AttributeRepository $attrRepo,
        AttributeOptionRepository $attrOptRepo,
    ): array {
        /** @var Attribute $attribute */
        $attribute = $attrRepo->findOneByField('code', $attrName);

        return $syncData->map(
            fn (Product $product) => $product->productAttributes->where('key', Str::ucfirst($attrName))->first()->value
        )->unique()->map(
            fn (string $attrName) => $attrOptRepo->findOneWhere([
                'admin_name'    => $attrName,
                'attribute_id'  => $attribute->id,
            ]) ?? $attrOptRepo->create([
                'sku'           => Str::slug($attrName),
                'admin_name'    => $attrName,
                'attribute_id'  => $attribute->id,
                'swatch_value'  => '',
                'bg'            => [
                    'label' => $attrName,
                ],
                'en'            => [
                    'label' => $attrName,
                ],
            ])
        )->pluck('id')->toArray();
    }

    private function createConfigurableProduct(
        ProductRepository $productRepository,
        AttributeRepository $attributeRepository,
        AttributeOptionRepository $attributeOptionRepository,
        Collection|array $pData
    ): \Webkul\Product\Contracts\Product {
        Log::debug('Creating missing products', ['sku' => $this->sku]);

        Event::dispatch('catalog.product.create.before');

        $prentConfigurableProduct = $productRepository->create([
            'type'                => 'configurable',
            'attribute_family_id' => $this->isObuvki($pData) ? 2 : 1,
            'family'              => 'default',
            'sku'                 => $this->sku,
            'super_attributes'    => [
                'color' => $this->getSubAttribute(
                    attrName: 'color',
                    syncData: $pData,
                    attrRepo: $attributeRepository,
                    attrOptRepo: $attributeOptionRepository,
                ),
                'size'  => $this->getSubAttribute(
                    attrName: 'size',
                    syncData: $pData,
                    attrRepo: $attributeRepository,
                    attrOptRepo: $attributeOptionRepository,
                ),
            ],
        ]);

        Event::dispatch('catalog.product.create.after', $prentConfigurableProduct);

        return $prentConfigurableProduct;
    }

    private function detectOldSkuByVariantSku(
        mixed $variantSku,
        AttributeOptionRepository $attributeOptionRepository,
        Collection $syncData,
    ): string {
        [$parentSku, $varPrefix, $colorId, $sizeId] = explode('-', $variantSku);

        $color = $attributeOptionRepository->find($colorId)->admin_name;
        $size = $attributeOptionRepository->find($sizeId)->admin_name;

        return $syncData
            ->filter(fn ($product) => Str::startsWith($product->sku, $parentSku.'_') &&
                $product->productAttributes->where('key', 'Color')->where('value', '==', $color)->isNotEmpty() &&
                $product->productAttributes->where('key', 'Size')->where('value', '==', $size)->isNotEmpty()
            )->first()?->sku;
    }

    private function getConfigProduct(ProductRepository $productRepository)
    {
        /** @var \Webkul\Product\Models\Product $configProduct */
        return $productRepository->findOneWhere([
            ['sku', 'like', $this->sku.'%'],
            ['type', 'configurable'],
        ]);
    }
}
