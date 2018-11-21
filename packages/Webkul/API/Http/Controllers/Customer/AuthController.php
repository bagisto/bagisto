<?php

namespace Webkul\API\Http\Controllers\Customer;

use Webkul\API\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
// use Webkul\Customer\Http\Listeners\CustomerEventsHandler;
use Auth;
use Cart;

/**
 * Session controller for the APIs of user customer
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AuthController extends Controller
{
    public function __construct()
    {
        // $this->middleware('customer')->except(['show','create']);
        // $this->_config = request('_config');
        // $subscriber = new CustomerEventsHandler;
        // Event::subscribe($subscriber);
    }

    /**
     * To get the details of user to display on profile
     *
     * @return response JSON
     */
    public function create() {
        $data = request()->except('_token');

        if(!auth()->guard('customer')->attempt($data)) {
            return response()->json('Incorrect Credentials', 200);
        } else {
            return response()->json(auth()->guard('customer')->user(), 200);
        }
    }
}