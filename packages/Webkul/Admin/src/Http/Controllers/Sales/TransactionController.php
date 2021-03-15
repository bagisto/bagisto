<?php

namespace Webkul\Admin\Http\Controllers\Sales;

use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sales\Repositories\OrderTransactionRepository;

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
     * @var \Webkul\Sales\Repositories\OrderTransactionRepository
     */
    protected $orderTransactionRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderTransactionRepository  $orderTransactionRepository
     * @return void
     */
    public function __construct(OrderTransactionRepository $orderTransactionRepository)
    {
        $this->middleware('admin');

        $this->_config = request('_config');

        $this->orderTransactionRepository = $orderTransactionRepository;
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