<?php

namespace Webkul\Customer\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * CustomerGroup Reposotory
 *
 * @author    Rahul Shukla <rahulshukla.symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

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
}