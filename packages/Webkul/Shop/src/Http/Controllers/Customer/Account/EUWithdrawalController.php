<?php

namespace Webkul\Shop\Http\Controllers\Customer\Account;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\EUWithdrawal\Repositories\WithdrawalRepository;
use Webkul\EUWithdrawal\Services\WithdrawalService;
use Webkul\Sales\Contracts\Order as OrderContract;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Shop\Http\Requests\EUWithdrawal\StoreWithdrawalRequest;

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
     * Show the withdrawal-submission form for an order owned by the
     * authenticated customer. If a withdrawal already exists for this
     * order, redirect to its confirmation receipt instead.
     */
    public function create(int $orderId): View|RedirectResponse
    {
        $order = $this->ownedOrAbort($orderId);

        $this->ensureChannelEnabledOrAbort($order);

        if ($existing = $this->withdrawals->findForOrder($order->id)) {
            return redirect()->route('shop.customers.account.eu-withdrawal.show', $existing->uuid);
        }

        return view('shop::customers.account.eu-withdrawals.form', [
            'order' => $order,
            'isGuest' => false,
            'formUrl' => route('shop.customers.account.eu-withdrawal.store', $order->id),
        ]);
    }

    /**
     * Persist the customer's withdrawal declaration and redirect to the
     * receipt. The service layer is idempotent — a second POST will return
     * the existing record rather than creating a duplicate.
     */
    public function store(StoreWithdrawalRequest $request, int $orderId): RedirectResponse
    {
        $order = $this->ownedOrAbort($orderId);

        $this->ensureChannelEnabledOrAbort($order);

        $withdrawal = $this->service->submit(
            $order,
            $request->input('reason_text'),
            app()->getLocale(),
        );

        return redirect()->route('shop.customers.account.eu-withdrawal.show', $withdrawal->uuid);
    }

    /**
     * Render the receipt page for a withdrawal owned by the authenticated
     * customer (ownership is via the underlying order's customer_id).
     */
    public function show(string $uuid): View
    {
        $withdrawal = $this->withdrawals
            ->getModel()
            ->newQuery()
            ->where('uuid', $uuid)
            ->whereHas('order', function ($q) {
                $q->where('customer_id', auth()->guard('customer')->id());
            })
            ->firstOrFail();

        return view('shop::customers.account.eu-withdrawals.confirmation', [
            'withdrawal' => $withdrawal,
            'isGuest' => false,
        ]);
    }

    /**
     * Resolve an order owned by the currently-authenticated customer, or
     * abort with 404 to avoid leaking order-existence information.
     */
    protected function ownedOrAbort(int $orderId): OrderContract
    {
        $customerId = auth()->guard('customer')->id();

        $order = $this->orders->getModel()
            ->newQuery()
            ->where('id', $orderId)
            ->where('customer_id', $customerId)
            ->first();

        abort_if(! $order, 404);

        return $order;
    }

    /**
     * Abort with 404 when the channel toggle for the order's channel is off.
     * This is the package master switch — disabled channels behave as if the
     * package wasn't installed.
     */
    protected function ensureChannelEnabledOrAbort(OrderContract $order): void
    {
        abort_if(
            ! core()->getConfigData('sales.eu_withdrawal.general.enabled', optional($order->channel)->code),
            404
        );
    }
}
