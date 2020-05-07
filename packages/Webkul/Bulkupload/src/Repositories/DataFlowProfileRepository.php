<?php

namespace Webkul\Bulkupload\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Seller Invoice Reposotory
 *
 */
class DataFlowProfileRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Bulkupload\Contracts\DataFlowProfile';
    }
}