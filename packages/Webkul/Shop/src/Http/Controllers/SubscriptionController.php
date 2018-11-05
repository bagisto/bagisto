<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Shop\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Subscription controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SubscriptionController extends Controller
{

    /**
     * User object
     *
     * @var array
     */
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = auth()->guard('customer')->user();

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function index($email)
    {
        $this->validate(request(), [
            'email' => 'email'
        ]);


    }
}
