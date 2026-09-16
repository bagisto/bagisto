<?php

namespace Webkul\Checkout\Repositories;

use Webkul\Checkout\Contracts\CartItem;
use Webkul\Core\Eloquent\Repository;

class CartItemRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return CartItem::class;
    }

    /**
     * The id of the product a cart item holds.
     *
     * @param  int  $cartItemId
     * @return int
     */
    public function getProduct($cartItemId)
    {
        return $this->model->find($cartItemId)->product->id;
    }
}
