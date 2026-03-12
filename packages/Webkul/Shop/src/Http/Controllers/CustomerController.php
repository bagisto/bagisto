<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Webkul\Customer\Models\Customer;

class CustomerController extends Controller
{
    // show custom profile index
    public function customerProfileIndex()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->back();
        } else {
            $logged_id = Auth::user()->id;
            $customer = Customer::find($logged_id);
            return view('shop::customer_profile.index', compact('customer'));
        }
    }
}
