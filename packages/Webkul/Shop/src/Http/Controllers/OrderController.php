<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Sales\Repositories\OrderRepository;
use Auth;

/**
 * Customer controlller for the customer basically for the tasks of customers
 * which will be done after customer authenticastion.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * OrderrRepository object
     *
     * @var array
     */
    protected $order;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Order\Repositories\OrderRepository $order
     * @return void
     */
    public function __construct(OrderRepository $order)
    {
        $this->middleware('customer');

        $this->_config = request('_config');

        $this->order = $order;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index() {
        $orders = $this->order->findWhere([
            'customer_id' => auth()->guard('customer')->user()->id
        ]);

        return view($this->_config['view'], compact('orders'));
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $order = $this->order->find($id);

        return view($this->_config['view'], compact('order'));
    }
}