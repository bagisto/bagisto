<?php

namespace Webkul\Shop\Http\Controllers\Customer;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Webkul\RMA\Contracts\RMAReason;
use Webkul\RMA\Enums\RMA;
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
        protected RMAHelper $rmaHelper,
        protected RMAItemRepository $rmaItemsRepository,
        protected RMAMessageRepository $rmaMessagesRepository,
        protected RMAReasonRepository $rmaReasonRepository,
        protected RMAReasonResolutionRepository $rmaReasonResolutionsRepository,
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

        return $this->rmaReasonRepository->whereIn('id', $existResolutions)->where('status', RMA::ACTIVE->value)->get();
    }

    /**
     * Change rma status to canceled.
     */
    public function cancel(int $id): JsonResponse
    {
        $rma = $this->rmaRepository->findOrFail($id);

        if ($rma->request_status == RMA::CANCELED->value) {
            return new JsonResponse([
                'message' => trans('shop::app.rma.response.already-cancel'),
            ]);
        }

        $rma->update([
            'request_status' => RMA::CANCELED->value,
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
        $data = request()->only(['rma_id', 'close_rma']);

        $rma = $this->rmaRepository->findOrFail($data['rma_id']);

        if (! empty($data['close_rma'])) {
            $rma->update([
                'status'         => 1,
                'request_status' => RMA::SOLVED->value,
                'order_status'   => 'closed',
            ]);

            $this->rmaMessagesRepository->create([
                'message'  => trans('shop::app.rma.mail.customer-conversation.solved'),
                'rma_id'   => $data['rma_id'],
                'is_admin' => 1,
            ]);
        }

        session()->flash('success', trans('shop::app.rma.response.update-success'));

        return redirect()->back();
    }

    /**
     * Reopen RMA request.
     * This method is used to reopen an RMA request that has been closed.
     */
    public function reOpen(): RedirectResponse
    {
        $data = request()->only(['rma_id', 'close_rma']);

        $rma = $this->rmaRepository->findOrFail($data['rma_id']);

        if (! empty($data['close_rma'])) {
            $order = $this->orderRepository->find($rma->order_id);

            $order->update(['status' => 'pending']);

            $rma->update([
                'status'         => RMA::ACTIVE->value,
                'request_status' => RMA::PENDING->value,
                'status'         => RMA::INACTIVE->value,
                'order_status'   => RMA::INACTIVE->value,
            ]);

            $this->rmaMessagesRepository->create([
                'message'    => trans('shop::app.rma.mail.customer-conversation.process'),
                'rma_id'     => $data['rma_id'],
                'is_admin'   => RMA::ACTIVE->value,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        session()->flash('success', trans('shop::app.rma.response.update-success'));

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
