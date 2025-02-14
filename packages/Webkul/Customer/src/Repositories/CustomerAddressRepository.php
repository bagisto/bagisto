<?php

namespace Webkul\Customer\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Customer\Contracts\CustomerAddress;

class CustomerAddressRepository extends Repository
{
    /**
     * Specify Model class name.
     */
    public function model(): string
    {
        return CustomerAddress::class;
    }

    /**
     * Create a new customer address.
     */
    public function create(array $data)
    {
        if (! empty($data['default_address'])) {
            $this->model->where('customer_id', $data['customer_id'])
                ->where('default_address', 1)
                ->update(['default_address' => 0]);
        }

        return $this->model->create($data);
    }
}
