<?php

namespace Webkul\Product\Helpers\Indexers;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webkul\Core\Contracts\Channel;
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
     * Create a new indexer instance.
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
        $this->productRepository
            ->with([
                'channels',
                'variants',
                'variants.channels',
                'attribute_family',
                'attribute_values',
                'variants.attribute_family',
                'variants.attribute_values',
            ])
            ->chunkById($this->batchSize, function ($products) {
                $this->reindexBatch($products->all());
            });
    }

    /**
     * Reindex products by batch size, refreshing the derived columns once for the whole batch.
     *
     * @return void
     */
    public function reindexBatch($products)
    {
        $productIds = [];

        foreach ($products as $product) {
            $productIds = array_merge($productIds, $this->writeFlatRows($product));
        }

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
     * Write the admin locale's flat rows for products and their variants, for callers such as the
     * product import that write the other locales' rows themselves.
     */
    public function refreshAdminLocale(array $products): void
    {
        $productIds = [];

        foreach ($products as $product) {
            foreach ($this->getProductWithVariants($product) as $item) {
                $this->writeAdminLocaleRows($item);

                $productIds[] = $item->id;
            }
        }

        $this->refreshDerivedColumns($productIds);
    }

    /**
     * Creates product flat.
     *
     * @param  Product  $product
     * @return void
     */
    public function updateOrCreate($product)
    {
        $channelIds = $this->getChannelIds($product);

        foreach ($this->getChannels() as $channel) {
            if (in_array($channel->id, $channelIds)) {
                foreach ($this->getLocaleCodes($channel) as $localeCode) {
                    $this->writeFlatRow($product, $channel, $localeCode);
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
     * @param  array|Closure|null  $productIds  Every product when null and none when empty, so a caller that found
     *                                          nothing cannot rewrite the table; a closure scopes a large set.
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
     * Returns all channels with their locales, resolved once for the run rather than once per product.
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
        $productIds = [];

        foreach ($this->getProductWithVariants($product) as $item) {
            $this->updateOrCreate($item);

            $productIds[] = $item->id;
        }

        return $productIds;
    }

    /**
     * Write a product's admin locale flat row on each of its channels.
     *
     * @param  Product  $product
     */
    protected function writeAdminLocaleRows($product): void
    {
        foreach ($this->getChannels()->whereIn('id', $this->getChannelIds($product)) as $channel) {
            $this->writeFlatRow($product, $channel, app()->getLocale());
        }
    }

    /**
     * Write a product's flat row for one channel and locale, with its values read as the product
     * model reads them on that channel.
     *
     * @param  Product  $product
     * @param  Channel  $channel
     */
    protected function writeFlatRow($product, $channel, string $localeCode): void
    {
        $productFlat = $this->productFlatRepository->updateOrCreate([
            'product_id' => $product->id,
            'channel' => $channel->code,
            'locale' => $localeCode,
        ], [
            'type' => $product->type,
            'sku' => $product->sku,
            'attribute_family_id' => $product->attribute_family_id,
        ]);

        $valueLocaleCode = $channel->resolveLocaleCode($localeCode);

        foreach ($this->getCachedFamilyAttributes($product) as $attribute) {
            if (
                ! in_array($attribute->code, $this->flatColumns)
                || $attribute->code == 'sku'
            ) {
                continue;
            }

            $productFlat->{$attribute->code} = $product->getCustomAttributeValueFor($attribute, $channel->code, $valueLocaleCode);
        }

        $productFlat->save();
    }

    /**
     * The product followed by its variants, when its type has variants.
     *
     * @param  Product  $product
     */
    protected function getProductWithVariants($product): array
    {
        if (! ProductType::hasVariants($product->type)) {
            return [$product];
        }

        return [$product, ...$product->variants];
    }

    /**
     * Ids of the channels a product's flat rows are written on, the default channel when it has none.
     *
     * @param  Product  $product
     */
    protected function getChannelIds($product): array
    {
        $channelIds = $product->channels->pluck('id')->toArray();

        if (empty($channelIds)) {
            $channelIds[] = core()->getDefaultChannel()->id;
        }

        return $channelIds;
    }

    /**
     * Locale codes a channel's flat rows are written in: its own, and the admin locale the admin
     * grids read, which the channel may not have.
     *
     * @param  Channel  $channel
     */
    protected function getLocaleCodes($channel): array
    {
        return $channel->locales
            ->pluck('code')
            ->push(app()->getLocale())
            ->unique()
            ->values()
            ->all();
    }
}
