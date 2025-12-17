<?php

namespace Webkul\RMA\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\RMA\Contracts\RMAReasonResolution;

class RMAReasonResolutionRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return RMAReasonResolution::class;
    }

    
}
