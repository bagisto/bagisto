<?php

namespace Webkul\Sales\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Sales\Contracts\OrderTransaction;

/**
 * Order Transaction Repository
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderTransactionRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */

    function model()
    {
        return OrderTransaction::class;
    }
}