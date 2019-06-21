<?php

namespace Webkul\CustomerCustomizer\Http\Controllers;

use Webkul\CustomerCustomizer\Http\Controllers\Controller;

use Company;

/**
 * Customer Approval controller
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CustomerApprovalController extends Controller
{
    protected $_config;

    public function __construct()
    {
        $this->_config = request('_config');
    }

    public function index()
    {

    }

    public function approve()
    {
        dd('approving the newly created customer');
    }
}