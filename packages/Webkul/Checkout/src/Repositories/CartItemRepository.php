<?php

namespace Webkul\Checkout\Repositories;

use Webkul\Core\Eloquent\Repository;

class CartItemRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */

    function model()
    {
        return 'Webkul\Checkout\Contracts\CartItem';
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Checkout\Contracts\CartItem
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $item = $this->find($id);

        $item->update($data);

        return $item;
    }

    /**
     * @param  int  $cartItemId
     * @return int
     */
    public function getProduct($cartItemId)
    {
        return $this->model->find($cartItemId)->product->id;
    }
}