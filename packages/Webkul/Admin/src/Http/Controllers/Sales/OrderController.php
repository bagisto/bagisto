<?php

namespace Webkul\Admin\Http\Controllers\Sales;

use Illuminate\Support\Facades\Event;
use Webkul\Admin\DataGrids\OrderDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sales\Repositories\OrderRepository;
use \Webkul\Sales\Repositories\OrderCommentRepository;

class OrderController extends Controller
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
     * OrderCommentRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderCommentRepository
     */
    protected $orderCommentRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\OrderCommentRepository  $orderCommentRepository
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderCommentRepository $orderCommentRepository
    )
    {
        $this->middleware('admin');

        $this->_config = request('_config');

        $this->orderRepository = $orderRepository;

        $this->orderCommentRepository = $orderCommentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(OrderDataGrid::class)->toJson();
        }

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
        $order = $this->orderRepository->findOrFail($id);

        return view($this->_config['view'], compact('order'));
    }

    /**
     * Cancel action for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $result = $this->orderRepository->cancel($id);

        if ($result) {
            session()->flash('success', trans('admin::app.response.cancel-success', ['name' => 'Order']));
        } else {
            session()->flash('error', trans('admin::app.response.cancel-error', ['name' => 'Order']));
        }

        return redirect()->back();
    }

    /**
     * Add comment to the order
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function comment($id)
    {
        $data = array_merge(request()->all(), [
            'order_id' => $id,
        ]);

        $data['customer_notified'] = isset($data['customer_notified']) ? 1 : 0;

        Event::dispatch('sales.order.comment.create.before', $data);

        $comment = $this->orderCommentRepository->create($data);

        Event::dispatch('sales.order.comment.create.after', $comment);

        session()->flash('success', trans('admin::app.sales.orders.comment-added-success'));

        return redirect()->back();
    }
}