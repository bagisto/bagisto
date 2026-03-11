<?php

namespace Webkul\Shop\Http\Controllers;

class CustomerController extends Controller
{
    // show custom profile index
    public function customerProfileIndex()
    {
        return view('shop::customer_profile.index');
    }
}
