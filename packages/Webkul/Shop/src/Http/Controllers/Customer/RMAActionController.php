<?php

namespace Webkul\Shop\Http\Controllers\Customer;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Webkul\RMA\Contracts\RMAReason;
use Webkul\RMA\Helpers\Helper as RMAHelper;
use Webkul\RMA\Repositories\RMAItemRepository;
use Webkul\RMA\Repositories\RMAMessageRepository;
use Webkul\RMA\Repositories\RMAReasonRepository;
use Webkul\RMA\Repositories\RMAReasonResolutionRepository;
use Webkul\RMA\Repositories\RMARepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Shop\Mail\Customer\RMA\CustomerConversationNotification;

class RMAActionController extends Controller
{
    /**
     * Product Type
     *
     * @var array
     */
    public const PRODUCT_TYPE = ['virtual', 'downloadable', 'booking', 'configurable'];

    /**
     * RMA status Solved.
     *
     * @var string
     */
    public const SOLVED = 'Solved';

    /**
     * RMA Status
     *
     * @var string
     */
    public const PENDING = 'Pending';

    /**
     * @var string
     */
    public const COMPLETED = 'completed';

    /**
     * @var string
     */
    public const CANCELED = 'Canceled';

    /**
     * @var int
     */
    public const INACTIVE = 0;

    /**
     * @var int
     */
    public const ACTIVE = 1;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected RMAReasonResolutionRepository $rmaReasonResolutionsRepository,
        protected RMAHelper $rmaHelper,
        protected RMAItemRepository $rmaItemsRepository,
        protected RMAMessageRepository $rmaMessagesRepository,
        protected RMAReasonRepository $rmaReasonRepository,
        protected RMARepository $rmaRepository,
    ) {}

    /**
     * Get Order details
     */
    public function getOrderProduct(int $orderId)
    {
        return $this->rmaHelper->getOrderProduct($orderId);
    }

    /**
     * Get reason for resolution.
     */
    public function getResolutionReason(mixed $resolutionType): RMAReason|Collection
    {
        $existResolutions = $this->rmaReasonResolutionsRepository->where('resolution_type', $resolutionType)->pluck('rma_reason_id');

        return $this->rmaReasonRepository->whereIn('id', $existResolutions)->where('status', self::ACTIVE)->get();
    }

    /**
     * Change rma status to canceled.
     */
    public function cancel(int $id): JsonResponse
    {
        $rma = $this->rmaRepository->find($id);

        if ($rma->request_status == self::CANCELED) {

            return new JsonResponse([
                'message' => trans('shop::app.rma.response.already-cancel'),
            ]);
        }

        $rma->update([
            'request_status' => self::CANCELED,
        ]);

        return new JsonResponse([
            'message' => trans('shop::app.rma.response.cancel-success'),
        ]);
    }

    /**
     * Save rma status by customer
     */
    public function saveStatus(): RedirectResponse
    {
        $data = request()->all();

        $rma = $this->rmaRepository->find($data['rma_id']);

        if (! empty($data['close_rma'])) {
            $rma->update([
                'status'         => 1,
                'request_status' => self::SOLVED,
                'order_status'   => 'closed',
            ]);

            $this->rmaMessagesRepository->create([
                'message'    => trans('shop::app.rma.mail.customer-conversation.solved'),
                'rma_id'     => $data['rma_id'],
                'is_admin'   => 1,
            ]);
        }

        session()->flash('success', trans('shop::app.rma.response.update-success', ['name' => trans('admin::app.sales.rma.all-rma.view.status')]));

        return redirect()->back();
    }

    /**
     * Reopen RMA request.
     * This method is used to reopen an RMA request that has been closed.
     */
    public function reOpen(): RedirectResponse
    {
        $data = request()->all();

        $rma = $this->rmaRepository->find($data['rma_id']);

        if (! empty($data['close_rma'])) {
            if (empty($rma)) {
                session()->flash('error', 'Something Went Wrong');

                return back();
            }

            $order = $this->orderRepository->find($rma->order_id);

            $order->update(['status' => 'pending']);

            $rma->update([
                'status'           => self::ACTIVE,
                'request_status'   => self::PENDING,
                'status'           => self::INACTIVE,
                'order_status'     => self::INACTIVE,
            ]);

            $this->rmaMessagesRepository->create([
                'message'    => trans('shop::app.rma.mail.customer-conversation.process'),
                'rma_id'     => $data['rma_id'],
                'is_admin'   => self::ACTIVE,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        session()->flash('success', trans('shop::app.rma.response.update-success', ['name' => 'Status']));

        return back();
    }

    /**
     * Get all messages
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
     * Send message by email
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
                    'messages' => trans('shop::app.rma.response.send-message', ['name' => trans('shop::app.rma.mail.customer-conversation.message')]),
                ]);
            }

            return new JsonResponse([
                'messages' => trans('shop::app.rma.response.send-message', ['name' => trans('shop::app.rma.mail.customer-conversation.message')]),
            ]);
        }

        return new JsonResponse([
            'messages' => trans('shop::app.customer.signup-form.failed'),
        ]);
    }
}
