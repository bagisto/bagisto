<?php

declare(strict_types=1);

namespace Frosko\FairySync\Jobs;

use Frosko\FairySync\Models\Sync\Product;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Type\Configurable;

class SyncProduct implements ShouldQueue
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

        // Check if the product already exists and skip it.
        if ($productRepository->findOneWhere(['sku' => $this->sku, 'type' => 'configurable'])) {
            Log::warning('Product already exists', ['sku' => $this->sku]);

            // return;
        }

        // Base product data from the store with limit info.
        $pData = Product::query()
            ->with('productAttributes')
            ->where('sku', 'like', $this->sku.'_%')
            ->get();

        $z1Data = $this->getZ1Data($this->sku);

        /** @var \Webkul\Product\Contracts\Product $configProduct */
        if (! ($configProduct = $this->getConfigProduct($productRepository))) {
            $this->createConfigurableProduct(
                $productRepository,
                $attributeRepository,
                $attributeOptionRepository,
                $pData
            );

            // Let's refresh it here
            $configProduct = $this->getConfigProduct($productRepository);
        }

        $oldProd = $pData->first();
        $oldProd->pa = $oldProd->productAttributes->pluck('value', 'key')->toArray();

        if ($z1Data['parent']['categories']) {
            foreach ($z1Data['parent']['categories'] as $category) {
                $categoryIds = array_merge(
                    $categoryIds ?? [],
                    $this->deepCategory($category, 1, '>') ?? []
                );
            }
        } else {
            $categoryIds = $this->deepCategory($oldProd->pa['CategoryFullName'] ?? '');
        }

        $brandId = $this->preCreateAttributeOption(
            attrCode: 'brand',
            attrName: $oldProd->pa['Manufacturer'] ?? '',
            attrRepo: $attributeRepository,
            attrOptRepo: $attributeOptionRepository,
        );
        $originId = $this->preCreateAttributeOption(
            attrCode: 'origin',
            attrName: $oldProd->pa['Origin'] ?? '',
            attrRepo: $attributeRepository,
            attrOptRepo: $attributeOptionRepository,
        );

        $variants = [];
        /** @var \Webkul\Product\Models\Product $variant */
        foreach ($configProduct->variants as $variant) {
            $varOldSku = $this->detectOldSkuByVariantSku($variant->sku, $attributeOptionRepository, $pData) ?? $variant->sku;

            $varOld = $pData->where('sku', $varOldSku)->first()->productAttributes->pluck('value', 'key')->toArray();

            $variants[$variant->id] = [
                'sku'               => $varOldSku,
                'url_key'           => Str::slug($varOld['Title'] ?? ''),
                'name'              => $varOld['Title'] ?? '',
                'price'             => $varOld['Price'] ?? 0,
                'cost'              => $varOld['PurchPrice'] ?? 0,
                'status'            => 1,
                'weight'            => $varOld['Weight'] ?? 0,
                'tax_category_id'   => 1,
                'brand'             => $brandId,
                'origin'            => $originId,
                'material'          => $varOld['Material'] ?? '',
                'barcode'           => $varOld['Barcode'] ?? '',
            ];
        }

        /** @var Configurable $instance */
        $configurable = $configProduct->getTypeInstance();

        // Update the product with the full data.
        Event::dispatch('catalog.product.update.before', $configProduct->id);
        $name = Str::of($oldProd->pa['Title'] ?? '')->before('(')->trim()->toString();

        $images = array_reduce(
            Arr::pluck($z1Data['products'], 'images') ?? [],
            'array_merge',
            []
        );

        $images = array_unique($images) ?? [];

        $product = $configurable->update($updateData = [
            'name'              => $name,
            'meta_title'        => $name,
            'brand'             => $brandId,

            'barcode'           => $oldProd->pa['Barcode'] ?? '',
            'origin'            => $originId,
            'material'          => $oldProd->pa['Material'] ?? '',
            'originalcategory'  => $oldProd->pa['CategoryFullName'] ?? '',
            'meta_description'  => $oldProd->pa['Description'] ?? '',
            'description'       => $oldProd->pa['Description'] ?? '',
            'short_description' => $oldProd->pa['Description'] ?? '',
            'price'             => $oldProd->pa['Price'] ?? '',
            'weight'            => $oldProd->pa['Weight'] ?? '',
            'status'            => 0,
            'url_key'           => Str::slug($name),
            'tax_category_id'   => 1,
            'channel'           => 'default',
            'locale'            => 'bg',
            'variants'          => $variants,
            'categories'        => $categoryIds,
            'images'            => empty($images) ? [] : [
                'files' => array_map(
                    fn ($image) => $this->filePathToUploadFile(env('Z1_IMG_PATH').$image),
                    $images
                ),
            ],
        ], $configProduct->id);
        Event::dispatch('catalog.product.update.after', $product);

        // Update the product with the full data.
        Event::dispatch('catalog.product.update.before', $configProduct->id);
        $updateData['locale'] = 'en';
        $product = $configurable->update($updateData, $configProduct->id);
        Event::dispatch('catalog.product.update.after', $product);

        $configProduct = $this->getConfigProduct($productRepository);
        Event::dispatch('catalog.product.update.before', $configProduct->id);

        /** @var \Webkul\Product\Models\Product $variant */
        foreach ($configProduct->variants as $variant) {
            foreach (['bg', 'en'] as $locale) {
                $pVar = $pData->where('sku', $variant->sku)->first()->productAttributes->pluck('value', 'key')->toArray();
                $description = $pVar['Description'] ?? '';

                $images = [];

                if ($z1Data) {
                    $z1 = Arr::first($z1Data['products'], fn ($z1) => $z1['sku'] === Str::beforeLast($variant->sku, '_'));
                    $description = $z1[$locale]['description'] ?? $description;
                    $images = $z1['images'];
                }

                $variant->getTypeInstance()->update([
                    'description'       => $description,
                    'short_description' => $description,
                    'cost'              => $pVar['PurchPrice'] ?? 0,
                    'channel'           => 'default',
                    'locale'            => $locale,
                    'barcode'           => $pVar['Barcode'] ?? '',
                    'brand'             => $brandId,
                    'origin'            => $originId,
                    'originalcategory'  => $pVar['CategoryFullName'] ?? '',
                    'material'          => $pVar['Material'] ?? '',
                    'weight'            => $pVar['Weight'] ?? 0,
                    'status'            => 1,
                    'manage_stock'      => 1,
                    'categories'        => $categoryIds,
                    'images'            => [
                        'files' => array_map(
                            fn ($image) => $this->filePathToUploadFile(env('Z1_IMG_PATH').$image),
                            $images
                        ),
                    ],
                ], $variant->id);

            }
        }
        Event::dispatch('catalog.product.update.after', $product);

        SyncStocks::dispatch($this->sku);
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
    ): ?string {
        if (Str::substrCount($variantSku, '-') < 3) {
            return null;
        }

        [$parentSku, $varPrefix, $colorId, $sizeId] = explode('-', $variantSku);

        if (! $sizeId || ! $colorId) {
            return null;
        }

        $color = Str::lower($attributeOptionRepository->find($colorId)->admin_name);
        $size = Str::lower($attributeOptionRepository->find($sizeId)->admin_name);

        return $syncData
            ->filter(fn ($product) => Str::startsWith($product->sku, $parentSku.'_') &&
                $product->productAttributes
                    ->where('key', 'Color')
                    ->filter(fn ($item) => Str::lower($item->value) === $color)
                    ->isNotEmpty() &&
                $product->productAttributes
                    ->where('key', 'Size')
                    ->filter(fn ($item) => Str::lower($item->value) === $size)
                    ->isNotEmpty()
            )->first()?->sku;
    }

    private function getConfigProduct(ProductRepository $productRepository)
    {
        /** @var \Webkul\Product\Models\Product $configProduct */
        return $productRepository->findOneWhere([
            ['sku', 'like', $this->sku.'%'],
            ['type', '=', 'configurable'],
        ]);
    }

    private function filePathToUploadFile(
        string $filepath
    ): ?UploadedFile {
        if (! file_exists($filepath)) {
            dump($filepath);

            return null;
        }

        return UploadedFile::createFromBase(
            new \Symfony\Component\HttpFoundation\File\UploadedFile(
                $filepath,
                basename($filepath)
            )
        );
    }

    /** Pre create attribute option  */
    private function preCreateAttributeOption(
        string $attrCode,
        string $attrName,
        AttributeRepository $attrRepo,
        AttributeOptionRepository $attrOptRepo,
    ): ?int {
        if (empty($attrName) || empty($attrCode)) {
            return null;
        }

        /** @var Attribute $attribute */
        $attribute = $attrRepo->findOneByField('code', $attrCode);

        return ($attrOptRepo->findOneWhere([
            'admin_name'    => Str::lower($attrName),
            'attribute_id'  => $attribute->id,
        ]) ?? $attrOptRepo->create([
            'sku'           => Str::slug($attrName),
            'admin_name'    => Str::lower($attrName),
            'attribute_id'  => $attribute->id,
            'swatch_value'  => '',
            'bg'            => [
                'label' => Str::of($attrName)->lower()->ucfirst()->toString(),
            ],
            'en'            => [
                'label' => Str::of($attrName)->lower()->ucfirst()->toString(),
            ],
        ]))->id;
    }

    private function deepCategory($categoryName, $parentId = 1, string $separator = '|'): array
    {
        $fn = fn ($categoryName, $pId) => DB::table('category_translations', 'ct')
            ->whereRaw('category_id IN (SELECT c.id FROM categories c WHERE c.parent_id = ?)', [$pId])
            ->where('ct.locale', 'bg')
            ->get()
            ->filter(fn ($item) => Str::lower($categoryName) === Str::lower($item->name))
            ->first()?->category_id;

        $categories = [];
        $explode = explode($separator, $categoryName);
        foreach ($explode as $categoryName) {
            $parentId = $fn(trim($categoryName), $parentId);

            if ($parentId) {
                $categories[] = $parentId;
            } else {
                break;
            }
        }

        return $categories;
    }

    protected function getZ1Data($sku): ?array
    {
        $allProducts = \Frosko\FairySync\Models\Z1\Product::query()
            ->with(['images', 'parentProduct', 'parentProduct.categories'])
            ->whereHas('parentProduct', fn ($query) => $query->where('sku', $sku))
            ->get();

        if ($allProducts->isEmpty()) {
            return null;
        }

        $products = [];

        foreach ($allProducts as $key => $product) {
            /** @var \Frosko\FairySync\Models\Z1\Product $product */
            $parent = $product->parentProduct->toArray();
            $images = $product->images->sortBy('sort_order')->pluck('path')->toArray();

            $data = [
                'sku'       => $product['sku'],
                'images'    => $images,
            ];

            foreach (['en', 'bg'] as $lang) {
                $data[$lang] = [
                    'name'             => $product['name']['bg'] ?? $parent['name']['bg'] ?? null,
                    'description'      => $product['description']['bg'] ?? $parent['description']['bg'] ?? null,
                    'meta_title'       => $product['meta_title']['bg'] ?? $parent['meta_title']['bg'] ?? null,
                    'meta_description' => $product['meta_description']['bg'] ?? $parent['meta_description']['bg'] ?? null,
                    'url_key'          => $product['seo_url']['bg'] ?? null,
                ];
            }

            $products[$key] = $data;
        }

        $parent = $allProducts->first()->parentProduct->toArray();

        return [
            'products'   => $products,
            'parent'     => [
                'categories' => Arr::pluck($parent['categories'], 'name'),
                'sku'        => $parent['sku'],
                'bg'         => [
                    'name'             => $parent['name']['bg'] ?? null,
                    'description'      => $parent['description']['bg'] ?? null,
                    'meta_title'       => $parent['meta_title']['bg'] ?? null,
                    'meta_description' => $parent['meta_description']['bg'] ?? null,
                ],
                'en' => [
                    'name'             => $parent['name']['en'] ?? null,
                    'description'      => $parent['description']['en'] ?? null,
                    'meta_title'       => $parent['meta_title']['en'] ?? null,
                    'meta_description' => $parent['meta_description']['en'] ?? null,
                ],
            ],
        ];
    }
}
