<?php

namespace Webkul\Attribute\Repositories;

use Webkul\Core\Eloquent\Repository;

class AttributeGroupRepository extends Repository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Attribute\Contracts\AttributeGroup';
    }
}