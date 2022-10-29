<?php

namespace Webkul\Product\Helpers\Indexers\Inventory;

class Product
{
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
}
