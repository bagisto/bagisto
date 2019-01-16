<?php

namespace Webkul\Customer\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Customer Reposotory
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class CustomerAddressRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */

    function model()
    {
        return 'Webkul\Customer\Models\CustomerAddress';
    }
}