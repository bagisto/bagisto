<?php

namespace Webkul\Inventory\Repositories;

use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;

class InventorySourceRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return \Webkul\Inventory\Contracts\InventorySource::class;
    }

    /**
     * Create.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        Event::dispatch('inventory.inventory_source.create.before');

        $inventorySource = parent::create($attributes);

        Event::dispatch('inventory.inventory_source.create.after', $inventorySource);

        return $inventorySource;
    }

    /**
     * Update.
     *
     * @param  array  $attributes
     * @param  $id
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        Event::dispatch('inventory.inventory_source.update.before', $id);

        $inventorySource = parent::update($attributes, $id);

        Event::dispatch('inventory.inventory_source.update.after', $inventorySource);

        return $inventorySource;
    }

    /**
     * Delete.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete($id)
    {
        Event::dispatch('inventory.inventory_source.delete.before', $id);

        parent::delete($id);

        Event::dispatch('inventory.inventory_source.delete.after', $id);
    }

    /**
     * Returns channel inventory source ids.
     *
     * @return collection
     */
    public function getChannelInventorySourceIds()
    {
        static $channelInventorySourceIds;

        if ($channelInventorySourceIds) {
            return $channelInventorySourceIds;
        }

        $found = core()->getCurrentChannel()->inventory_sources()
            ->where('status', 1)
            ->pluck('id');

        return $channelInventorySourceIds = ($found ? $found : collect([]));
    }
}
