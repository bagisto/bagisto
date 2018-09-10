<?php

namespace Webkul\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Webkul\Customer\Models\Customer;

/**
 * Session controller for the user customer
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SessionController extends Controller
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

    }

    public function show()
    {
        if(auth()->guard('customer')->check()) {
            return redirect()->route('customer.account.index');
        } else {
            return view($this->_config['view']);
        }
    }

    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!auth()->guard('customer')->attempt(request(['email', 'password']))) {

            session()->flash('error', 'Please check your credentials and try again.');
            return back();
        }

        $cookieProducts = unserialize(Cookie::get('session_c'));

        if(isset($cookieProducts)){
            return redirect()->action('Cart\Http\Controllers\CartController@add', [$cookieProducts]);
        } else {
            return redirect()->intended(route($this->_config['redirect']));
        }

    }

    public function destroy($id)
    {
        auth()->guard('customer')->logout();
        return redirect()->route($this->_config['redirect']);
    }
}