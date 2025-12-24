<?php

namespace Webkul\RMA\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\RMA\Contracts\RMAItem;

class RMAItemRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return RMAItem::class;
    }
}
