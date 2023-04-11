<?php

namespace Webkul\Checkout\Repositories;

use Webkul\Core\Eloquent\Repository;

class CartItemRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Checkout\Contracts\CartItem';
    }

    /**
     * @param  int  $cartItemId
     * @return int
     */
    public function getProduct($cartItemId)
    {
        return $this->model->find($cartItemId)->product->id;
    }

    /**
     * Sum of specific Columns
     *
     * @return Collection
     */
    public function getSumOfColumns($id, $productId = null)
    {
        $query = $this->model
            ->selectRaw("sum(base_total) as base_grand_total, sum(total) as grand_total, sum(base_price) as base_sub_total, sum(price) as sub_total")
            ->where("cart_id", $id);
        
        if ($productId) {
            $query->where("product_id", $productId);
        }

        return $query->first();
    }
}