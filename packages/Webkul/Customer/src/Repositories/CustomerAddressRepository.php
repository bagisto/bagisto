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
    public function create(array $data): CustomerAddress
    {
        $defaultAddress = $this->findOneWhere(['customer_id' => $data['customer_id'], 'default_address' => 1]);

        if ($defaultAddress) {
            $defaultAddress->update(['default_address' => 0]);
        }

        $address = $this->model->create($data);

        return $address;
    }

    /**
     * Update customer address.
     *
     * @param  int  $id
     */
    public function update(array $data, $id): CustomerAddress
    {
        $address = $this->find($id);

        $defaultAddress = $this->findOneWhere(['customer_id' => $address->customer_id, 'default_address' => 1]);

        if (
            $defaultAddress
            && $defaultAddress->id != $address->id
        ) {
            $defaultAddress->update(['default_address' => 0]);
        }

        $address->update($data);

        return $address;
    }
}
