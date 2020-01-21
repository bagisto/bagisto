<?php

namespace Webkul\Checkout\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Cart Address Reposotory
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class CartAddressRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return Mixed
     */
    function model()
    {
        return 'Webkul\Checkout\Contracts\CartAddress';
    }
}