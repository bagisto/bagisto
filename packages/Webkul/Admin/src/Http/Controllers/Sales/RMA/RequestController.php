<?php

namespace Webkul\Admin\Http\Controllers\Sales\RMA;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Sales\RMA\OrderRMADataGrid;
use Webkul\Admin\DataGrids\Sales\RMA\RMADataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\RMA\Contracts\RMAReasonResolution;
use Webkul\RMA\Enums\DefaultRMAResolution;
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
use Webkul\Sales\Exceptions\InvalidRefundQuantityException;
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
     * Display the specified resource.
     */
    public function view(int $rmaId): View|RedirectResponse
    {
        $rma = $this->rmaRepository->with(['item', 'order'])->findOrFail($rmaId);

        $statusArray = $this->rmaStatusForRequest($rma);

        return view('admin::sales.rma.returns.view', compact('rma', 'statusArray'));
    }

    /**
     * Re-open RMA request.
     */
    public function reOpenRequest(int $id): RedirectResponse
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
            } catch (\Exception $e) {
            }
        }

        session()->flash('success', trans('admin::app.sales.rma.all-rma.view.update-success'));

        return back();
    }

    /**
     * Get messages for RMA conversation.
     */
    public function getMessages(): JsonResponse
    {
        $messages = $this->rmaMessageRepository->where('rma_id', request()->get('id'))
            ->orderBy('id', 'desc')
            ->paginate(request()->get('limit') ?? 5);

        return new JsonResponse([
            'messages' => $messages,
        ]);
    }

    /**
     * Send message in RMA conversation.
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
            } catch (\Exception $e) {
            }

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
            'order_item_id'   => 'required',
            'rma_qty'         => 'required',
            'resolution_type' => 'required',
            'rma_reason_id'   => 'required',
            'information'     => 'nullable|string',
            'images'          => 'nullable|array|min:1',
            'images.*'        => 'nullable|file|mimetypes:'.core()->getConfigData('sales.rma.setting.allowed_file_extension'),
        ]);

        $data = request()->only([
            'order_id',
            'order_item_id',
            'variant',
            'rma_qty',
            'resolution_type',
            'rma_reason_id',
            'information',
            'images',
            'package_condition',
        ]);

        Event::dispatch('sales.rma.request.create.before', $data);

        /**
         * Creation of a new RMA record.
         */
        $rma = $this->rmaRepository->create([
            'order_id'          => $data['order_id'],
            'rma_status_id'     => DefaultRMAStatusEnum::PENDING->value,
            'information'       => $data['information'] ?? null,
            'package_condition' => $data['package_condition'] ?? null,
        ]);

        /**
         * Creation of RMA item for the newly created RMA record.
         */
        $this->rmaItemRepository->create([
            'rma_id'        => $rma->id,
            'rma_reason_id' => $data['rma_reason_id'],
            'order_item_id' => $data['order_item_id'],
            'variant_id'    => ! empty($data['variant']) ? $data['variant'] : null,
            'quantity'      => $data['rma_qty'],
            'resolution'    => $data['resolution_type'],
        ]);

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

        Event::dispatch('sales.rma.request.create.after', $rma);

        /**
         * Sending RMA creation email to the customer.
         */
        if ($rma->item) {
            try {
                Mail::queue(new CustomerRMARequestNotification($rma));
            } catch (\Exception $e) {
            }

            return response()->json([
                'messages'     => trans('admin::app.sales.rma.create-rma.create-success'),
                'redirect_url' => route('admin.sales.rma.view', $rma->id),
            ]);
        }

        return response()->json([
            'messages'     => trans('admin::app.sales.rma.create-rma.failed'),
            'redirect_url' => route('admin.sales.rma.create'),
        ]);
    }

    /**
     * Get order items for RMA creation.
     */
    public function getOrderItems(int $orderId): Collection
    {
        return $this->rmaHelper->getOrderItems($orderId);
    }

    /**
     * Get RMA reason by resolution type.
     */
    public function getResolutionReasons(string $resolutionType): RMAReasonResolution|Collection
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

        $hasCancel = $rma->item->resolution === DefaultRMAResolution::CANCEL_ITEMS->value;

        $excludedStatuses = $hasCancel
            ? [DefaultRMAStatusEnum::ACCEPT->value, DefaultRMAStatusEnum::DECLINED->value, DefaultRMAStatusEnum::PENDING->value, DefaultRMAStatusEnum::DISPATCHED_PACKAGE->value, DefaultRMAStatusEnum::RECEIVED_PACKAGE->value, DefaultRMAStatusEnum::SOLVED->value]
            : [DefaultRMAStatusEnum::ITEM_CANCELED->value, DefaultRMAStatusEnum::ACCEPT->value, DefaultRMAStatusEnum::DECLINED->value, DefaultRMAStatusEnum::PENDING->value, DefaultRMAStatusEnum::SOLVED->value];

        return $this->rmaStatusRepository
            ->whereIn('id', $activeStatusIds->diff($excludedStatuses))
            ->pluck('title', 'id')
            ->toArray();
    }

    /**
     * Update RMA status by admin.
     */
    public function updateStatus(int $id): JsonResponse
    {
        $data = request()->input();

        $rma = $this->rmaRepository->findOrFail($id);

        $totalCount = $this->rmaItemRepository
            ->where('rma_id', $id)
            ->sum('quantity');

        if (empty($totalCount)) {
            return $this->finalizeRmaUpdate($rma, $data);
        }

        $statusId = (int) $data['rma_status_id'];

        return match ($statusId) {
            DefaultRMAStatusEnum::RECEIVED_PACKAGE->value => $this->handleReceivedPackage($rma, $data),
            DefaultRMAStatusEnum::ITEM_CANCELED->value    => $this->handleItemCancellation($rma, $data),
            default                                       => $this->finalizeRmaUpdate($rma, $data),
        };
    }

    /**
     * Handle received package status.
     */
    private function handleReceivedPackage($rma, array $data): JsonResponse
    {
        try {
            DB::beginTransaction();

            $refundData = $this->prepareRefundDataFromRmaItem($rma);

            if (! $this->processOrderRefund($rma->order, $refundData)) {
                throw new \Exception(trans('admin::app.sales.rma.all-rma.view.refund-failed'));
            }

            $result = $this->finalizeRmaUpdate($rma, $data);

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();

            return new JsonResponse([
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle item cancellation status.
     */
    private function handleItemCancellation($rma, array $data): JsonResponse
    {
        try {
            DB::beginTransaction();

            $this->cancelRmaItem($rma);

            $result = $this->finalizeRmaUpdate($rma, $data);

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();

            return new JsonResponse([
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Cancel RMA items and update order.
     */
    private function cancelRmaItem($rma): void
    {
        if ($rma->item) {
            $orderItem = $rma->item->orderItem;

            if (! $orderItem) {
                return;
            }

            $this->cancelNonInvoicedItem($rma->item, $orderItem);
        }

        $this->orderRepository->updateOrderStatus($rma->order);

        Event::dispatch('sales.order.cancel.after', $rma->order);
    }

    /**
     * Cancel a Non-Invoiced item and restore inventory.
     */
    private function cancelNonInvoicedItem($rmaItem, $orderItem): void
    {
        $this->orderItemRepository->returnQtyToProductInventory($orderItem);

        if ($orderItem->qty_ordered) {
            $orderItem->qty_canceled += $rmaItem->quantity;
            $orderItem->save();

            if (
                $orderItem->parent
                && $orderItem->parent->qty_ordered
            ) {
                $orderItem->parent->qty_canceled += $orderItem->parent->qty_to_cancel;
                $orderItem->parent->save();
            }
        } else {
            $orderItem->parent->qty_canceled += $orderItem->parent->qty_to_cancel;
            $orderItem->parent->save();
        }
    }

    /**
     * Process refund for an order.
     */
    private function processOrderRefund($order, array $refundData): bool
    {
        if (! $order->canRefund()) {
            session()->flash('error', trans('admin::app.sales.refunds.create.creation-error'));

            return false;
        }

        try {
            $totals = $this->refundRepository->getOrderItemsRefundSummary(
                $refundData['refund'],
                $order->id
            );

            if (! $totals) {
                throw new InvalidRefundQuantityException(
                    trans('admin::app.sales.refunds.create.invalid-qty')
                );
            }

            $validation = $this->validateRefundAmount($order, $totals, $refundData['refund']);

            if (! $validation['valid']) {
                session()->flash('error', $validation['message']);

                return false;
            }

            $refund = $this->refundRepository->create(
                array_merge($refundData, ['order_id' => $order->id])
            );

            return (bool) $refund;

        } catch (InvalidRefundQuantityException $e) {
            session()->flash('error', $e->getMessage());

            return false;
        }
    }

    /**
     * Validate refund amount against order limits.
     */
    private function validateRefundAmount($order, array $totals, array $refundData): array
    {
        $maxRefundAmount = $totals['grand_total']['price'] - $order->refunds()->sum('base_adjustment_refund');

        $refundAmount = $totals['grand_total']['price'] - $totals['shipping']['price'] + $refundData['shipping'] + $refundData['adjustment_refund'] - $refundData['adjustment_fee'];

        if (! $refundAmount) {
            return [
                'valid'   => false,
                'message' => trans('admin::app.sales.refunds.create.invalid-refund-amount-error'),
            ];
        }

        if ($refundAmount > $maxRefundAmount) {
            return [
                'valid'   => false,
                'message' => trans('admin::app.sales.refunds.create.refund-limit-error', [
                    'amount' => core()->formatBasePrice($maxRefundAmount),
                ]),
            ];
        }

        return ['valid' => true];
    }

    /**
     * Prepare refund data from RMA item.
     */
    private function prepareRefundDataFromRmaItem($rma): array
    {
        $item = [
            $rma->item->order_item_id => $rma->item->quantity,
        ];

        return [
            'refund' => [
                'shipping'          => request('shipping', 0),
                'adjustment_refund' => 0,
                'adjustment_fee'    => 0,
                'items'             => $item,
            ],
        ];
    }

    /**
     * Finalize RMA update with message and notification.
     */
    private function finalizeRmaUpdate($rma, array $data): JsonResponse
    {
        $rma->update($data);

        $this->rmaMessageRepository->create([
            'message' => trans('admin::app.sales.rma.all-rma.view.status-message', [
                'id'     => $rma->id,
                'status' => $rma->fresh()->status->title,
            ]),
            'rma_id'   => $rma->id,
            'is_admin' => 1,
        ]);

        try {
            Mail::queue(new CustomerRMAStatusNotification($rma));
        } catch (\Exception $e) {
        }

        return new JsonResponse([
            'messages' => trans('admin::app.sales.rma.all-rma.view.update-success'),
        ]);
    }
}
