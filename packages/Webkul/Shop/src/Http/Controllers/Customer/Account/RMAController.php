<?php

namespace Webkul\Shop\Http\Controllers\Customer\Account;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Webkul\RMA\Contracts\RMAReason;
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
use Webkul\Shop\DataGrids\RMA\OrderDataGrid;
use Webkul\Shop\DataGrids\RMA\RMADataGrid;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Shop\Mail\Customer\RMA\CustomerConversationNotification;
use Webkul\Shop\Mail\Customer\RMA\CustomerRMARequestNotification;

class RMAController extends Controller
{
    /**
     * Configurable product type constant.
     *
     * @var string
     */
    public const CONFIGURABLE = 'configurable';

    /**
     * Supported product types for RMA.
     *
     * @var array
     */
    public const PRODUCT_TYPE = ['virtual', 'downloadable', 'booking', 'configurable'];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderItemRepository $orderItemRepository,
        protected RMARepository $rmaRepository,
        protected RMAAdditionalFieldRepository $rmaAdditionalFieldRepository,
        protected RMAImageRepository $rmaImagesRepository,
        protected RMAItemRepository $rmaItemsRepository,
        protected RMAMessageRepository $rmaMessagesRepository,
        protected RMAReasonRepository $rmaReasonRepository,
        protected RMAReasonResolutionRepository $rmaReasonResolutionsRepository,
        protected RMAStatusRepository $rmaStatusRepository,
        protected RMAHelper $rmaHelper,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(RMADataGrid::class)->process();
        }

        return view('shop::customers.account.rma.index');
    }

    /**
     * Display the specified resource.
     */
    public function view(int $id): View|RedirectResponse
    {
        $rma = $this->rmaRepository->with(['items', 'order'])
            ->findOneWhere(['id' => $id]);

        if (! $rma) {
            return abort(404);
        }

        $canCloseRma = true;
        $canReopenRma = false;
        
        if (
            is_null($rma->rma_status_id)
            || in_array($rma->rma_status_id, [DefaultRMAStatusEnum::RECEIVED_PACKAGE->value, DefaultRMAStatusEnum::SOLVED->value, DefaultRMAStatusEnum::ITEM_CANCELED->value, DefaultRMAStatusEnum::DECLINED->value, DefaultRMAStatusEnum::CANCELED->value]) 
            || in_array($rma->order->status, [Order::STATUS_CANCELED, Order::STATUS_CLOSED])
        ) {
            $canCloseRma = false;
        }

        if (
            core()->getConfigData('sales.rma.setting.allowed_new_rma_request_for_cancelled_request') == 'yes' &&
            $rma->rma_status_id == DefaultRMAStatusEnum::CANCELED->value
        ) {
            $canReopenRma = true;
        }

        $expireDays = intval(core()->getConfigData('sales.rma.setting.default_allow_days'));

        $daysSinceCreation = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($rma->created_at));

        $isExpired = $daysSinceCreation > $expireDays && $daysSinceCreation != 0;

        $rmaStatus = $this->rmaStatusRepository->findOrFail($rma->rma_status_id);

        return view('shop::customers.account.rma.view', compact('rma', 'canCloseRma', 'canReopenRma', 'isExpired', 'rmaStatus'));
    }

    /**
     * Create rma for customer. This page will first show order list to select order.
     * Then after selecting order, it will show order items to create rma.
     */
    public function create(): JsonResponse|View
    {
        if (request()->ajax()) {
            return datagrid(OrderDataGrid::class)->process();
        }

        return view('shop::customers.account.rma.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse|RedirectResponse
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
            'agreement'       => 'accepted',
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

        $rma = $this->rmaRepository->create([
            'order_id'          => $data['order_id'],
            'rma_status_id'     => DefaultRMAStatusEnum::PENDING->value,
            'information'       => $data['information'] ?? null,
            'package_condition' => $data['package_condition'] ?? null,
        ]);

        foreach ($data['order_item_id'] as $key => $orderItemId) {
            $this->rmaItemsRepository->create([
                'rma_id'        => $rma->id,
                'rma_reason_id' => $data['rma_reason_id'][$key],
                'order_item_id' => $orderItemId,
                'variant_id'    => ! empty($data['variant'][$key]) ? $data['variant'][$key] : null,
                'quantity'      => $data['rma_qty'][$key],
                'resolution'    => $data['resolution_type'][$key],
            ]);
        }

        $this->rmaMessagesRepository->create([
            'rma_id'     => $rma->id,
            'message'    => trans('shop::app.rma.mail.customer-conversation.process'),
            'is_admin'   => 1,
        ]);

        if (! empty($data['images']) && ! empty(implode(',', $data['images']))) {
            foreach ($data['images'] as $itemImage) {
                $this->rmaImagesRepository->create([
                    'rma_id' => $rma->id,
                    'path'   => $itemImage->getClientOriginalName(),
                ]);
            }

            $this->rmaImagesRepository->uploadImages($data, $rma);
        }

        $customAttributes = request('customAttributes') ?? [];

        if ($customAttributes) {
            $customAttributesData = [];

            foreach ($customAttributes as $key => $customAttribute) {
                $customAttributesData = [
                    'rma_id' => $data['rma_id'],
                    'name'   => $key,
                    'value'  => is_array($customAttribute) ? implode(',', $customAttribute) : $customAttribute,
                ];

                $this->rmaAdditionalFieldRepository->create($customAttributesData);
            }
        }

        if ($rma->items) {
            try {
                // Mail::queue(new CustomerRMARequestNotification($rma));
            } catch (\Exception $e) {
            }

            return new JsonResponse([
                'messages' => trans('shop::app.rma.response.create-success'),
            ]);
        } else {
            return new JsonResponse([
                'messages' => trans('shop::app.customer.signup-form.failed'),
            ]);
        }
    }

    /**
     * Get order items for rma creation.
     */
    public function getOrderItems(int $orderId)
    {
        return $this->rmaHelper->getOrderProduct($orderId);
    }

    /**
     * Get resolution reasons based on resolution type.
     */
    public function getResolutionReasons(string $resolutionType): RMAReason|Collection
    {
        $existResolutions = $this->rmaReasonResolutionsRepository
            ->where('resolution_type', $resolutionType)
            ->pluck('rma_reason_id');

        return $this->rmaReasonRepository
            ->whereIn('id', $existResolutions)
            ->where('status', 1)
            ->get();
    }

    /**
     * Update RMA status.
     */
    public function updateStatus(int $id): RedirectResponse
    {
        $data = request()->only(['close_rma']);

        $rma = $this->rmaRepository->findOrFail($id);

        if (! empty($data['close_rma'])) {
            $rma->update([
                'status'        => 1,
                'rma_status_id' => DefaultRMAStatusEnum::SOLVED->value,
                'order_status'  => 0,
            ]);

            $this->rmaMessagesRepository->create([
                'message'  => trans('shop::app.rma.mail.customer-conversation.solved'),
                'rma_id'   => $id,
                'is_admin' => 1,
            ]);
        }

        session()->flash('success', trans('shop::app.rma.response.update-success'));

        return redirect()->back();
    }

    /**
     * Re-open RMA request.
     */
    public function reOpenRequest(int $id): RedirectResponse
    {
        $data = request()->only(['reopen_rma']);

        $rma = $this->rmaRepository->findOrFail($id);

        if (! empty($data['reopen_rma'])) {
            $order = $this->orderRepository->findOrFail($rma->order_id);

            $order->update(['status' => 'pending']);

            $rma->update([
                'rma_status_id' => DefaultRMAStatusEnum::PENDING->value,
                'status'        => 0,
                'order_status'  => 0,
            ]);

            $this->rmaMessagesRepository->create([
                'message'    => trans('shop::app.rma.mail.customer-conversation.process'),
                'rma_id'     => $id,
                'is_admin'   => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        session()->flash('success', trans('shop::app.rma.response.update-success'));

        return back();
    }

    /**
     * Cancel RMA request.
     */
    public function cancelRequest(int $id): JsonResponse
    {
        $rma = $this->rmaRepository->findOrFail($id);

        if ($rma->rma_status_id == DefaultRMAStatusEnum::CANCELED->value) {
            return new JsonResponse([
                'message' => trans('shop::app.rma.response.already-cancel'),
            ]);
        }

        $rma->update([
            'rma_status_id' => DefaultRMAStatusEnum::CANCELED->value,
        ]);

        return new JsonResponse([
            'message' => trans('shop::app.rma.response.cancel-success'),
        ]);
    }

    /**
     * Get messages for rma conversation.
     */
    public function getMessages(): JsonResponse
    {
        $messages = $this->rmaMessagesRepository
            ->where('rma_id', request()->get('id'))
            ->orderBy('id', 'desc')
            ->paginate(request()->get('limit') ?? 5);

        return new JsonResponse([
            'messages' => $messages,
        ]);
    }

    /**
     * Send message in rma conversation.
     */
    public function sendMessage(): JsonResponse
    {
        $data = request()->all();

        $conversationDetails = [
            'adminName'     => 'Admin',
            'message'       => $data['message'],
            'adminEmail'    => core()->getConfigData('emails.configure.email_settings.admin_email') ?: config('mail.admin.address'),
            'customerEmail' => auth()->guard('customer')->check() ? auth()->guard('customer')->user()->email : $this->orderRepository->find(session()->get('guestOrderId'))->customer_email,
        ];

        $storedMessage = $this->rmaMessagesRepository->create($data);

        if (! empty($storedMessage)) {
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

            try {
                if ($conversationDetails['adminEmail']) {
                    Mail::queue(new CustomerConversationNotification($conversationDetails));
                }
            } catch (\Exception $e) {
                return new JsonResponse([
                    'messages' => trans('shop::app.rma.response.send-message', [
                        'name' => trans('shop::app.rma.mail.customer-conversation.message'),
                    ]),
                ]);
            }

            return new JsonResponse([
                'messages' => trans('shop::app.rma.response.send-message', [
                    'name' => trans('shop::app.rma.mail.customer-conversation.message'),
                ]),
            ]);
        }

        return new JsonResponse([
            'messages' => trans('shop::app.customer.signup-form.failed'),
        ]);
    }
}
