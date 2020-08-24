<?php

namespace Webkul\Checkout\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Checkout\Contracts\CartItem;

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
     * @param array  $data
     * @param        $id
     * @param string $attribute
     *
     * @return \Webkul\Checkout\Contracts\CartItem|null
     */
    public function update(array $data, $id, $attribute = "id"): ?CartItem
    {
        $item = $this->find($id);

        if ($item) {
            $item->update($data);
        }

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