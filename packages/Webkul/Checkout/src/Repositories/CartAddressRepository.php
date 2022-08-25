<?php

namespace Webkul\Checkout\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Cart Address Repository
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartAddressRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Checkout\Contracts\CartAddress';
    }
}