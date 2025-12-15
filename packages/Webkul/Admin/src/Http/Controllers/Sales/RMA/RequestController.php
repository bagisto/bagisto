<?php

namespace Webkul\Admin\Http\Controllers\Sales\RMA;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Sales\RMA\OrderRMADataGrid;
use Webkul\Admin\DataGrids\Sales\RMA\RMADataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Mail\Admin\RMA\AdminConversationNotification;
use Webkul\RMA\Contracts\RMAReasonResolution;
use Webkul\RMA\Enums\RequestStatusEnum;
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
use Webkul\Shop\Mail\Customer\RMA\CustomerRMARequestNotification;
use Webkul\Shop\Mail\Customer\RMA\CustomerRMAStatusNotification;

class RequestController extends Controller
{
    /**
     * @var string
     */
    public const CONFIGURABLE = 'configurable';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderItemRepository $orderItemRepository,
        protected OrderRepository $orderRepository,
        protected RMAAdditionalFieldRepository $rmaAdditionalFieldRepository,
        protected RMAHelper $rmaHelper,
        protected RMAImageRepository $rmaImagesRepository,
        protected RMAItemRepository $rmaItemsRepository,
        protected RMAMessageRepository $rmaMessagesRepository,
        protected RMAReasonRepository $rmaReasonRepository,
        protected RMAReasonResolutionRepository $rmaReasonResolutionsRepository,
        protected RMARepository $rmaRepository,
        protected RefundRepository $refundRepository,
        protected RMAStatusRepository $rmaStatusRepository,
    ) {}

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
            'rma_qty'          => 'required',
            'resolution_type'  => 'required',
            'order_status'     => 'required',
            'order_item_id'    => 'required|array|min:1',
            'images.*'         => 'nullable|file|mimetypes:'.core()->getConfigData('sales.rma.setting.allowed_file_extension'),
        ]);

        $requestData = request()->only([
            'order_id',
            'resolution_type',
            'order_status',
            'order_item_id',
            'rma_qty',
            'rma_reason_id',
            'images',
            'information',
        ]);

        if (request()->input('package_condition')) {
            $requestData['package_condition'] = request()->input('package_condition');
        }

        if (request()->input('return_pickup_address')) {
            $requestData['return_pickup_address'] = request()->input('return_pickup_address');
        }

        if (request()->input('return_pickup_time')) {
            $requestData['return_pickup_time'] = request()->input('return_pickup_time');
        }

        $rma = $this->rmaRepository->create([
            'status'                => '',
            'order_id'              => $requestData['order_id'],
            'information'           => $requestData['information'] ?? null,
            'order_status'          => $requestData['order_status'],
            'request_status'        => RequestStatusEnum::PENDING->value,
            'package_condition'     => $requestData['package_condition'] ?? '',
        ]);

        $this->storeRelatedData($requestData, $rma);

        return new JsonResponse([
            'messages' => trans('admin::app.sales.rma.create-rma.create-success'),
        ]);
    }

    public function storeRelatedData($data, $rma)
    {
        $data['order_items'] = [];
        $rmaItemIds = [];

        foreach ($data['order_item_id'] as $key => $orderItemId) {
            $orderItem = $this->orderItemRepository->find($orderItemId);

            if (! empty($orderItem)) {
                array_push($data['order_items'], $orderItem);

                $rmaItem = $this->rmaItemsRepository->create([
                    'resolution'    => $data['resolution_type'][$key],
                    'rma_id'        => $rma->id,
                    'order_item_id' => $orderItemId,
                    'quantity'      => $data['rma_qty'][$key],
                    'rma_reason_id' => $data['rma_reason_id'][$key],
                    'variant_id'    => ! empty($data['variant'][$key]) ? $data['variant'][$key] : null,
                ]);

                $rmaItemIds[] = $rmaItem->id;

                $data['reason'][] = $this->rmaReasonRepository->findOneWhere(['id' => $data['rma_reason_id'][$key]])?->title;
            }
        }

        $requestData = [
            'message'    => trans('admin::app.sales.rma.create-rma.conversation-process'),
            'rma_id'     => $rma->id,
            'is_admin'   => 1,
        ];

        $this->rmaMessagesRepository->create($requestData);

        $data['rma_id'] = $rma->id;

        // insert images
        if (! empty($data['images']) && ! empty(implode(',', $data['images']))) {
            foreach ($data['images'] as $itemImg) {
                $this->rmaImagesRepository->create([
                    'rma_id'     => $rma->id,
                    'path'       => $itemImg->getClientOriginalName(),
                ]);
            }

            $this->rmaImagesRepository->uploadImages($data, $rma);
        }

        // Save custom Attributes
        $customAttributes = request('customAttributes') ?? [];

        if ($customAttributes) {

            $customAttributesData = [];

            foreach ($customAttributes as $key => $customAttribute) {
                $customAttributesData = [
                    'rma_id'  => $data['rma_id'],
                    'name'    => $key,
                    'value'   => is_array($customAttribute) ? implode(',', $customAttribute) : $customAttribute,
                ];

                $this->rmaAdditionalFieldRepository->create($customAttributesData);
            }
        }

        $order = $this->orderRepository->findOrFail($rma->order_id);

        $orderItems = $order->items->whereIn('id', $rmaItemIds);

        foreach ($orderItems as $key => $configurableProducts) {
            if ($configurableProducts['type'] == self::CONFIGURABLE) {
                $data['skus'][] = $configurableProducts['child'];
            }
        }

        $data['email'] = $order->customer_email;

        $data['name'] = $order->customer_first_name.' '.$order->customer_last_name;

        unset($data['images']);

        if ($rma->items) {
            try {
                Mail::queue(new CustomerRMARequestNotification($data));

            } catch (\Exception $e) {
                \Log::error('Error in Sending Email'.$e->getMessage());
            }

            Cookie::has('rmaData') ? setcookie('rmaData', null, -1, '/') : '';

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
    public function getOrderProduct(int $orderId)
    {
        return $this->rmaHelper->getOrderProduct($orderId);
    }

    /**
     * Get reason for resolution.
     */
    public function getResolutionReason(string $resolutionType): RMAReasonResolution|Collection
    {
        $existResolutions = $this->rmaReasonResolutionsRepository
            ->where('resolution_type', $resolutionType)
            ->pluck('rma_reason_id');

        $reasons = $this->rmaReasonRepository
            ->whereIn('id', $existResolutions)
            ->where('status', 1)
            ->get();

        return $reasons;
    }

    /**
     * RMA list
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
        $rma = $this->rmaRepository->with(['items', 'order'])->find($rmaId);

        if (! $rma) {
            return redirect()->route('admin.sales.rma.index');
        }

        $statusArr = $this->rmaStatusForRequest($rma);

        $rmaStatusColor = app('Webkul\RMA\Repositories\RMAStatusRepository')
            ->where('title', $rma->request_status)
            ->value('color') ?? '#000000';

        $order = $rma->order;

        return view('admin::sales.rma.returns.view', compact('rma', 'statusArr', 'rmaStatusColor', 'order'));
    }

    /**
     * Get rma status for request
     */
    public function rmaStatusForRequest($rma)
    {
        $activeStatuses = $this->rmaStatusRepository
            ->where('status', 1)
            ->pluck('title')
            ->toArray();

        if ($rma->request_status === RequestStatusEnum::PENDING->value) {
            return array_values(array_intersect($activeStatuses, [RequestStatusEnum::ACCEPT->value, RequestStatusEnum::DECLINED->value]));
        }

        $hasCancel = $rma->items->pluck('resolution')->contains('cancel-items');

        $excludedStatuses = $hasCancel
            ? [RequestStatusEnum::ACCEPT->value, RequestStatusEnum::DECLINED->value, RequestStatusEnum::PENDING->value, RequestStatusEnum::DISPATCHED_PACKAGE->value, RequestStatusEnum::RECEIVED_PACKAGE->value]
            : [RequestStatusEnum::ITEM_CANCELED->value, RequestStatusEnum::ACCEPT->value, RequestStatusEnum::DECLINED->value, RequestStatusEnum::PENDING->value];

        return array_values(array_diff($activeStatuses, $excludedStatuses));
    }

    /**
     * Save rma status by customer
     */
    public function saveReOpenStatus(): RedirectResponse
    {
        $data = request()->only([
            'close_rma',
            'rma_id',
        ]);

        $rma = $this->rmaRepository->findOrFail($data['rma_id']);

        if (! empty($data['close_rma'])) {
            $order = $this->orderRepository->find($rma->order_id);

            $order->update(['status' => Order::STATUS_PENDING]);

            $this->rmaRepository->find($data['rma_id'])->update([
                'status'           => 1,
                'request_status'   => RequestStatusEnum::PENDING->value,
                'status'           => 0,
                'order_status'     => 0,
            ]);

            $requestData = [
                'message'    => trans('admin::app.sales.rma.all-rma.view.conversation-process'),
                'rma_id'     => $data['rma_id'],
                'is_admin'   => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            $this->rmaMessagesRepository->create($requestData);
        }

        session()->flash('success', trans('admin::app.sales.rma.all-rma.view.update-success'));

        return back();
    }

    /**
     * Get all Messages
     */
    public function getMessages()
    {
        $messages = $this->rmaMessagesRepository->where('rma_id', request()->get('id'))
            ->orderBy('id', 'desc')
            ->paginate(request()->get('limit') ?? 5);

        return new JsonResponse([
            'messages' => $messages,
        ]);
    }

    /**
     * Send message
     */
    public function sendMessage(): JsonResponse
    {
        $requestData = request()->input();

        $orderDetails = $this->orderRepository->find($requestData['order_id']);

        $conversationDetails = [
            'message'       => $requestData['message'],
            'customerName'  => $orderDetails->customer_first_name.' '.$orderDetails->customer_last_name,
            'customerEmail' => $orderDetails->customer_email,
        ];

        unset($requestData['order_id']);

        $storedMessage = $this->rmaMessagesRepository->create($requestData);

        $removedKeys = explode(',', request()->input('removed_key'));

        array_shift($removedKeys);

        if (! empty(request()->file('file'))) {
            $file = request()->file('file');

            $filename = $file->getClientOriginalName();

            $path = $file->storeAs('rma-conversation/'.$storedMessage->id, $filename);

            $this->rmaMessagesRepository->update([
                'attachment_path' => $path,
                'attachment'      => $filename,
            ], $storedMessage->id);
        }

        if ($storedMessage) {
            try {
                Mail::queue(new AdminConversationNotification($conversationDetails));

                return new JsonResponse([
                    'message' => trans('admin::app.sales.rma.all-rma.view.send-message-success'),
                ]);
            } catch (\Exception $e) {
                return new JsonResponse([
                    'message' => trans('admin::app.sales.rma.all-rma.view.send-message-success'),
                ]);
            }
        }

        return new JsonResponse([
            'message' => trans('admin::app.sales.rma.all-rma.view.failed'),
        ]);
    }

    /**
     * Save rma status
     */
    public function saveRmaStatus(): RedirectResponse
    {
        $status = request()->input();

        $rma = $this->rmaRepository->find($status['rma_id']);

        $order = $rma->order;

        $mailDetails = [
            'name'           => $order->customer_first_name.' '.$order->customer_last_name,
            'email'          => $order->customer_email,
            'rma_id'         => $status['rma_id'],
            'request_status' => $status['request_status'],
        ];

        $ordersRma = $this->rmaRepository->findWhere(['order_id' => $order->id]);

        $totalCount = (int) $this->rmaItemsRepository->whereIn('rma_id', $ordersRma->pluck('id'))->sum('quantity');

        if ($totalCount > 0) {
            if ($status['request_status'] == RequestStatusEnum::ITEM_CANCELED->value) {

                foreach ($ordersRma as $orderRma) {
                    $rmaItems = $this->rmaItemsRepository->findWhere([
                        'rma_id' => $orderRma->id,
                    ]);

                    foreach ($rmaItems as $key => $rmaItem) {
                        $firstItem = $this->orderItemRepository->find($rmaItem->order_item_id);

                        if ($firstItem->parent_id != null) {
                            $parentItem = $this->orderItemRepository->find($firstItem->parent_id);

                            $parentItem->update([
                                'qty_canceled' => $rmaItem->quantity,
                            ]);
                        } else {
                            $firstItem->update([
                                'qty_canceled' => $rmaItem->quantity,
                            ]);
                        }
                    }
                }

                $this->createRefund($rma);

                $this->updateOrderStatus($order);

                Event::dispatch('sales.order.cancel.after', $order);
            }

            if ($status['request_status'] == RequestStatusEnum::RECEIVED_PACKAGE->value) {
                $refund = $this->createRefund($rma);

                if (! $refund) {
                    session()->flash('error', trans('admin::app.sales.rma.all-rma.view.refund-failed'));

                    return redirect()->back();
                }

                $updateStatus = $rma->update($status);

                session()->flash('success', trans('admin::app.sales.refunds.create.create-success'));

                return redirect()->route('admin.sales.refunds.index');
            }

            if ($order->total_qty_ordered == $totalCount) {
                if ($status['request_status'] == RequestStatusEnum::ITEM_CANCELED->value) {
                    $status['order_status'] = RequestStatusEnum::CANCELED->value;

                    $order->update(['status' => Order::STATUS_CANCELED]);
                } elseif ($status['request_status'] == RequestStatusEnum::ACCEPT->value) {
                    $this->rmaRepository->find($status['rma_id'])->update(['status' => 0]);
                }
            }
        }

        $updateStatus = $rma->update($status);

        $requestData = [
            'message'    => trans('admin::app.sales.rma.all-rma.view.status-message', [
                'id'     => $status['rma_id'],
                'status' => $rma['request_status'],
            ]),
            'rma_id'     => $status['rma_id'],
            'is_admin'   => 1,
        ];

        $this->rmaMessagesRepository->create($requestData);

        if ($updateStatus) {
            try {
                Mail::queue(new CustomerRMAStatusNotification($mailDetails));
            } catch (\Exception $e) {
            }

            session()->flash('success', trans('admin::app.sales.rma.all-rma.view.update-success'));

            return redirect()->back();
        }

        session()->flash('error', trans('admin::app.sales.rma.all-rma.view.failed'));

        return redirect()->back();
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
}
