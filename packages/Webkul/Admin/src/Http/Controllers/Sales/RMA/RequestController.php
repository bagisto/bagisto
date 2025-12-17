<?php

namespace Webkul\Admin\Http\Controllers\Sales\RMA;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Sales\RMA\OrderRMADataGrid;
use Webkul\Admin\DataGrids\Sales\RMA\RMADataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\RMA\Contracts\RMAReasonResolution;
use Webkul\RMA\Enums\DefaultRMAStatusEnum;
use Webkul\RMA\Helpers\Helper as RMAHelper;
use Webkul\RMA\Repositories\RMAAdditionalFieldRepository;
use Webkul\RMA\Repositories\RMAImageRepository;
use Webkul\RMA\Repositories\RMAItemRepository;
use Webkul\RMA\Repositories\RMAMessageRepository;
use Webkul\RMA\Repositories\RMAReasonRepository;
use Webkul\RMA\Repositories\RMAReasonResolutionRepository;
use Webkul\RMA\Repositories\RMARepository;
use Webkul\RMA\Repositories\RMAStatusRepository;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\RefundRepository;
use Webkul\Shop\Mail\Customer\RMA\AdminToCustomerConversationNotification;
use Webkul\Shop\Mail\Customer\RMA\CustomerRMARequestNotification;
use Webkul\Shop\Mail\Customer\RMA\CustomerRMAStatusNotification;

class RequestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderItemRepository $orderItemRepository,
        protected OrderRepository $orderRepository,
        protected RefundRepository $refundRepository,
        protected RMAAdditionalFieldRepository $rmaAdditionalFieldRepository,
        protected RMAHelper $rmaHelper,
        protected RMAImageRepository $rmaImageRepository,
        protected RMAItemRepository $rmaItemRepository,
        protected RMAMessageRepository $rmaMessageRepository,
        protected RMAReasonRepository $rmaReasonRepository,
        protected RMAReasonResolutionRepository $rmaReasonResolutionRepository,
        protected RMARepository $rmaRepository,
        protected RMAStatusRepository $rmaStatusRepository,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(RMADataGrid::class)->process();
        }

        return view('admin::sales.rma.returns.index');
    }

    /**
     * Show the view for the specified resource.
     */
    public function view(int $rmaId): View|RedirectResponse
    {
        $rma = $this->rmaRepository->with(['items', 'order'])->findOrFail($rmaId);

        $statusArray = $this->rmaStatusForRequest($rma);

        return view('admin::sales.rma.returns.view', compact('rma', 'statusArray'));
    }

    /**
     * Save rma status by admin
     */
    public function saveRmaStatus(int $id): RedirectResponse
    {
        $data = request()->input();

        $rma = $this->rmaRepository->findOrFail($id);
        $order = $rma->order;
        
        $ordersRma = $this->rmaRepository->findWhere(['order_id' => $order->id]);

        $totalCount = (int) $this->rmaItemRepository
            ->whereIn('rma_id', $ordersRma->pluck('id'))
            ->sum('quantity');
        
        if (empty($totalCount)) {
            return $this->updateRmaAndRedirect($rma, $id, $data);
        }
        
        $statusId = (int) $data['rma_status_id'];
        
        if ($statusId === DefaultRMAStatusEnum::ITEM_CANCELED->value) {
            $this->handleItemCancellation($ordersRma, $order, $totalCount);
        }
        
        if ($statusId === DefaultRMAStatusEnum::RECEIVED_PACKAGE->value) {
            return $this->handleReceivedPackage($rma, $data);
        }
        
        return $this->updateRmaAndRedirect($rma, $id, $data);
    }

    /**
     * Save rma status by customer
     */
    public function saveReOpenStatus(int $id): RedirectResponse
    {
        $data = request()->only(['close_rma']);

        $rma = $this->rmaRepository->findOrFail($id);

        if (! empty($data['close_rma'])) {
            $order = $this->orderRepository->find($rma->order_id);

            $order->update(['status' => Order::STATUS_PENDING]);

            $rma->update(['rma_status_id' => DefaultRMAStatusEnum::PENDING->value]);

            $this->rmaMessageRepository->create([
                'message'    => trans('admin::app.sales.rma.all-rma.view.conversation-process'),
                'rma_id'     => $id,
                'is_admin'   => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            try {
                Mail::queue(new CustomerRMAStatusNotification($rma));
            } catch (\Exception $e) {}
        }

        session()->flash('success', trans('admin::app.sales.rma.all-rma.view.update-success'));

        return back();
    }

    /**
     * Get all Messages
     */
    public function getMessages() : JsonResponse
    {
        $messages = $this->rmaMessageRepository->where('rma_id', request()->get('id'))
            ->orderBy('id', 'desc')
            ->paginate(request()->get('limit') ?? 5);

        return new JsonResponse([
            'messages' => $messages,
        ]);
    }

    /**
     * Send message from admin to customer
     */
    public function sendMessage(): JsonResponse
    {
        $requestData = request()->input();

        $storedMessage = $this->rmaMessageRepository->create($requestData);

        if (! empty($storedMessage)) {
            $removedKeys = explode(',', request()->input('removed_key'));

            array_shift($removedKeys);

            if (! empty(request()->file('file'))) {
                $file = request()->file('file');

                $filename = $file->getClientOriginalName();

                $path = $file->storeAs('rma-conversation/'.$storedMessage->id, $filename);

                $this->rmaMessageRepository->update([
                    'attachment_path' => $path,
                    'attachment'      => $filename,
                ], $storedMessage->id);
            }

            try {
                Mail::queue(new AdminToCustomerConversationNotification($storedMessage));
            } catch (\Exception $e) {}

            return new JsonResponse([
                'message' => trans('admin::app.sales.rma.all-rma.view.send-message-success'),
            ]);
        }

        return new JsonResponse([
            'message' => trans('admin::app.sales.rma.all-rma.view.failed'),
        ]);
    }

    /**
     * Display the RMA creation form.
     */
    public function create(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(OrderRMADataGrid::class)->process();
        }

        return view('admin::sales.rma.returns.create');
    }

    /**
     * Store RMA created by admin.
     */
    public function store(): RedirectResponse|JsonResponse
    {
        $this->validate(request(), [
            'order_id'        => 'required|exists:orders,id',
            'order_item_id'   => 'required|array|min:1',
            'rma_qty'         => 'required|array|min:1',
            'resolution_type' => 'required|array|min:1',
            'rma_reason_id'   => 'required|array|min:1',
            'information'     => 'nullable|string',
            'images'          => 'nullable|array|min:1',
            'images.*'        => 'nullable|file|mimetypes:'.core()->getConfigData('sales.rma.setting.allowed_file_extension'),
        ]);

        $data = request()->only([
            'order_id',
            'order_item_id',
            'order_status',
            'variant',
            'rma_qty',
            'resolution_type',
            'rma_reason_id',
            'information',
            'images',
            'package_condition',
        ]);

        /**
         * Creation of a new RMA record.
         */
        $rma = $this->rmaRepository->create([
            'order_id'          => $data['order_id'],
            'order_status'      => $data['order_status'] ?? null,
            'rma_status_id'     => DefaultRMAStatusEnum::PENDING->value,
            'information'       => $data['information'] ?? null,
            'package_condition' => $data['package_condition'] ?? null,
        ]);

        /**
         * Creation of RMA items for the newly created RMA record.
         */
        foreach ($data['order_item_id'] as $key => $orderItemId) {  
            $this->rmaItemRepository->create([
                'rma_id'        => $rma->id,
                'rma_reason_id' => $data['rma_reason_id'][$key],
                'order_item_id' => $orderItemId,
                'variant_id'    => ! empty($data['variant'][$key]) ? $data['variant'][$key] : null,
                'quantity'      => $data['rma_qty'][$key],
                'resolution'    => $data['resolution_type'][$key],
            ]);
        }

        /**
         * Initial message indicating the processing of the RMA request.
         */
        $this->rmaMessageRepository->create([
            'rma_id'     => $rma->id,
            'message'    => trans('shop::app.rma.mail.customer-conversation.process'),
            'is_admin'   => 1,
        ]);

        /**
         * Creation of RMA images for the newly created RMA record.
         */
        if (
            ! empty($data['images']) 
            && ! empty(implode(',', $data['images']))
        ) {
            $this->rmaImageRepository->manageImages($data['images'], $rma);
        }

        /**
         * Creation of additional fields for the newly created RMA record.
         */
        $customAttributes = request('customAttributes', []);

        if (! empty($customAttributes)) {
            $this->rmaAdditionalFieldRepository->createManyForRma($rma->id, $customAttributes);
        }

        /**
         * Sending RMA creation email to the customer.
         */
        if ($rma->items) {
            try {
                Mail::queue(new CustomerRMARequestNotification($rma));
            } catch (\Exception $e) {}

            return response()->json([
                'messages' => trans('admin::app.sales.rma.create-rma.create-success'),
            ]);
        }

        return response()->json([
            'messages' => trans('admin::app.sales.rma.create-rma.failed'),
        ]);
    }

    /**
     * Get order details
     */
    public function getOrderProduct(int $orderId) : Collection
    {
        return $this->rmaHelper->getOrderProduct($orderId);
    }

    /**
     * Get RMA reason by resolution type.
     */
    public function getResolutionReason(string $resolutionType): RMAReasonResolution|Collection
    {
        return $this->rmaReasonRepository->getRMAReasonsByResolutionType($resolutionType);
    }

    /**
     * Get RMA status for request.
     */
    public function rmaStatusForRequest($rma): array
    {
        $activeStatusIds = $this->rmaStatusRepository
            ->where('status', 1)
            ->pluck('id');

        if ($rma->rma_status_id === DefaultRMAStatusEnum::PENDING->value) {
            return $this->rmaStatusRepository
                ->whereIn('id', $activeStatusIds->intersect([
                    DefaultRMAStatusEnum::ACCEPT->value,
                    DefaultRMAStatusEnum::DECLINED->value,
                ]))
                ->pluck('title', 'id')
                ->toArray();
        }

        $hasCancel = $rma->items->contains('resolution', 'cancel-items');

        $excludedStatuses = $hasCancel
            ? [DefaultRMAStatusEnum::ACCEPT->value, DefaultRMAStatusEnum::DECLINED->value, DefaultRMAStatusEnum::PENDING->value, DefaultRMAStatusEnum::DISPATCHED_PACKAGE->value, DefaultRMAStatusEnum::RECEIVED_PACKAGE->value, DefaultRMAStatusEnum::SOLVED->value]
            : [DefaultRMAStatusEnum::ITEM_CANCELED->value, DefaultRMAStatusEnum::ACCEPT->value, DefaultRMAStatusEnum::DECLINED->value, DefaultRMAStatusEnum::PENDING->value, DefaultRMAStatusEnum::SOLVED->value];

        return $this->rmaStatusRepository
            ->whereIn('id', $activeStatusIds->diff($excludedStatuses))
            ->pluck('title', 'id')
            ->toArray();
    }

    /**
     * Create refund for the RMA.
     *
     * @param  \Webkul\RMA\Contracts\RMA  $rma
     * @return \Webkul\Sales\Contracts\Refund
     */
    public function createRefund($rma)
    {
        $requestData = request()->all();

        $orderId = $rma->order_id;

        $items = collect($rma->items)
            ->mapWithKeys(function ($rmaItem) {
                $orderItem = $rmaItem->orderItem;

                return [$rmaItem->order_item_id => ($orderItem->qty_invoiced - $orderItem->qty_refunded)];
            });

        $data = [
            'refund' => [
                'shipping'          => $requestData['shipping'] ?? 0,
                'adjustment_refund' => 0,
                'adjustment_fee'    => 0,
            ],
        ];

        foreach ($items as $key => $value) {
            $data['refund']['items'][$key] = $value;
        }

        $order = $this->orderRepository->findOrFail($orderId);

        if (! $order->canRefund()) {
            session()->flash('error', trans('admin::app.sales.rma.all-rma.view.refund-creation-error'));

            return redirect()->back();
        }

        $totals = $this->refundRepository->getOrderItemsRefundSummary($data['refund'], $orderId);

        if (! $totals) {
            session()->flash('error', trans('admin::app.sales.refunds.create.invalid-qty'));

            return redirect()->back();
        }

        $maxRefundAmount = $totals['grand_total']['price'] - $order->refunds()->sum('base_adjustment_refund');

        $refundAmount = $totals['grand_total']['price'] - $totals['shipping']['price'] + $data['refund']['shipping'] + $data['refund']['adjustment_refund'] - $data['refund']['adjustment_fee'];

        if (! $refundAmount) {
            session()->flash('error', trans('admin::app.sales.refunds.create.invalid-refund-amount-error'));

            return redirect()->back();
        }

        if ($refundAmount > $maxRefundAmount) {
            session()->flash('error', trans('admin::app.sales.refunds.create.refund-limit-error', ['amount' => core()->formatBasePrice($maxRefundAmount)]));

            return redirect()->back();
        }

        Event::dispatch('sales.refund.create.before', [$order, core()->convertPrice($refundAmount, $order->order_currency_code)]);

        $refund = $this->refundRepository->create(array_merge($data, ['order_id' => $orderId]));

        return $refund;
    }

    /**
     * Update order status.
     */
    public function updateOrderStatus(\Webkul\Sales\Contracts\Order $order, ?string $orderState = null): void
    {
        Event::dispatch('sales.order.update-status.before', $order);

        if (! empty($orderState)) {
            $status = $orderState;
        } else {
            if ($this->orderRepository->isInCompletedState($order)) {
                $status = Order::STATUS_COMPLETED;
            }

            if ($this->orderRepository->isInCanceledState($order)) {
                $status = Order::STATUS_CANCELED;
            } elseif ($this->orderRepository->isInClosedState($order)) {
                $status = Order::STATUS_CLOSED;
            }
        }

        if (! empty($status)) {
            $order->status = $status;
        }

        $order->save();

        Event::dispatch('sales.order.update-status.after', $order);
    }

    /**
     * Handle item cancellation status update.
     */
    private function handleItemCancellation($ordersRma, $order, int $totalCount): void
    {
        $orderItemIds = $ordersRma->flatMap(fn($orderRma) => 
            $orderRma->items->pluck('order_item_id')
        );
        
        $orderItems = $this->orderItemRepository
            ->whereIn('id', $orderItemIds)
            ->orWhereIn('parent_id', $orderItemIds)
            ->get()
            ->keyBy('id');
        
        foreach ($ordersRma as $orderRma) {
            foreach ($orderRma->items as $rmaItem) {
                $orderItem = $orderItems->get($rmaItem->order_item_id);
                
                if (! $orderItem) {
                    continue;
                }
                
                $itemToUpdate = $orderItem->parent_id 
                    ? $orderItems->get($orderItem->parent_id) 
                    : $orderItem;
                
                if ($itemToUpdate) {
                    $itemToUpdate->update([
                        'qty_canceled' => $rmaItem->quantity,
                    ]);
                }
            }
        }
        
        if ($order->total_qty_ordered == $totalCount) {
            $order->update(['status' => Order::STATUS_CANCELED]);
        } else {
            $this->updateOrderStatus($order);
        }
        
        Event::dispatch('sales.order.cancel.after', $order);
    }

    /**
     * Handle received package status update.
     */
    private function handleReceivedPackage($rma, array $data): RedirectResponse
    {
        $refund = $this->createRefund($rma);
        
        if (! $refund) {
            session()->flash('error', trans('admin::app.sales.rma.all-rma.view.refund-failed'));

            return redirect()->back();
        }
        
        $rma->update($data);
        
        session()->flash('success', trans('admin::app.sales.refunds.create.create-success'));

        return redirect()->route('admin.sales.refunds.index');
    }

    /**
     * Update RMA and redirect with status message.
     */
    private function updateRmaAndRedirect($rma, int $id, array $data): RedirectResponse
    {
        $updateStatus = $rma->update($data);
        
        $this->rmaMessageRepository->create([
            'message'  => trans('admin::app.sales.rma.all-rma.view.status-message', [
                'id'     => $id,
                'status' => $rma->fresh()->status->title,
            ]),
            'rma_id'   => $id,
            'is_admin' => 1,
        ]);
        
        if ($updateStatus) {
            try {
                Mail::queue(new CustomerRMAStatusNotification($rma));
            } catch (\Exception $e) {}
            
            session()->flash('success', trans('admin::app.sales.rma.all-rma.view.update-success'));
        } else {
            session()->flash('error', trans('admin::app.sales.rma.all-rma.view.failed'));
        }
        
        return redirect()->back();
    }
}