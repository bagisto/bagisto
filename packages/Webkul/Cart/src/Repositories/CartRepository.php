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
     * @return Mixed
     */

    function model()
    {
        return 'Webkul\Cart\Models\Cart';
    }

    /**
     * @param array $data
     * @return Mixed
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
     * @return Mixed
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
     * @return Mixed
    */
    public function attach($cart_id, $product_id, $quantity) {

        return $this->model->findOrFail($cart_id)->with_products()->attach($cart_id, ['product_id' => $product_id, 'cart_id' => $cart_id, 'quantity' => $quantity]);

    }

    /**
     * This will update the
     * quantity of product
     * for the customer,
     * in case of merge.
     *
     * @return Mixed
     */
    public function updateRelatedForMerge($pivot, $column, $value) {
        $cart_product = $this->model->findOrFail($pivot['cart_id']);

        return $cart_product->with_products()->updateExistingPivot($pivot['product_id'], array($column => $value));
    }

    /**
     * Method to detach
     * associations.
     *
     * Use this only with
     * guest cart only.
     *
     * @return Mixed
     */
    public function detachAndDeleteParent($cart_id) {
        $cart = $this->model->find($cart_id);

        //apply strict check for verifying guest ownership on this record.
        $cart->with_products()->detach();

        return $this->model->destroy($cart_id);
    }
}