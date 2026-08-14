<?php

namespace Webkul\Product\Helpers\Indexers;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webkul\Product\Contracts\Product;
use Webkul\Product\Helpers\ProductType;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Repositories\ProductRepository;

class Flat extends AbstractIndexer
{
    /**
     * Batch size.
     *
     * @var int
     */
    private $batchSize;

    /**
     * Attribute codes that can be fill during flat creation.
     *
     * @var string[]
     */
    protected $fillableAttributeCodes = [
        'sku',
        'name',
        'price',
        'weight',
        'status',
    ];

    /**
     * Flat columns.
     *
     * @var array
     */
    protected $flatColumns = [];

    /**
     * Channels.
     *
     * @var mixed
     */
    protected $channels;

    /**
     * Family attributes.
     *
     * @var array
     */
    protected $familyAttributes = [];

    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductFlatRepository $productFlatRepository
    ) {
        $this->batchSize = self::BATCH_SIZE;

        $this->flatColumns = Schema::getColumnListing('product_flat');
    }

    /**
     * Reindex all products.
     *
     * @return void
     */
    public function reindexFull()
    {
        while (true) {
            $paginator = $this->productRepository
                ->with([
                    'channels',
                    'variants',
                    'variants.channels',
                    'attribute_family',
                    'attribute_values',
                    'variants.attribute_family',
                    'variants.attribute_values',
                ])
                ->cursorPaginate($this->batchSize);

            $this->reindexBatch($paginator->items());

            if (! $cursor = $paginator->nextCursor()) {
                break;
            }

            request()->query->add(['cursor' => $cursor->encode()]);
        }

        request()->query->remove('cursor');
    }

    /**
     * Reindex products by batch size.
     *
     * @return void
     */
    public function reindexBatch($products)
    {
        $productIds = [];

        foreach ($products as $product) {
            $productIds = array_merge($productIds, $this->writeFlatRows($product));
        }

        /**
         * Derived once for the whole batch rather than once per product: every statement a
         * long-running command issues costs memory it never gets back.
         */
        $this->refreshDerivedColumns($productIds);
    }

    /**
     * Refresh product flat indices.
     *
     * @param  Product  $product
     * @return void
     */
    public function refresh($product)
    {
        $this->refreshDerivedColumns($this->writeFlatRows($product));
    }

    /**
     * Creates product flat.
     *
     * @param  Product  $product
     * @return void
     */
    public function updateOrCreate($product)
    {
        $familyAttributes = $this->getCachedFamilyAttributes($product);

        $channelIds = $product->channels->pluck('id')->toArray();

        if (empty($channelIds)) {
            $channelIds[] = core()->getDefaultChannel()->id;
        }

        $attributeValues = $product->attribute_values;

        foreach ($this->getChannels() as $channel) {
            if (in_array($channel->id, $channelIds)) {
                foreach ($channel->locales as $locale) {
                    $productFlat = $this->productFlatRepository->updateOrCreate([
                        'product_id' => $product->id,
                        'channel' => $channel->code,
                        'locale' => $locale->code,
                    ], [
                        'type' => $product->type,
                        'sku' => $product->sku,
                        'attribute_family_id' => $product->attribute_family_id,
                    ]);

                    foreach ($familyAttributes as $attribute) {
                        if (
                            ! in_array($attribute->code, $this->flatColumns)
                            || $attribute->code == 'sku'
                        ) {
                            continue;
                        }

                        $productAttributeValues = $attributeValues->where('attribute_id', $attribute->id);

                        if ($attribute->value_per_channel) {
                            if ($attribute->value_per_locale) {
                                $productAttributeValues = $productAttributeValues
                                    ->where('channel', $channel->code)
                                    ->where('locale', $locale->code);
                            } else {
                                $productAttributeValues = $productAttributeValues->where('channel', $channel->code);
                            }
                        } else {
                            if ($attribute->value_per_locale) {
                                $productAttributeValues = $productAttributeValues->where('locale', $locale->code);
                            }
                        }

                        $productAttributeValue = $productAttributeValues->first();

                        /**
                         * Same fallback as `Product::getCustomAttributeValue()`, so an attribute a
                         * product never saved reads the same off the flat table as off the model.
                         */
                        $productFlat->{$attribute->code} = $productAttributeValue[$attribute->column_name] ?? $attribute->default_value;
                    }

                    $productFlat->save();
                }
            } else {
                if (request()->route()?->getName() == 'admin.catalog.products.update') {
                    $this->productFlatRepository->deleteWhere([
                        'product_id' => $product->id,
                        'channel' => $channel->code,
                    ]);
                }
            }
        }
    }

    /**
     * Refresh the flat columns derived from other tables rather than from an attribute.
     *
     * @param  array|Closure|null  $productIds  Every product when null, none when an empty array,
     *                                          so a caller that found nothing cannot rewrite the
     *                                          table. A closure scopes a large set without listing
     *                                          its ids.
     */
    public function refreshDerivedColumns(array|Closure|null $productIds = null): void
    {
        if (
            is_array($productIds)
            && empty($productIds)
        ) {
            return;
        }

        $tablePrefix = DB::getTablePrefix();

        $query = DB::table('product_flat');

        if (! is_null($productIds)) {
            $query->whereIn('product_id', $productIds);
        }

        $query->update([
            'quantity' => DB::raw(
                '(SELECT SUM(qty) FROM '.$tablePrefix.'product_inventories'
                .' WHERE product_id = '.$tablePrefix.'product_flat.product_id)'
            ),

            'images_count' => DB::raw(
                '(SELECT COUNT(*) FROM '.$tablePrefix.'product_images'
                .' WHERE product_id = '.$tablePrefix.'product_flat.product_id)'
            ),

            'base_image' => DB::raw(
                '(SELECT path FROM '.$tablePrefix.'product_images'
                .' WHERE product_id = '.$tablePrefix.'product_flat.product_id'
                .' ORDER BY position, id LIMIT 1)'
            ),

            'attribute_family_name' => DB::raw(
                '(SELECT name FROM '.$tablePrefix.'attribute_families'
                .' WHERE id = '.$tablePrefix.'product_flat.attribute_family_id)'
            ),

            'category_name' => DB::raw(
                '(SELECT GROUP_CONCAT(ct.name ORDER BY ct.category_id SEPARATOR \', \')'
                .' FROM '.$tablePrefix.'product_categories pc'
                .' INNER JOIN '.$tablePrefix.'category_translations ct'
                .' ON ct.category_id = pc.category_id AND ct.locale = '.$tablePrefix.'product_flat.locale'
                .' WHERE pc.product_id = '.$tablePrefix.'product_flat.product_id)'
            ),
        ]);
    }

    /**
     * Get cached family attributes for a product, so we don't have to query the same family multiple times in a reindex.
     *
     * @param  Product  $product
     * @return mixed
     */
    public function getCachedFamilyAttributes($product)
    {
        if (array_key_exists($product->attribute_family_id, $this->familyAttributes)) {
            return $this->familyAttributes[$product->attribute_family_id];
        }

        return $this->familyAttributes[$product->attribute_family_id] = $product->attribute_family->custom_attributes;
    }

    /**
     * Returns all channels, with their locales, resolved once for the run.
     *
     * `core()->getAllChannels()` queries afresh on every call and hands back new models each
     * time, which a reindex would otherwise pay for once per product.
     *
     * @return mixed
     */
    public function getChannels()
    {
        if ($this->channels) {
            return $this->channels;
        }

        $this->channels = core()->getAllChannels();

        $this->channels->each->locales;

        return $this->channels;
    }

    /**
     * Write the flat rows for a product and its variants, and return every id written.
     *
     * @param  Product  $product
     * @return array
     */
    protected function writeFlatRows($product)
    {
        $this->updateOrCreate($product);

        $productIds = [$product->id];

        if (ProductType::hasVariants($product->type)) {
            foreach ($product->variants as $variant) {
                $this->updateOrCreate($variant);

                $productIds[] = $variant->id;
            }
        }

        return $productIds;
    }
}
