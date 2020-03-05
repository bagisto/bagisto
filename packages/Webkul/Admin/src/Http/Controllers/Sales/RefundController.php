<?php

namespace Webkul\Admin\Http\Controllers\Sales;

use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\RefundRepository;

/**
 * Sales Refund controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class RefundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @var array
     */
    protected $_config;

    /**
     * OrderRepository object
     *
     * @var Object
     */
    protected $orderRepository;

    /**
     * OrderItemRepository object
     *
     * @var Object
     */
    protected $orderItemRepository;

    /**
     * RefundRepository object
     *
     * @var Object
     */
    protected $refundRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository      $orderRepository
     * @param  \Webkul\Sales\Repositories\OrderItemRepository  $orderItemRepository
     * @param  \Webkul\Sales\Repositories\RefundRepository     $refundRepository
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository,
        RefundRepository $refundRepository
    )
    {
        $this->middleware('admin');

        $this->_config = request('_config');

        $this->orderRepository = $orderRepository;

        $this->orderItemRepository = $orderItemRepository;

        $this->refundRepository = $refundRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $orderId
     * @return \Illuminate\Http\View
     */
    public function create($orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);

        return view($this->_config['view'], compact('order'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $orderId
     * @return \Illuminate\Http\Response
     */
    public function store($orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);

        if (! $order->canRefund()) {
            session()->flash('error', trans('admin::app.sales.refunds.creation-error'));

            return redirect()->back();
        }

        $this->validate(request(), [
            'refund.items.*' => 'required|numeric|min:0',
        ]);

        $data = request()->all();

        $totals = $this->refundRepository->getOrderItemsRefundSummary($data['refund']['items'], $orderId);

        $maxRefundAmount = $totals['grand_total']['price'] - $order->refunds()->sum('base_adjustment_refund');

        $refundAmount = $totals['grand_total']['price'] - $totals['shipping']['price'] + $data['refund']['shipping'] + $data['refund']['adjustment_refund'] - $data['refund']['adjustment_fee'];

        if (! $refundAmount) {
            session()->flash('error', trans('admin::app.sales.refunds.invalid-refund-amount-error'));

            return redirect()->back();
        }

        if ($refundAmount > $maxRefundAmount) {
            session()->flash('error', trans('admin::app.sales.refunds.refund-limit-error', ['amount' => core()->formatBasePrice($maxRefundAmount)]));

            return redirect()->back();
        }

        $this->refundRepository->create(array_merge($data, ['order_id' => $orderId]));

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Refund']));

        return redirect()->route($this->_config['redirect'], $orderId);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateQty($orderId)
    {
        $data = $this->refundRepository->getOrderItemsRefundSummary(request()->all(), $orderId);

        if (! $data) {
            return response('');
        }

        return response()->json($data);
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\View
     */
    public function view($id)
    {
        $refund = $this->refundRepository->findOrFail($id);

        return view($this->_config['view'], compact('refund'));
    }
}
