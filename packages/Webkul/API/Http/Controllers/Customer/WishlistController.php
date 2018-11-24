<?php

namespace Webkul\API\Http\Controllers\Customer;

use Webkul\API\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Customer\Repositories\WishlistRepository as Wishlist;
use Auth;
use Cart;

/**
 * Wishlist controller for the APIs of User's Wishlist
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class WishlistController extends Controller
{
    protected $customer;
    protected $product;
    protected $wishlist;

    public function __construct(Product $product, Wishlist $wishlist)
    {
        if(auth()->guard('customer')->check()) {
            $this->product = $product;
            $this->wishlist = $wishlist;
            $this->customer = auth()->guard('customer')->user();
        } else {
            $this->customer['message'] = 'unauthorized';
            $this->unAuthorized();
        }
    }

    public function unAuthorized() {
        return response()->json($this->customer, 401);
    }

    public function getWishlist()
    {
        if($this->customer == false) {
            return response()->json($this->customer, 401);
        } else {
            $wishlist = $this->customer->wishlist_items;

            if($wishlist->count() > 0) {
                return response()->json($wishlist, 200);
            } else {
                return response()->json(['message' => 'Wishlist Empty', 'Items' => null], 200);
            }
        }
    }

    /**
     * Function to add item to the wishlist.
     * Only accepts the id of simple or configurable product
     *
     * @param integer $productId
     */
    public function add($productId)
    {
        $product = $this->product->findOneByField('id', $productId);

        $data = [
            'channel_id' => core()->getCurrentChannel()->id,
            'product_id' => $productId,
            'customer_id' => auth()->guard('customer')->user()->id
        ];

        //accidental case if some one adds id of the product in the anchor tag amd gives id of a variant.
        if($product->parent_id != null) {
            $data['product_id'] = $productId = $product->parent_id;
        }

        $checked = $this->wishlist->findWhere(['channel_id' => core()->getCurrentChannel()->id, 'product_id' => $productId, 'customer_id' => auth()->guard('customer')->user()->id]);

        if($checked->isEmpty()) {
            if($wishlistItem = $this->wishlist->create($data)) {
                return response()->json(['message' => 'Successfully Added Item To Wishlist', 'items' => $wishlistItem], 200);
            } else {
                return response()->json(['message' => 'Error! Cannot Add Item To Wishlist', 'items' => null], 401);
            }
        } else {
            return response()->json(['message' => trans('customer::app.wishlist.already'), 'items' => null], 200);
        }
    }

    /**
     * Function to remove item to the wishlist.
     *
     * @param integer $itemId
     */
    public function delete($itemId) {
        $result = $this->wishlist->deleteWhere(['customer_id' => auth()->guard('customer')->user()->id, 'channel_id' => core()->getCurrentChannel()->id, 'id' => $itemId]);

        if($result) {
            return response()->json(['message' => 'Item Successfully Removed From Wishlist', 'status' => $result]);
        } else {
            return response()->json(['message' => 'Error! While Removing Item From Wishlist', 'status' => $result]);
        }
    }
}