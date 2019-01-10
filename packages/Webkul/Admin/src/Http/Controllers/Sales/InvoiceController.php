<?php

namespace Webkul\Admin\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sales\Repositories\OrderRepository as Order;
use Webkul\Sales\Repositories\InvoiceRepository as Invoice;
use PDF;

/**
 * Sales Invoice controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * OrderRepository object
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
     * @param  Webkul\Sales\Repositories\OrderRepository   $order
     * @param  Webkul\Sales\Repositories\InvoiceRepository $invoice
     * @return void
     */
    public function __construct(Invoice $invoice, Order $order)
    {
        $this->middleware('admin');

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
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $orderId
     * @return \Illuminate\Http\Response
     */
    public function create($orderId)
    {
        $order = $this->order->find($orderId);

        return view($this->_config['view'], compact('order'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param int $orderId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $orderId)
    {
        $order = $this->order->find($orderId);

        if(!$order->canInvoice()) {
            session()->flash('error', 'Order invoice creation is not allowed.');

            return redirect()->back();
        }

        $this->validate(request(), [
            'invoice.items.*' => 'required|numeric|min:0',
        ]);

        $data = request()->all();
        
        $haveProductToInvoice = false;
        foreach ($data['invoice']['items'] as $itemId => $qty) {
            if($qty) {
                $haveProductToInvoice = true;
                break;
            }
        }

        if(!$haveProductToInvoice) {
            session()->flash('error', 'Invoice can not be created without products.');

            return redirect()->back();
        }

        $this->invoice->create(array_merge($data, ['order_id' => $orderId]));

        session()->flash('success', 'Invoice created successfully.');

        return redirect()->route($this->_config['redirect'], $orderId);
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $invoice = $this->invoice->find($id);

        return view($this->_config['view'], compact('invoice'));
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

        $pdf = PDF::loadView('admin::sales.invoices.pdf', compact('invoice'))->setPaper('a4');

        return $pdf->download('invoice-' . $invoice->created_at->format('d-m-Y') . '.pdf');
    }
}
