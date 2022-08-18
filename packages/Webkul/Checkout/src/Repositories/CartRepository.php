<?php

namespace Webkul\Checkout\Repositories;

use Webkul\Core\Eloquent\Repository;

class CartRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Checkout\Contracts\Cart';
    }

    /**
     * Method to detach associations. Use this only with guest cart only.
     * 
     * @param  int  $cartId
     * @return bool
     */
    public function deleteParent($cartId)
    {
        return $this->model->destroy($cartId);
    }
}