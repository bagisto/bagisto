<?php

namespace Webkul\Attribute\Repositories;

use Webkul\Attribute\Contracts\AttributeGroup;
use Webkul\Core\Eloquent\Repository;

class AttributeGroupRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return AttributeGroup::class;
    }
}
