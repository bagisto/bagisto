<?php

namespace Webkul\Shop\Http\Controllers\Customer\Account;

use Webkul\Shop\Http\Controllers\Controller;

class WishlistController extends Controller
{
    /**
     * Displays the listing resources if the customer having items in wishlist.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (! core()->getConfigData('general.content.shop.wishlist_option')) {
            abort(404);
        }

        return view('shop::customers.account.wishlist.index');
    }
}
