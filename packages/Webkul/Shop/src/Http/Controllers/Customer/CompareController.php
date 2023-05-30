<?php

namespace Webkul\Shop\Http\Controllers\Customer;

use Webkul\Shop\Http\Controllers\Controller;

class CompareController extends Controller
{
    /**
     * Address route index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('shop::customers.account.compare.index');
    }
}
