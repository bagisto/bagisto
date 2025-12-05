<?php

namespace Webkul\RMA\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\RMA\Contracts\RMAAdditionalField;

class RMAAdditionalFieldRepository extends Repository
{
    /**
     * Specify model class name
     */
    public function model(): string
    {
        return RMAAdditionalField::class;
    }
}