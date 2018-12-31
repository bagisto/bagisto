<?php

namespace Webkul\Admin\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sales\Repositories\OrderRepository as Order;
use Webkul\Sales\Repositories\ShipmentRepository as Shipment;

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
     * OrderRepository object
     *
     * @var array
     */
    protected $order;

    /**
     * ShipmentRepository object
     *
     * @var array
     */
    protected $shipment;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Sales\Repositories\OrderRepository     $order
     * @param  Webkul\Sales\Repositories\ShipmentRepository  $shipment
     * @return void
     */
    public function __construct(Shipment $shipment, Order $order)
    {
        $this->middleware('admin');

        $this->_config = request('_config');

        $this->order = $order;

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

        if(!$order->canShip()) {
            session()->flash('error', 'Order shipment creation is not allowed.');

            return redirect()->back();
        }

        $this->validate(request(), [
            'shipment.carrier_title' => 'required',
            'shipment.track_number' => 'required',
            'shipment.items.*' => 'required|numeric|min:0',
        ]);

        $data = request()->all();
        
        $haveProductToShip = false;
        foreach ($data['shipment']['items'] as $itemId => $qty) {
            if($qty) {
                $haveProductToShip = true;
                break;
            }
        }

        if(!$haveProductToShip) {
            session()->flash('error', 'Shipment can not be created without products.');

            return redirect()->back();
        }

        $this->shipment->create(array_merge($data, ['order_id' => $orderId]));

        session()->flash('success', 'Shipment created successfully.');

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
        $shipment = $this->shipment->find($id);

        return view($this->_config['view'], compact('shipment'));
    }
}
