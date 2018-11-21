<?php

namespace Webkul\API\Http\Controllers\Customer;

use Webkul\API\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Auth;
use Cart;

/**
 * Customer controller for the APIs of Profile customer
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CustomerController extends Controller
{
    protected $customer;

    public function __construct()
    {
        if(auth()->guard('customer')->check()) {
            $this->customer = auth()->guard('customer')->user();
        } else {
            return response()->json('Unauthorized', 401);
        }
    }

    /**
     * To get the details of user to display on profile
     *
     * @return response JSON
     */
    public function getProfile() {
        return $this->customer;
    }
}