<?php

namespace Webkul\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Auth;

/**
 * Account Controlller for the customers
 * basically will control the landing
 * behavior for custome and group of
 * customers.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;
    protected $customer;
    protected $address;


    public function __construct(CustomerRepository $customer, CustomerAddressRepository $address)
    {
        $this->middleware('customer');

        $this->_config = request('_config');

        $this->customer = $customer;

        $this->address = $address;
    }

    public function index() {
        return view($this->_config['view']);
    }

}
