<?php

namespace Webkul\RMA\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\RMA\Contracts\RMAReason;

class RMAReasonRepository extends Repository
{
    /**
     * Specify model class name
     */
    public function model(): string
    {
        return RMAReason::class;
    }
}
