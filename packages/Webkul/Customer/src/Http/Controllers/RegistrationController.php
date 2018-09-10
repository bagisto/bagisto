<?php

namespace Webkul\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Webkul\Customer\Models\Customer;

/**
 * Dashboard controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    public function __construct()
    {
        $this->_config = request('_config');
    }

    /**
     * For showing the registration form
     * @return view
     */
    public function show()
    {
        return view($this->_config['view']);
    }

    /**
     * For collecting the registration
     * data from the registraion form
     * @return view
     */
    public function create(Request $request)
    {
        // return $request->except('_token'); //don't let csrf token to be openly printed
        $request->validate([
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'email' => 'email|required',
            'password' => 'confirmed|min:8|required'
        ]);
        $customer = new \Webkul\Customer\Models\Customer();
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->password = bcrypt($request->password);
        // dd('hello1');
        if ($customer->save()) {
            session()->flash('success', 'Account created successfully.');
            return redirect()->route($this->_config['redirect']);
        } else {
            session()->flash('error', 'Cannot Create Your Account.');
            return redirect()->back();
        }
    }
}
