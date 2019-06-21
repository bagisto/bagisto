<?php

namespace Webkul\CustomerDocument\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Customer Reposotory
 *
 * @author    Rahul Shukla <rahulshukla517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CustomerDocumentRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */

    function model()
    {
        return 'Webkul\CustomerDocument\Contracts\CustomerDocument';
    }
}