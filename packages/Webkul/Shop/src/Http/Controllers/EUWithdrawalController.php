<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Webkul\EUWithdrawal\Repositories\WithdrawalRepository;
use Webkul\EUWithdrawal\Services\WithdrawalService;
use Webkul\Sales\Contracts\Order as OrderContract;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Shop\Http\Requests\EUWithdrawal\LookupGuestOrderRequest;
use Webkul\Shop\Http\Requests\EUWithdrawal\StoreWithdrawalRequest;
use Webkul\Shop\Mail\Customer\EUWithdrawal\GuestWithdrawalLink;

class EUWithdrawalController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected OrderRepository $orders,
        protected WithdrawalRepository $withdrawals,
        protected WithdrawalService $service,
    ) {}

    /**
     * Render the public lookup form. Returns 404 when the feature is
     * disabled on the current channel — the page should not exist for
     * channels that don't sell to EU consumers.
     */
    public function lookupForm(): View
    {
        abort_unless(
            (bool) core()->getConfigData('sales.eu_withdrawal.general.enabled', core()->getCurrentChannelCode()),
            404
        );

        return view('shop::eu-withdrawals.lookup');
    }

    /**
     * Process a lookup submission: if the order+email match and the channel
     * is enabled, mail a signed-URL link. The response is deliberately
     * constant — same redirect + flash for both match and miss — so the
     * endpoint cannot be used to enumerate order numbers.
     */
    public function lookupSubmit(LookupGuestOrderRequest $request): RedirectResponse
    {
        abort_unless(
            (bool) core()->getConfigData('sales.eu_withdrawal.general.enabled', core()->getCurrentChannelCode()),
            404
        );

        $currentChannel = core()->getCurrentChannel();

        $order = $this->orders->getModel()
            ->newQuery()
            ->where('increment_id', $request->input('order_increment_id'))
            ->where('customer_email', $request->input('email'))
            ->where('is_guest', 1)
            ->when($currentChannel, fn ($q) => $q->where('channel_id', $currentChannel->id))
            ->first();

        if ($order && core()->getConfigData('sales.eu_withdrawal.general.enabled', optional($order->channel)->code)) {
            $signedUrl = URL::temporarySignedRoute(
                'shop.eu-withdrawal.guest.create',
                now()->addHours(24),
                ['orderId' => $order->id],
            );

            try {
                // Queued (not Mail::send) so response timing does not leak whether
                // the lookup matched — the magic-link email is not the durable-medium
                // artifact (the confirmation email is), so async delivery is safe here.
                Mail::queue(new GuestWithdrawalLink(
                    toEmail: $order->customer_email,
                    signedUrl: $signedUrl,
                    orderIncrementId: $order->increment_id,
                ));
            } catch (\Throwable $e) {
                Log::error('EU withdrawal guest-link email failed', ['error' => $e->getMessage()]);
            }
        }

        return redirect()
            ->route('shop.eu-withdrawal.guest.lookup')
            ->with('lookup_sent', true);
    }

    /**
     * Render the withdrawal form for a guest who has clicked a valid
     * magic-link. If a withdrawal already exists, mint a fresh signed URL
     * to its confirmation page and redirect there instead.
     */
    public function guestCreate(int $orderId): View|RedirectResponse
    {
        $order = $this->loadGuestOrderOrAbort($orderId);

        if ($existing = $this->withdrawals->findForOrder($order->id)) {
            return redirect(
                URL::temporarySignedRoute(
                    'shop.eu-withdrawal.guest.confirmation',
                    now()->addHours(24),
                    ['uuid' => $existing->uuid],
                )
            );
        }

        return view('shop::eu-withdrawals.form', [
            'order' => $order,
            'isGuest' => true,
            'formUrl' => URL::temporarySignedRoute(
                'shop.eu-withdrawal.guest.store',
                now()->addHours(24),
                ['orderId' => $order->id],
            ),
        ]);
    }

    /**
     * Persist a guest withdrawal declaration and redirect to a freshly-
     * signed confirmation URL.
     */
    public function guestStore(StoreWithdrawalRequest $request, int $orderId): RedirectResponse
    {
        $order = $this->loadGuestOrderOrAbort($orderId);

        $withdrawal = $this->service->submit(
            $order,
            $request->input('reason_text'),
            app()->getLocale(),
        );

        return redirect(
            URL::temporarySignedRoute(
                'shop.eu-withdrawal.guest.confirmation',
                now()->addHours(24),
                ['uuid' => $withdrawal->uuid],
            )
        );
    }

    /**
     * Render the receipt page for a guest withdrawal. Reached only via a
     * fresh signed URL; the route is wrapped in the signed middleware.
     */
    public function guestConfirmation(string $uuid): View
    {
        $withdrawal = $this->withdrawals
            ->getModel()
            ->newQuery()
            ->where('uuid', $uuid)
            ->firstOrFail();

        return view('shop::eu-withdrawals.confirmation', [
            'withdrawal' => $withdrawal,
            'isGuest' => true,
        ]);
    }

    /**
     * Resolve a guest order by id, asserting the order is a guest order
     * and the channel has EU withdrawal enabled. Returns 404 in every
     * failure case so the endpoint never reveals which condition failed.
     */
    protected function loadGuestOrderOrAbort(int $orderId): OrderContract
    {
        $order = $this->orders->find($orderId);

        abort_if(! $order, 404);
        abort_if(! $order->is_guest, 404);

        abort_if(
            ! core()->getConfigData('sales.eu_withdrawal.general.enabled', optional($order->channel)->code),
            404
        );

        return $order;
    }
}
