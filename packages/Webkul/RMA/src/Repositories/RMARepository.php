<?php

namespace Webkul\RMA\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\RMA\Contracts\RMA;

class RMARepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return RMA::class;
    }
}
