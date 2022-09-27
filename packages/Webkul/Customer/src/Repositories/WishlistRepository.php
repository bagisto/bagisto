<?php

namespace Webkul\Customer\Repositories;

use Webkul\Core\Eloquent\Repository;

class WishlistRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Customer\Contracts\Wishlist';
    }

    /**
     * Create wishlist.
     *
     * @param  array  $data
     * @return \Webkul\Customer\Contracts\Wishlist
     */
    public function create(array $data)
    {
        $wishlist = $this->model->create($data);

        return $wishlist;
    }

    /**
     * Update wishlist.
     *
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
     * Get shared wishlist by customer's id.
     *
     * @param  int  $id
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getSharedWishlistByCustomerId($id)
    {
        return $this
            ->where('customer_id', $id)
            ->where('shared', 1)
            ->get();
    }

    /**
     * Get customer wishlist items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCustomerWishlist()
    {
        return $this->model->select('wishlist.*')->where([
            'channel_id'  => core()->getCurrentChannel()->id,
            'customer_id' => auth()->guard('customer')->user()->id,
        ])->paginate(5);
    }
}
