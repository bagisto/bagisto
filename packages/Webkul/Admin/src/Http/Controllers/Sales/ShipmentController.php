<?php

namespace Webkul\Admin\Http\Controllers\Sales;

use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Sales\OrderShipmentDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Product\Contracts\Product;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\ShipmentRepository;

class ShipmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderItemRepository $orderItemRepository,
        protected ShipmentRepository $shipmentRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(OrderShipmentDataGrid::class)->process();
        }

        return view('admin::sales.shipments.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(int $orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);

        if (
            ! $order->channel
            || ! $order->canShip()
        ) {
            session()->flash('error', trans('admin::app.sales.shipments.create.creation-error'));

            return redirect()->back();
        }

        return view('admin::sales.shipments.create', compact('order'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(int $orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);

        if (! $order->canShip()) {
            session()->flash('error', trans('admin::app.sales.shipments.create.order-error'));

            return redirect()->back();
        }

        $this->validate(request(), [
            'shipment.source' => ['required', Rule::in($order->channel?->inventory_sources->pluck('id')->all() ?? [])],
            'shipment.items.*.*' => 'required|numeric|min:0',
        ]);

        $data = request()->only(['shipment', 'carrier_name']);

        if (! $order->ownsItems(array_keys((array) ($data['shipment']['items'] ?? [])))) {
            session()->flash('error', trans('admin::app.sales.shipments.create.quantity-invalid'));

            return redirect()->back();
        }

        if (! $this->isInventoryValidate($data)) {
            session()->flash('error', trans('admin::app.sales.shipments.create.quantity-invalid'));

            return redirect()->back();
        }

        $this->shipmentRepository->create(array_merge($data, [
            'order_id' => $orderId,
        ]));

        session()->flash('success', trans('admin::app.sales.shipments.create.success'));

        return redirect()->route('admin.sales.orders.view', $orderId);
    }

    /**
     * Checks if requested quantity available or not.
     *
     * @param  array  $data
     * @return bool
     */
    public function isInventoryValidate(&$data)
    {
        if (! isset($data['shipment']['items'])) {
            return;
        }

        $valid = false;

        $inventorySourceId = $data['shipment']['source'];

        foreach ($data['shipment']['items'] as $itemId => $inventorySource) {
            $qty = $inventorySource[$inventorySourceId];

            if ((int) $qty) {
                $orderItem = $this->orderItemRepository->find($itemId);

                if ($orderItem->qty_to_ship < $qty) {
                    return false;
                }

                if ($orderItem->getTypeInstance()->isComposite()) {
                    foreach ($orderItem->children as $child) {
                        if (! $child->qty_ordered) {
                            continue;
                        }

                        $finalQty = ($child->qty_ordered / $orderItem->qty_ordered) * $qty;

                        if (
                            $child->qty_to_ship < $finalQty
                            || ! $this->hasStockToShip($child->product, $inventorySourceId, $finalQty)
                        ) {
                            return false;
                        }
                    }
                } elseif (! $this->hasStockToShip($orderItem->product, $inventorySourceId, $qty)) {
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
     * @return View
     */
    public function view(int $id)
    {
        $shipment = $this->shipmentRepository->findOrFail($id);

        return view('admin::sales.shipments.view', compact('shipment'));
    }

    /**
     * Return true if the source holds the quantity, or the product does not manage its stock.
     */
    protected function hasStockToShip(Product $product, int $inventorySourceId, float $qty): bool
    {
        if (! $product->manage_stock) {
            return true;
        }

        return $product->inventory_source_qty($inventorySourceId) >= $qty;
    }
}
