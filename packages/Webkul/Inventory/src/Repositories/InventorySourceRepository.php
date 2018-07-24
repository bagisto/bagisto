<?php 

namespace Webkul\Inventory\Repositories;
 
use Webkul\Core\Eloquent\Repository;

/**
 * Inventory Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class InventorySourceRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Inventory\Models\InventorySource';
    }
}