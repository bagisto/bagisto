<?php

namespace Webkul\Admin\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Payment\Facades\Payment;

use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;
use Webkul\Sales\Repositories\InvoiceRepository;

class TransactionController extends Controller
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
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * OrderRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderTransactionRepository
     */
    protected $orderTransactionRepository;

    /**
     * InvoiceRepository object
     *
     * @var \Webkul\Sales\Repositories\InvoiceRepository
     */
    protected $invoiceRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\OrderTransactionRepository  $orderTransactionRepository
     * @param  \Webkul\Sales\Repositories\InvoiceRepository  $invoiceRepository
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderTransactionRepository $orderTransactionRepository,
        InvoiceRepository $invoiceRepository)
    {
        $this->middleware('admin');

        $this->_config = request('_config');

        $this->orderRepository = $orderRepository;

        $this->orderTransactionRepository = $orderTransactionRepository;

        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Display a form to save the tranaction.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $payment_methods = Payment::getSupportedPaymentMethods();
        return view($this->_config['view'], compact('payment_methods'));
    }

    /**
     * Save the tranaction.
     *
     * @return \Illuminate\View\View
     */
    public function store(Request $request)
    {
        $validate = $this->validate(request(), [
            'invoice_id'     => 'required',
            'payment_method' => 'required',
            'amount'         => 'required|numeric'
        ]);

        $invoice = $this->invoiceRepository->where('increment_id', $request->invoice_id)->first();

        if ($invoice) {

            if ($invoice->state == 'paid') {
                session()->flash('info', trans('admin::app.sales.transactions.response.already-paid'));
                return redirect(route('admin.sales.transactions.index'));
            }

            $order = $this->orderRepository->find($invoice->id);

            $data = [
                "paidAmount" => $request->amount,
            ];

            $randomId = random_bytes(20);
            $transactionId = bin2hex($randomId);

            $transactionData['transaction_id'] = $transactionId;
            $transactionData['type']           = $request->payment_method;
            $transactionData['payment_method'] = $request->payment_method;
            $transactionData['invoice_id']     = $invoice->id;
            $transactionData['order_id']       = $invoice->order_id;
            $transactionData['status']         = 'paid';
            $transactionData['data']           = json_encode($data);

            $this->orderTransactionRepository->create($transactionData);

            if ($invoice->base_grand_total == $request->amount) {
                $this->orderRepository->updateOrderStatus($order, 'processing');
                $update = $this->invoiceRepository->updateState($invoice, "paid");
            } else {
                $this->orderRepository->updateOrderStatus($order, 'pending');
                $update = $this->invoiceRepository->updateState($invoice, "pending");
            }

            session()->flash('success', trans('admin::app.sales.transactions.response.transaction-saved'));
            return redirect(route('admin.sales.transactions.index'));

        } else {
            session()->flash('error', trans('admin::app.sales.transactions.response.invoice-missing'));
            return redirect()->back();
        }
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function view($id)
    {
        $transaction = $this->orderTransactionRepository->findOrFail($id);

        $transData = json_decode(json_encode(json_decode($transaction['data'])), true);

        $transactionDeatilsData = $this->convertIntoSingleDimArray($transData);

        return view($this->_config['view'], compact('transaction', 'transactionDeatilsData'));
    }

    /**
     * Convert Transaction Details Data into single Dim Array.
     *
     * @param array $data
     * @return array
    */
    public function convertIntoSingleDimArray($transData) {
        static $detailsData = [];

        foreach ($transData as $key => $data) {
            if (is_array($data)) {
                $this->convertIntoSingleDimArray($data);
            } else {
                $skipAttributes = ['sku', 'name', 'category', 'quantity'];

                if (gettype($key) == 'integer' || in_array($key, $skipAttributes)) {
                    continue;
                }

                $detailsData[$key] = $data;
            }
        }

        return $detailsData;
    }
}