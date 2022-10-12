<?php

namespace Webkul\Inventory\Repositories;

use Webkul\Core\Eloquent\Repository;

class InventorySourceRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return 'Webkul\Inventory\Contracts\InventorySource';
    }
}
