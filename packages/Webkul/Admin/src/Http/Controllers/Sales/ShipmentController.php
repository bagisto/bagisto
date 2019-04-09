<?php

namespace Webkul\Admin\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sales\Repositories\ShipmentRepository as Shipment;
use Webkul\Sales\Repositories\OrderRepository as Order;
use Webkul\Sales\Repositories\OrderItemRepository as OrderItem;

/**
 * Sales Shipment controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * ShipmentRepository object
     *
     * @var mixed
     */
    protected $shipment;

    /**
     * OrderRepository object
     *
     * @var mixed
     */
    protected $order;

    /**
     * OrderItemRepository object
     *
     * @var mixed
     */
    protected $orderItem;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\ShipmentRepository  $shipment
     * @param  \Webkul\Sales\Repositories\OrderRepository     $order
     * @param  \Webkul\Sales\Repositories\OrderitemRepository $orderItem
     * @return void
     */
    public function __construct(
        Shipment $shipment,
        Order $order,
        OrderItem $orderItem
    )
    {
        $this->middleware('admin');

        $this->_config = request('_config');

        $this->order = $order;

        $this->orderItem = $orderItem;

        $this->shipment = $shipment;

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
        $order = $this->order->findOrFail($orderId);

        if (! $order->channel || !$order->canShip()) {
            session()->flash('error', trans('admin::app.sales.shipments.creation-error'));

            return redirect()->back();
        }

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
        $order = $this->order->findOrFail($orderId);

        if (! $order->canShip()) {
            session()->flash('error', trans('admin::app.sales.shipments.order-error'));

            return redirect()->back();
        }

        $this->validate(request(), [
            'shipment.carrier_title' => 'required',
            'shipment.track_number' => 'required',
            'shipment.source' => 'required',
            'shipment.items.*.*' => 'required|numeric|min:0',
        ]);

        $data = request()->all();

        if (! $this->isInventoryValidate($data)) {
            session()->flash('error', trans('admin::app.sales.shipments.quantity-invalid'));

            return redirect()->back();
        }

        $this->shipment->create(array_merge($data, ['order_id' => $orderId]));

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Shipment']));

        return redirect()->route($this->_config['redirect'], $orderId);
    }

    /**
     * Checks if requested quantity available or not
     *
     * @param array $data
     * @return boolean
     */
    public function isInventoryValidate(&$data)
    {
        $valid = false;

        foreach ($data['shipment']['items'] as $itemId => $inventorySource) {
            if ($qty = $inventorySource[$data['shipment']['source']]) {
                $orderItem = $this->orderItem->find($itemId);

                $product = ($orderItem->type == 'configurable')
                        ? $orderItem->child->product
                        : $orderItem->product;

                $availableQty = $product->inventories()
                        ->where('inventory_source_id', $data['shipment']['source'])
                        ->sum('qty');

                if ($orderItem->qty_to_ship < $qty || $availableQty < $qty) {
                    return false;
                }

                $valid = true;
            } else {
                unset($data['shipment']['items'][$itemId]);
            }
        }

        return $valid;
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $shipment = $this->shipment->findOrFail($id);

        return view($this->_config['view'], compact('shipment'));
    }
}
