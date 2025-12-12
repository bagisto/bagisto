<?php

namespace Webkul\Admin\Http\Controllers\Sales\RMA;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Sales\RMA\RMADataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Mail\Admin\RMA\AdminConversationNotification;
use Webkul\RMA\Enums\RequestStatusEnum;
use Webkul\RMA\Repositories\RMAAdditionalFieldRepository;
use Webkul\RMA\Repositories\RMAItemRepository;
use Webkul\RMA\Repositories\RMAMessageRepository;
use Webkul\RMA\Repositories\RMARepository;
use Webkul\RMA\Repositories\RMAStatusRepository;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\RefundRepository;
use Webkul\Shop\Mail\Customer\RMA\CustomerRMAStatusNotification;

class RMAController extends Controller
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
        protected RMAItemRepository $rmaItemsRepository,
        protected RMAMessageRepository $rmaMessagesRepository,
        protected RMARepository $rmaRepository,
        protected RMAStatusRepository $rmaStatusRepository,
    ) {}

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
                'message'    => trans('shop::app.rma.mail.customer-conversation.process'),
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
            'message' => trans('shop::app.customer.signup-form.failed'),
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
                    session()->flash('error', trans('shop::app.customer.signup-form.failed'));

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
            'message'    => trans('shop::app.rma.mail.status.your-rma-id').' '.trans('shop::app.rma.mail.status.status-change', ['id' => $status['rma_id']]).'. '.trans('shop::app.rma.mail.status.status').' : '.$rma['request_status'],
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

        session()->flash('error', trans('shop::app.customer.signup-form.failed'));

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
            session()->flash('error', trans('shop::app.rma.response.creation-error'));

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
