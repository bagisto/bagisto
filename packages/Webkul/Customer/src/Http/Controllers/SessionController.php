<?php

namespace Webkul\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Http\Listeners\CustomerEventsHandler;
use Illuminate\Support\Facades\Event;
use Cookie;

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

        $subscriber = new CustomerEventsHandler;

        Event::subscribe($subscriber);

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

        //Event passed to prepare cart after login
        if(Cookie::has('session_c')) {
            Event::fire('customer.after.login', $request->input('email'));
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