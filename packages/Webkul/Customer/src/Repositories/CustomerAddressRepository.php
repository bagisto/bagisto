<?php

namespace Webkul\Customer\Repositories;

use Webkul\Core\Eloquent\Repository;

class CustomerAddressRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Customer\Contracts\CustomerAddress';
    }

    /**
     * @param  array  $data
     * @return \Webkul\Customer\Contracts\CustomerAddress
     */
    public function create(array $data)
    {
        $data['default_address'] = isset($data['default_address']);

        $default_address = $this
            ->findWhere(['customer_id' => $data['customer_id'], 'default_address' => 1])
            ->first();

        if (
            $default_address
            && $data['default_address']
        ) {
            $default_address->update(['default_address' => 0]);
        }

        $address = $this->model->create($data);

        return $address;
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @return \Webkul\Customer\Contracts\CustomerAddress
     */
    public function update(array $data, $id)
    {
        $address = $this->find($id);

        $data['default_address'] = $data['default_address'] ?? $address->default_address;

        $default_address = $this
            ->findWhere(['customer_id' => $address->customer_id, 'default_address' => 1])
            ->first();

        if (
            isset($default_address->id)
            && $data['default_address']
        ) {
            if ($default_address->id != $address->id) {
                $default_address->update(['default_address' => 0]);
            }

            $address->update($data);
        } else {
            $address->update($data);
        }

        return $address;
    }
}
