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
    /**
     * To get the details of user to display on profile
     *
     * @return response JSON
     */
    public function create() {
        $data = request()->except('_token');

        if (! auth()->guard('customer')->check()) {
            if (! auth()->guard('customer')->attempt($data)) {
                return response()->json(['message' => 'unauthenticated', 'details' => 'invalid creds'], 401);
            } else {
                return response()->json(['message' => 'authenticated', 'user' => auth()->guard('customer')->user()], 200);
            }
        } else {
            return response()->json(['message' => 'already authenticated'], 200);
        }
    }

    public function destroy() {
        auth()->guard('customer')->logout();
        return response()->json(['message' => 'logged out'], 200);
    }
}