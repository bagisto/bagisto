<?php

namespace Webkul\API\Http\Controllers\Customer;

use Webkul\API\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Http\Listeners\CustomerEventsHandler;
use Cart;

/**
 * Session controller for the APIs of user customer
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    public function __construct()
    {

        $this->middleware('customer')->except(['show','create']);
        $this->_config = request('_config');

        $subscriber = new CustomerEventsHandler;

        Event::subscribe($subscriber);
    }

    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!auth()->guard('customer')->attempt(request(['email', 'password']))) {
            return response()->json([false], 200);
        }

        if(auth()->guard('customer')->check()) {
            $customer = auth()->guard('customer')->user();

            $token = $customer->createToken('customer-token')->accessToken;

            return response()->json([$token], 200);
        } else {
            return response()->json([false], 200);
        }
    }

    public function destroy($id)
    {
        auth()->guard('customer')->logout();

        Event::fire('customer.after.logout', $id);

        return redirect()->route($this->_config['redirect']);
    }
}