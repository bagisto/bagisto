<?php

namespace Webkul\Product\Helpers;

use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Product\Repositories\ProductPriceIndexRepository;
use Webkul\Product\Repositories\ProductInventoryIndexRepository;
use Webkul\Product\Helpers\Indexers\Flat\Product as FlatIndexer;
use Webkul\Product\Helpers\Indexers\Inventory\Product as InventoryIndexer;
use Webkul\Product\Helpers\Indexers\ElasticSearch\Product as ElasticSearchIndexer;

class Indexer
{
    /**
     * Create a new command instance.
     *
     * @param  \Webkul\Customer\Repositories\CustomerGroupRepository  $customerGroupRepository
     * @param  \Webkul\Product\Repositories\ProductPriceIndexRepository  $productPriceIndexRepository
     * @param  \Webkul\Product\Repositories\ProductInventoryIndexRepository  $productInventoryIndexRepository
     * @param  \Webkul\Product\Helpers\Indexers\Flat\Product  $flatIndexer
     * @param  \Webkul\Product\Helpers\Indexers\Inventory\Product  $inventoryIndexer
     * @param  \Webkul\Product\Helpers\Indexers\ElasticSearch\Product  $elasticSearchIndexer
     * @return void
     */
    public function __construct(
        protected CustomerGroupRepository $customerGroupRepository,
        protected ProductPriceIndexRepository $productPriceIndexRepository,
        protected ProductInventoryIndexRepository $productInventoryIndexRepository,
        protected FlatIndexer $flatIndexer,
        protected InventoryIndexer $inventoryIndexer,
        protected ElasticSearchIndexer $elasticSearchIndexer
    )
    {
    }

    /**
     * Refresh product indexes
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @param  array  $indexers
     * @return void
     */
    public function refresh($product, array $indexers = ['inventory', 'price', 'elastic'])
    {
        if (in_array('inventory', $indexers)) {
            $this->refreshInventory($product);
        }

        if (in_array('price', $indexers)) {
            $this->refreshPrice($product);
        }

        if (in_array('elastic', $indexers)) {
            $this->refreshElasticSearch($product);
        }
    }

    /**
     * Refresh product flat indexes
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function refreshFlat($product)
    {
        $this->flatIndexer->refresh($product);
    }

    /**
     * Refresh product price indexes
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function refreshPrice($product)
    {
        $indexer = $product->getTypeInstance()
            ->getPriceIndexer()
            ->setProduct($product);

        $customerGroups = $this->customerGroupRepository->all();

        foreach ($customerGroups as $customerGroup) {
            $this->productPriceIndexRepository->updateOrCreate([
                'customer_group_id' => $customerGroup->id,
                'product_id'        => $product->id,
            ], $indexer->getIndices($customerGroup));
        }
    }

    /**
     * Refresh product inventory indices
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function refreshInventory($product)
    {
        if (in_array($product, ['configurable', 'bundle', 'grouped', 'booking'])) {
            return;
        }

        foreach (core()->getAllChannels() as $channel) {
            $this->productInventoryIndexRepository->updateOrCreate([
                'channel_id' => $channel->id,
                'product_id' => $product->id,
            ], $this->inventoryIndexer->setProduct($product)->getIndices($channel));
        }
    }

    /**
     * Refresh elastic search indices
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function refreshElasticSearch($product)
    {
        if (core()->getConfigData('catalog.products.storefront.search_mode') != 'elastic') {
            return;
        }

        foreach (core()->getAllChannels() as $channel) {
            foreach ($channel->locales as $locale) {
                $this->elasticSearchIndexer
                    ->setProduct($product)
                    ->setChannel($channel)
                    ->setLocale($locale)
                    ->refresh();
            }
        }
    }
}