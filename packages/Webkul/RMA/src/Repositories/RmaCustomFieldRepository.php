<?php

namespace Webkul\RMA\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\RMA\Contracts\RMACustomField;

class RmaCustomFieldRepository extends Repository
{
    /**
     * Specify model class name
     */
    public function model(): string
    {
        return RMACustomField::class;
    }
}