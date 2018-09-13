<?php

namespace Webkul\Cart\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Cart Items Reposotory
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class CartProductRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */

    function model()
    {
        return 'Webkul\Cart\Models\CartProduct';
    }

    /**
     * @param array $data
     * @return mixed
     */

    public function create(array $data)
    {
        $cartitems = $this->model->create($data);

        return $cartitems;
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */

    public function update(array $data, $id, $attribute = "id")
    {
        $cartitems = $this->find($id);

        $cartitems->update($data);

        return $cartitems;
    }
}