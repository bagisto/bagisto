<?php

namespace Webkul\Customer\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Customer\Contracts\CustomerGroup;

class CustomerGroupRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return CustomerGroup::class;
    }
}
