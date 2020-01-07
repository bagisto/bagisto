<?php

namespace Webkul\CatalogRule\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * CatalogRuleProductPrice Repository
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CatalogRuleProductRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\CatalogRule\Contracts\CatalogRuleProduct';
    }
}