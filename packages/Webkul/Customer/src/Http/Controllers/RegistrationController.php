<?php

namespace Webkul\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Webkul\Customer\Repositories\CustomerRepository;

/**
 * Registration controller
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
    protected $customer;


    public function __construct(CustomerRepository $customer)
    {
        $this->_config = request('_config');
        $this->customer = $customer;
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

        $request->validate([

            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'email' => 'email|required',
            'password' => 'confirmed|min:6|required'

        ]);

        $registrationData = $request->except('_token');

        if ($this->customer->create($registrationData)) {

            session()->flash('success', 'Account created successfully.');

            return redirect()->route($this->_config['redirect']);

        } else {

            session()->flash('error', 'Cannot Create Your Account.');

            return redirect()->back();

        }
    }
}
