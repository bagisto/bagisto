<?php

namespace Webkul\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Dashboard controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CustomerController extends Controller
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
    public function index()
    {
        return view('customer::login.index');
    }

    public function login()
    {
        return view($this->_config['view']);
    }

    public function signup()
    {
        return view($this->_config['view']);
    }
}
