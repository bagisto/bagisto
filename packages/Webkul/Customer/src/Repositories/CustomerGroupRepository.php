<?php

namespace Webkul\Customer\Repositories;

use Webkul\Core\Eloquent\Repository;

class CustomerGroupRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */

    function model()
    {
        return 'Webkul\Customer\Contracts\CustomerGroup';
    }

    /**
     * @param  array  $data
     * @return \Webkul\Customer\Contracts\CustomerGroup
     */
    public function create(array $data)
    {
        $customer = $this->model->create($data);

        return $customer;
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $id
     * @return \Webkul\Customer\Contracts\CustomerGroup
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $customer = $this->find($id);

        $customer->update($data);

        return $customer;
    }

    /**
     * Returns guest group.
     *
     * @return object
     */
    public function getCustomerGuestGroup()
    {
        static $customerGuestGroup;

        if ($customerGuestGroup) {
            return $customerGuestGroup;
        }

        return $customerGuestGroup = $this->findOneByField('code', 'guest');
    }
}