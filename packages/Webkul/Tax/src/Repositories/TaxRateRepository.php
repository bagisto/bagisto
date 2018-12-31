<?php

namespace Webkul\Tax\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Tax Rate Reposotory
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class TaxRateRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Tax\Models\TaxRate';
    }
}