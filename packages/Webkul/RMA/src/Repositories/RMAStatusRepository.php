<?php

namespace Webkul\RMA\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\RMA\Contracts\RMAStatus;

class RMAStatusRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return RMAStatus::class;
    }
}
