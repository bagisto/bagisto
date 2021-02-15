<?php

namespace Webkul\Customer\Repositories;

use Webkul\Core\Eloquent\Repository;

class WishlistRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */

    function model()
    {
        return 'Webkul\Customer\Contracts\Wishlist';
    }

    /**
     * @param  array  $data
     * @return \Webkul\Customer\Contracts\Wishlist
     */
    public function create(array $data)
    {
        $wishlist = $this->model->create($data);

        return $wishlist;
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Customer\Contracts\Wishlist
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $wishlist = $this->find($id);

        $wishlist->update($data);

        return $wishlist;
    }

    /**
     * To retrieve products with wishlist for a listing resource.
     *
     * @param  int  $id
     * @return \Webkul\Customer\Contracts\Wishlist
     */
    public function getItemsWithProducts($id)
    {
        return $this->model->find($id)->item_wishlist;
    }

    /**
     * get customer wishlist Items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCustomerWhishlist()
    {
        $query = $this->model;

        if (! core()->getConfigData('catalog.products.homepage.out_of_stock_items')) {
            $query = $this->model
            ->leftJoin('products as ps', 'wishlist.product_id', '=', 'ps.id')
            ->leftJoin('product_inventories as pv', 'ps.id', '=', 'pv.product_id')
            ->where(function ($qb) {
                $qb
                    ->WhereIn('ps.type', ['configurable', 'grouped', 'downloadable', 'bundle', 'booking'])
                    ->orwhereIn('ps.type', ['simple', 'virtual'])->where('pv.qty' , '>' , 0);
            });
        }

        return $query->where([
            'channel_id'  => core()->getCurrentChannel()->id,
            'customer_id' => auth()->guard('customer')->user()->id,
        ])->paginate(5);
    }
}