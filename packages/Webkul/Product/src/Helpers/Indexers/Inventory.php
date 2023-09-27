<?php

namespace Webkul\Product\Helpers\Indexers;

use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductInventoryIndexRepository;

class Inventory extends AbstractIndexer
{
    /**
     * @var int
     */
    private $batchSize;

    /**
     * Product instance.
     *
     * @var \Webkul\Product\Contracts\Product
     */
    protected $product;

    /**
     * Channel instance.
     *
     * @var \Webkul\Core\Contracts\Channel
     */
    protected $channel;

    /**
     * Channels
     *
     * @var array
     */
    protected $channels;

    /**
     * Create a new indexer instance.
     *
     * @param  \Webkul\Core\Repositories\ChannelRepository  $channelRepository
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Product\Repositories\ProductInventoryIndexRepository  $productInventoryIndexRepository
     * @return void
     */
    public function __construct(
        protected ChannelRepository $channelRepository,
        protected ProductRepository $productRepository,
        protected ProductInventoryIndexRepository $productInventoryIndexRepository
    )
    {
        $this->batchSize = self::BATCH_SIZE;
    }

    /**
     * Set current product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return \Webkul\Product\Helpers\Indexers\Inventory\Product
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Set channel
     *
     * @param  \Webkul\Core\Contracts\Channel  $channel
     * @return \Webkul\Product\Helpers\Indexers\Inventory\Product
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
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
                    'inventories',
                    'ordered_inventories',
                    'inventory_indices',
                ])
                ->whereIn('type', ['simple', 'virtual'])
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
            $this->setProduct($product);

            foreach ($this->getChannels() as $channel) {
                $this->setChannel($channel);

                $channelIndex = $product->inventory_indices
                    ->where('channel_id', $channel->id)
                    ->where('product_id', $product->id)
                    ->first();

                $newIndex = $this->getIndices();

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
     * Check if index value changed
     *
     * @return boolean
     */
    public function isIndexChanged($oldIndex, $newIndex)
    {
        return (boolean) count(array_diff_assoc($oldIndex, $newIndex));
    }

    /**
     * Returns product specific indices
     *
     * @return array
     */
    public function getIndices()
    {
        return [
            'qty'        => $this->getQuantity(),
            'product_id' => $this->product->id,
            'channel_id' => $this->channel->id,
        ];
    }

    /**
     * Returns product remaining quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        $channelInventorySourceIds = $this->channel->inventory_sources->where('status', 1)->pluck('id');

        $qty = 0;

        foreach ($this->product->inventories as $inventory) {
            if (is_numeric($channelInventorySourceIds->search($inventory->inventory_source_id))) {
                $qty += $inventory->qty;
            }
        }

        $orderedInventory = $this->product->ordered_inventories
            ->where('channel_id', $this->channel->id)->first();

        if ($orderedInventory) {
            $qty -= $orderedInventory->qty;
        }

        return $qty;
    }
    
    /**
     * Returns all channels
     *
     * @return Collection
     */
    public function getChannels()
    {
        if ($this->channels) {
            return $this->channels;
        }

        return $this->channels = $this->channelRepository->all();
    }
}