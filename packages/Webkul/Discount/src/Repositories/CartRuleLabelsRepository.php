<?php

namespace Webkul\Discount\Repositories;

use Webkul\Core\Eloquent\Repository;

class CartRuleLabelsRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Discount\Contracts\CartRuleLabels';
    }
}