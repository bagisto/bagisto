<?php

namespace Webkul\Product\Helpers\Indexers;

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

    public function reindexFull()
    {
        while (true) {
            $paginator = $this->productRepository
                ->with(['price_indices'])
                ->cursorPaginate($this->batchSize);
 
            $this->insertBatch($paginator->items());
 
            if (! $cursor = $paginator->nextCursor()) {
                break;
            }
 
            request()->query->add(['cursor' => $cursor->encode()]);
        }

        request()->query->remove('cursor');
    }
    
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function insertBatch($products)
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
     * Execute the console command.
     *
     * @return void
     */
    public function isIndexChanged($oldIndex, $newIndex)
    {
        return (boolean) count(array_diff_assoc($oldIndex, $newIndex));
    }

    /**
     * Execute the console command.
     *
     * @return void
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
     * Execute the console command.
     *
     * @return void
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