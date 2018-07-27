<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Admin user session controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class HomeController extends controller
{
    protected $_config;

    public function __construct()
    {
        $this->_config = request('_config');

    }
    public function index(){
        return view($this->_config['view']);
    }
}
