<?php

namespace Webkul\API\Http\Controllers\Customer;

use Webkul\API\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

    public function __construct()
    {
        if(auth()->guard('customer')->check()) {
            $this->customer = auth()->guard('customer')->user();
        } else {
            $this->customer = false;
        }
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
                return response()->json('Wishlist Empty', 200);
            }
        }
    }
}