<?php

namespace Webkul\Customer\Repositories;

use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;

class CustomerGroupRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return mixed
     */

    public function model()
    {
        return \Webkul\Customer\Contracts\CustomerGroup::class;
    }

    /**
     * Create.
     *
     * @param  array  $data
     * @return \Webkul\Customer\Contracts\CustomerGroup
     */
    public function create(array $data)
    {
        Event::dispatch('customer.customer_group.create.before');

        $customerGroup = $this->model->create($data);

        Event::dispatch('customer.customer_group.create.after', $customerGroup);

        return $customerGroup;
    }

    /**
     * Update.
     *
     * @param  array  $data
     * @param  int  $id
     * @param  string  $id
     * @return \Webkul\Customer\Contracts\CustomerGroup
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $customer = $this->findOrFail($id);

        Event::dispatch('customer.customer_group.update.before', $id);

        $customerGroup = $customer->update($data);

        Event::dispatch('customer.customer_group.update.after', $customerGroup);

        return $customerGroup;
    }

    /**
     * Delete.
     *
     * @param  $id
     * @return int
     */
    public function delete($id)
    {
        Event::dispatch('customer.customer_group.delete.before', $id);

        parent::delete($id);

        Event::dispatch('customer.customer_group.delete.after', $id);
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
