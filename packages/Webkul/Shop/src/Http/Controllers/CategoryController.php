<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Products Category controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class CategoryController extends controller
{

    public function __construct()
    {
        $this->_config = request('_config');
    }
    public function index()
    {
        return view($this->_config['view']);
    }
}
