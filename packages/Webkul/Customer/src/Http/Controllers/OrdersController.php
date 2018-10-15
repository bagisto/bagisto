<?php

namespace Webkul\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Customer\Repositories\CustomerRepository;
use Auth;

/**
 * Customer controlller for the customer
 * basically for the tasks of customers
 * which will be done after customer
 * authenticastion.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrdersController extends Controller
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
        $this->middleware('customer');

        $this->_config = request('_config');

        $this->customer = $customer;
    }

    /**
     * For taking the customer
     * to the dashboard after
     * authentication
     * @return view
     */
    private function getCustomer($id) {
        $customer = collect($this->customer->findOneWhere(['id'=>$id]));
        return $customer;
    }

    public function index() {
        $id = auth()->guard('customer')->user()->id;

        $customer = $this->getCustomer($id);

        return view($this->_config['view'])->with('customer', $customer);
    }

    public function orders() {

        $id = auth()->guard('customer')->user()->id;

        $order = $this->customer->with('customerOrder')->findOneWhere(['id'=>$id]);

        return view($this->_config['view'],compact('order'));
    }
}


