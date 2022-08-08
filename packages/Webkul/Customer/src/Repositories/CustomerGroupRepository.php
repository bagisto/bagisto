<?php

namespace Webkul\Customer\Repositories;

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
