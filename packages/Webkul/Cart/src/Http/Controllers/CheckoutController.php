<?php

namespace Webkul\Cart\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;

/**
 * Chekout controller for the customer
 * and guest for placing order
 *
 * @author    Rahul Shukla <rahulshukla.symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CheckoutController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    public function __construct()
    {
        // $this->middleware(['customer', 'guest']);

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $customer_id = auth()->guard('customer')->user();

        return view($this->_config['view'],compact('customer_id'));
    }

}