<?php

namespace Webkul\Cart\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Cart Reposotory
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class CartRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */

    function model()
    {
        return 'Webkul\Cart\Models\Cart';
    }

    /**
     * @param array $data
     * @return mixed
     */

    public function create(array $data)
    {
        $cart = $this->model->create($data);

        return $cart;
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */

    public function update(array $data, $id, $attribute = "id")
    {
        $cart = $this->find($id);

        $cart->update($data);

        return $cart;
    }

    public function getProducts($id) {

        return $this->model->find($id)->with_products;
    }

    /**
     * Method to attach
     * associations
     *
     * @return Eloquent
    */
    public function attach($cart_id, $product_id, $quantity) {

        $this->model->findOrFail($cart_id)->with_products()->attach($cart_id, ['product_id' => $product_id, 'cart_id' => $cart_id, 'quantity' => $quantity]);

    }

    /**
     * Method to detach
     * associations
     *
     * @return Eloquent
     */
}