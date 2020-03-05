<?php

namespace Webkul\Customer\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Wishlist Reposotory
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

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
     * @param  array   $data
     * @param  int     $id
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
        return $this->model->where([
            'channel_id'  => core()->getCurrentChannel()->id,
            'customer_id' => auth()->guard('customer')->user()->id,
        ])->paginate(5);
    }
}