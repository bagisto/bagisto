<?php

namespace Webkul\Product\Helpers\Indexers;

use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductInventoryIndexRepository;
use Webkul\Product\Helpers\Indexers\Inventory\Product as InventoryIndexer;

class Inventory extends AbstractIndexer
{
    /**
     * @var int
     */
    private $batchSize;

    /**
     * Create a new indexer instance.
     *
     * @param  \Webkul\Core\Repositories\ChannelRepository  $channelRepository
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Product\Repositories\ProductInventoryIndexRepository  $productInventoryIndexRepository
     * @param  \Webkul\Product\Helpers\Indexers\Inventory  $inventoryIndexer
     * @return void
     */
    public function __construct(
        protected ChannelRepository $channelRepository,
        protected ProductRepository $productRepository,
        protected ProductInventoryIndexRepository $productInventoryIndexRepository,
        protected InventoryIndexer $inventoryIndexer
    )
    {
        $this->batchSize = self::BATCH_SIZE;
    }

    public function reindexFull()
    {
        while (true) {
            $paginator = $this->productRepository
                ->with(['inventory_indices'])
                ->whereIn('type', ['simple', 'virtual'])
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
            $indexer = $this->inventoryIndexer->setProduct($product);

            foreach ($this->getChannels() as $channel) {
                $channelIndex = $product->inventory_indices
                    ->where('channel_id', $channel->id)
                    ->where('product_id', $product->id)
                    ->first();

                $newIndex = $indexer->setChannel($channel)->getIndices();

                if ($channelIndex) {
                    $oldIndex = collect($channelIndex->toArray())
                        ->except('id', 'created_at', 'updated_at')
                        ->toArray();

                    $isIndexChanged = $this->isIndexChanged(
                        $oldIndex,
                        $newIndex
                    );

                    if ($isIndexChanged) {
                        $this->productInventoryIndexRepository->update($newIndex, $channelIndex->id);
                    }
                } else {
                    $newIndices[] = $newIndex;
                }
            }
        }

        $this->productInventoryIndexRepository->insert($newIndices);
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
    public function getChannels()
    {
        static $channels;

        if ($channels) {
            return $channels;
        }

        return $channels = $this->channelRepository->all();
    }
}