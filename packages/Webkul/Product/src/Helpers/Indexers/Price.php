<?php

namespace Webkul\Product\Helpers\Indexers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Product\Repositories\ProductPriceIndexRepository;
use Webkul\Product\Repositories\ProductRepository;

class Price extends AbstractIndexer
{
    /**
     * The channels prices are indexed for.
     *
     * @var array
     */
    protected $channels;

    /**
     * The customer groups prices are indexed for.
     *
     * @var array
     */
    protected $customerGroups;

    /**
     * Number of products reindexed per batch.
     *
     * @var int
     */
    private $batchSize;

    /**
     * Create a new indexer instance.
     *
     * @return void
     */
    public function __construct(
        protected CustomerGroupRepository $customerGroupRepository,
        protected ProductRepository $productRepository,
        protected ProductPriceIndexRepository $productPriceIndexRepository
    ) {
        $this->batchSize = self::BATCH_SIZE;
    }

    /**
     * Reindex every product's price, announcing it without product ids since any price may have changed.
     *
     * @return void
     */
    public function reindexFull()
    {
        Event::dispatch('catalog.product.price.reindex.before');

        $this->productRepository
            ->with([
                'variants',
                'attribute_family',
                'attribute_values',
                'variants.attribute_family',
                'variants.attribute_values',
                'price_indices',
                'inventory_indices',
                'variants.price_indices',
                'variants.inventory_indices',
                'customer_group_prices',
                'variants.customer_group_prices',
                'catalog_rule_prices',
                'variants.catalog_rule_prices',
            ])
            ->chunkById($this->batchSize, function ($products) {
                $this->reindexBatch($products->all());
            });

        Event::dispatch('catalog.product.price.reindex.after');
    }

    /**
     * Reindex the products whose price depends on today's date and the composite products built from them,
     * announcing the ids of those reindexed.
     *
     * @return void
     */
    public function reindexSelective()
    {
        Event::dispatch('catalog.product.price.reindex.before');

        $productIds = [];

        $this->productRepository
            ->distinct()
            ->select('products.*')
            ->with([
                'variants',
                'attribute_values',
                'variants.attribute_values',
                'price_indices',
                'inventory_indices',
                'variants.price_indices',
                'variants.inventory_indices',
                'customer_group_prices',
                'variants.customer_group_prices',
                'catalog_rule_prices',
                'variants.catalog_rule_prices',
            ])
            ->join('product_attribute_values as special_price_from_pav', function ($join) {
                $join->on('products.id', '=', 'special_price_from_pav.product_id')
                    ->where('special_price_from_pav.attribute_id', self::SPECIAL_PRICE_FROM_ATTRIBUTE_ID);
            })
            ->join('product_attribute_values as special_price_to_pav', function ($join) {
                $join->on('products.id', '=', 'special_price_to_pav.product_id')
                    ->where('special_price_to_pav.attribute_id', self::SPECIAL_PRICE_TO_ATTRIBUTE_ID);
            })
            ->leftJoin('catalog_rule_product_prices', 'products.id', '=', 'catalog_rule_product_prices.product_id')
            ->where(function ($query) {
                return $query->orWhere('special_price_from_pav.date_value', Carbon::now()->format('Y-m-d'))
                    ->orWhere('special_price_to_pav.date_value', Carbon::now()->subDays(1)->format('Y-m-d'))
                    ->orWhere('catalog_rule_product_prices.rule_date', Carbon::now()->subDays(1)->format('Y-m-d'));
            })
            ->chunkById($this->batchSize, function ($products) use (&$productIds) {
                $this->reindexBatch($products->all());

                $productIds = [...$productIds, ...$products->pluck('id')->all()];
            }, 'products.id', 'id');

        $productIds = array_values(array_unique($productIds));

        $productIds = [...$productIds, ...$this->reindexCompositeParentsOf($productIds)];

        Event::dispatch('catalog.product.price.reindex.after', [$productIds]);
    }

    /**
     * Reindex the given products' prices for every channel and customer group.
     *
     * @return void
     */
    public function reindexBatch($products)
    {
        $newIndices = [];

        foreach ($products as $product) {
            $indexer = $this->getTypeIndexer($product)
                ->setProduct($product);

            foreach ($this->getChannels() as $channel) {
                foreach ($this->getCustomerGroups() as $customerGroup) {
                    $customerGroupIndex = $product->price_indices
                        ->where('channel_id', $channel->id)
                        ->where('customer_group_id', $customerGroup->id)
                        ->where('product_id', $product->id)
                        ->first();

                    $newIndex = $indexer
                        ->setChannel($channel)
                        ->setCustomerGroup($customerGroup)
                        ->getIndices();

                    if ($customerGroupIndex) {
                        $oldIndex = collect($customerGroupIndex->toArray())
                            ->except('id', 'created_at', 'updated_at')
                            ->toArray();

                        $isIndexChanged = $this->isIndexChanged(
                            $oldIndex,
                            $newIndex
                        );

                        if ($isIndexChanged) {
                            $this->productPriceIndexRepository->update($newIndex, $customerGroupIndex->id);
                        }
                    } else {
                        $newIndices[] = $newIndex;
                    }
                }
            }
        }

        $this->productPriceIndexRepository->insert($newIndices);
    }

    /**
     * Whether an index value changed.
     *
     * @return bool
     */
    public function isIndexChanged($oldIndex, $newIndex)
    {
        return (bool) count(array_diff_assoc($oldIndex, $newIndex));
    }

    /**
     * Get the price indexer of a product's type.
     *
     * @return Price\AbstractType
     */
    public function getTypeIndexer($product)
    {
        static $typeIndexers = [];

        if (isset($typeIndexers[$product->type])) {
            return $typeIndexers[$product->type];
        }

        return $typeIndexers[$product->type] = $product->getTypeInstance()->getPriceIndexer();
    }

    /**
     * Get every channel.
     *
     * @return Collection
     */
    public function getChannels()
    {
        if ($this->channels) {
            return $this->channels;
        }

        return $this->channels = core()->getAllChannels();
    }

    /**
     * Get every customer group.
     *
     * @return Collection
     */
    public function getCustomerGroups()
    {
        if ($this->customerGroups) {
            return $this->customerGroups;
        }

        return $this->customerGroups = $this->customerGroupRepository->all();
    }

    /**
     * Reindex the composite products built from the given products, which the date-based selective query
     * cannot find, returning their ids.
     */
    protected function reindexCompositeParentsOf(array $productIds): array
    {
        $parentIds = array_values(array_diff($this->productRepository->getCompositeParentIds($productIds), $productIds));

        foreach (array_chunk($parentIds, $this->batchSize) as $batchIds) {
            $this->reindexBatch($this->productRepository->with([
                'variants',
                'price_indices',
                'variants.attribute_values',
                'variants.price_indices',
                'variants.customer_group_prices',
                'variants.catalog_rule_prices',
            ])->findWhereIn('id', $batchIds)->all());
        }

        return $parentIds;
    }
}
