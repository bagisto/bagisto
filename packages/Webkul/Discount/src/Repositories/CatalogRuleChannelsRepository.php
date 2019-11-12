<?php

namespace Webkul\Discount\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * CatalogRuleChannelsReposotory
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com>
 * @copyright  2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CatalogRuleChannelsRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return String
     */
    function model()
    {
        return 'Webkul\Discount\Contracts\CatalogRuleChannels';
    }
}
