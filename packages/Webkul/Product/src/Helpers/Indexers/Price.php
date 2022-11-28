<?php

namespace Webkul\Product\Helpers\Indexers;

use Illuminate\Support\Carbon;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductPriceIndexRepository;

class Price extends AbstractIndexer
{
    /**
     * @var int
     */
    private $batchSize;

    /**
     * Create a new indexer instance.
     *
     * @param  \Webkul\Customer\Repositories\CustomerGroupRepository  $customerGroupRepository
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Product\Repositories\ProductPriceIndexRepository  $productPriceIndexRepository
     * @return void
     */
    public function __construct(
        protected CustomerGroupRepository $customerGroupRepository,
        protected ProductRepository $productRepository,
        protected ProductPriceIndexRepository $productPriceIndexRepository
    )
    {
        $this->batchSize = self::BATCH_SIZE;
    }

    /**
     * Reindex all products
     *
     * @return void
     */
    public function reindexFull()
    {
        while (true) {
            $paginator = $this->productRepository
                ->with([
                    'variants',
                    'attribute_values',
                    'variants.attribute_values',
                    'price_indices',
                    'variants.price_indices',
                    'customer_group_prices',
                    'variants.customer_group_prices',
                    'catalog_rule_prices',
                    'variants.catalog_rule_prices',
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
     * Reindexed products with price which depends on date
     *
     * @return void
     */
    public function reindexSelective()
    {
        while (true) {
            $paginator = $this->productRepository
                ->select('products.*')
                ->with([
                    'variants',
                    'attribute_values',
                    'variants.attribute_values',
                    'price_indices',
                    'variants.price_indices',
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
                ->where(function($query) {
                    return $query->orWhere('special_price_from_pav.date_value', Carbon::now()->format('Y-m-d'))
                        ->orWhere('special_price_to_pav.date_value', Carbon::now()->subDays(1)->format('Y-m-d'));
                })
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
     * Reindex products by batch size
     *
     * @return void
     */
    public function reindexBatch($products)
    {
        $newIndices = [];

        foreach ($products as $product) {
            $indexer = $this->getTypeIndexer($product)
                ->setProduct($product);

            foreach ($this->getCustomerGroups() as $customerGroup) {
                $customerGroupIndex = $product->price_indices
                    ->where('customer_group_id', $customerGroup->id)
                    ->where('product_id', $product->id)
                    ->first();

                $newIndex = $indexer->setCustomerGroup($customerGroup)->getIndices();

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

        $this->productPriceIndexRepository->insert($newIndices);
    }

    /**
     * Check if index value changed
     *
     * @return boolean
     */
    public function isIndexChanged($oldIndex, $newIndex)
    {
        return (boolean) count(array_diff_assoc($oldIndex, $newIndex));
    }

    /**
     * Returns indexer for product type
     *
     * @return string
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
     * Returns all customer groups
     *
     * @return Collection
     */
    public function getCustomerGroups()
    {
        static $customerGroups;

        if ($customerGroups) {
            return $customerGroups;
        }

        return $customerGroups = $this->customerGroupRepository->all();
    }
}