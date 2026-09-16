<?php

namespace Webkul\Inventory\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Inventory\Contracts\InventorySource;

class InventorySourceRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return InventorySource::class;
    }
}
