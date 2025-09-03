<?php

namespace Webkul\Customer\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Customer\Contracts\CustomerAddress;

class CustomerAddressRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return CustomerAddress::class;
    }
}
