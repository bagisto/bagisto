<?php

namespace Webkul\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Auth;
use PDF;

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
     * InvoiceRepository object
     *
     * @var array
     */
    protected $invoice;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Order\Repositories\OrderRepository   $order
     * @param  \Webkul\Order\Repositories\InvoiceRepository $invoice
     * @return void
     */
    public function __construct(
        OrderRepository $order,
        InvoiceRepository $invoice
    )
    {
        $this->middleware('customer');

        $this->_config = request('_config');

        $this->order = $order;

        $this->invoice = $invoice;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $orders = auth()->guard('customer')->user()->all_orders;

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
        $orders = auth()->guard('customer')->user()->all_orders;

        if (isset($orders) && count($orders)) {
            foreach ($orders as $order) {
                if ($order->id  == $id) {
                    return view($this->_config['view'], compact('order'));
                }
            }
        }

        return redirect()->route( 'customer.orders.index');
    }

    /**
     * Print and download the for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $invoice = $this->invoice->find($id);

        $pdf = PDF::loadView('shop::customers.account.orders.pdf', compact('invoice'))->setPaper('a4');

        return $pdf->download('invoice-' . $invoice->created_at->format('d-m-Y') . '.pdf');
    }
}
