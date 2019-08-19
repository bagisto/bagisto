<?php

namespace Webkul\Product\Type;

/**
 * Class Simple.
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Simple extends AbstractType
{
    /**
     * Skip attribute for simple product type
     *
     * @var array
     */
    protected $skipAttributes = [];

    /**
     * These blade files will be included in product edit page
     * 
     * @var array
     */
    protected $additionalViews = [
        'admin::catalog.products.accordians.inventories',
        'admin::catalog.products.accordians.images',
        'admin::catalog.products.accordians.categories',
        'admin::catalog.products.accordians.product-links'
    ];

    /**
     * Return true if this product type is saleable
     *
     * @return boolean
     */
    public function isSaleable()
    {
        if (! $this->product->status)
            return false;

        if ($this->haveSufficientQuantity(1))
            return true;

        return false;
    }

    /**
     * Return true if this product can have inventory
     *
     * @return boolean
     */
    public function isStockable()
    {
        return true;
    }

    /**
     * @param integer $qty
     *
     * @return boolean
     */
    public function haveSufficientQuantity($qty)
    {
        return $qty <= $this->totalQuantity() ? true : (core()->getConfigData('catalog.inventory.stock_options.backorders') ? true : false);
    }

    /**
     * @return integer
     */
    public function totalQuantity()
    {
        $total = 0;

        $channelInventorySourceIds = core()->getCurrentChannel()
                ->inventory_sources()
                ->where('status', 1)
                ->pluck('id');

        foreach ($this->product->inventories as $inventory) {
            if (is_numeric($index = $channelInventorySourceIds->search($inventory->inventory_source_id))) {
                $total += $inventory->qty;
            }
        }

        $orderedInventory = $this->product->ordered_inventories()
                ->where('channel_id', core()->getCurrentChannel()->id)
                ->first();

        if ($orderedInventory) {
            $total -= $orderedInventory->qty;
        }

        return $total;
    }
}