<?php

namespace Webkul\Checkout\Repositories;

use Webkul\Core\Eloquent\Repository;

class CartRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return Mixed
     */

    function model()
    {
        return 'Webkul\Checkout\Contracts\Cart';
    }

    /**
     * Method to detach associations. Use this only with guest cart only.
     * 
     * @param  int  $cartId
     * @return bool
     */
    public function deleteParent($cartId) {
        $cart = $this->model->find($cartId);

        return $this->model->destroy($cartId);
    }
}