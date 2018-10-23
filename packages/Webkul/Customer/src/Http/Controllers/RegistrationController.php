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
     * Opens up the
     * user's sign up
     * form.
     *
     * @return view
     */
    public function show()
    {
        return view($this->_config['view']);
    }

    /**
     * Method to store
     * user's sign up
     * form data to DB
     *
     * @return Mixed
     */
    public function create(Request $request)
    {
        $request->validate([
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'email' => 'email|required',
            'password' => 'confirmed|min:6|required',
            'agreement' => 'required'
        ]);

        $data = request()->input();

        $data['password'] = bcrypt($data['password']);

        $data['channel_id'] = core()->getCurrentChannel()->id;

        if ($this->customer->create($data)) {

            session()->flash('success', 'Account created successfully.');

            return redirect()->route($this->_config['redirect']);

        } else {
            session()->flash('error', 'Cannot Create Your Account.');

            return redirect()->back();
        }
    }
}
