<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;

/**
 * OrderItem Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class OrderItemRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return Mixed
     */

    function model()
    {
        return 'Webkul\Sales\Contracts\OrderItem';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        if(isset($data['product']) && $data['product'] instanceof Model) {
            $data['product_id'] = $data['product']->id;
            $data['product_type'] = get_class($data['product']);
        }

        return $this->model->create($data);
    }
}