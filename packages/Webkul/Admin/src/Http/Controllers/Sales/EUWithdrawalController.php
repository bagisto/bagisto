<?php

namespace Webkul\Admin\Http\Controllers\Sales;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Sales\EUWithdrawalDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\EUWithdrawal\Enums\WithdrawalStatus;
use Webkul\EUWithdrawal\Repositories\WithdrawalRepository;
use Webkul\Shop\Mail\Customer\EUWithdrawal\WithdrawalConfirmation;

class EUWithdrawalController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected WithdrawalRepository $withdrawals) {}

    /**
     * Show the withdrawal index — Blade page on a normal request, DataGrid
     * JSON payload on an AJAX request.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(EUWithdrawalDataGrid::class)->process();
        }

        return view('admin::sales.eu-withdrawals.index');
    }

    /**
     * Show the read-only detail view for a single withdrawal record,
     * including the evidence panel, timeline, and admin actions.
     */
    public function view(int $id): View
    {
        $withdrawal = $this->withdrawals->with([
            'order',
            'customer',
            'channel',
            'declinedBy',
            'refundedBy',
        ])->findOrFail($id);

        return view('admin::sales.eu-withdrawals.view', compact('withdrawal'));
    }

    /**
     * Record that the merchant contests entitlement for this withdrawal.
     * The declined timestamp and admin user id are written, blocking further
     * customer-side action on the record.
     */
    public function decline(int $id): RedirectResponse
    {
        request()->validate([
            'declined_reason' => ['required', 'string', 'max:500'],
        ]);

        $withdrawal = $this->withdrawals->findOrFail($id);

        $withdrawal->update([
            'status' => WithdrawalStatus::DECLINED,
            'declined_at' => now(),
            'declined_reason' => request('declined_reason'),
            'declined_by_user_id' => auth()->guard('admin')->id(),
        ]);

        session()->flash('success', trans('admin::app.eu_withdrawal.flash.declined'));

        return redirect()->route('admin.sales.eu-withdrawals.view', $id);
    }

    /**
     * Record that the refund has been issued out-of-band (in Bagisto's
     * existing refund/order tools) against this withdrawal. The refund
     * note is an optional admin-supplied reference (e.g. refund id).
     */
    public function markRefunded(int $id): RedirectResponse
    {
        request()->validate([
            'refund_note' => ['nullable', 'string', 'max:500'],
        ]);

        $withdrawal = $this->withdrawals->findOrFail($id);

        $withdrawal->update([
            'status' => WithdrawalStatus::REFUNDED,
            'refunded_at' => now(),
            'refund_note' => request('refund_note'),
            'refunded_by_user_id' => auth()->guard('admin')->id(),
        ]);

        session()->flash('success', trans('admin::app.eu_withdrawal.flash.refunded'));

        return redirect()->route('admin.sales.eu-withdrawals.view', $id);
    }

    /**
     * Re-send the confirmation email. Used when the original send failed
     * (a non-null confirmation_error is shown in the timeline) or when the
     * customer reports missing the original.
     */
    public function resendConfirmation(int $id): RedirectResponse
    {
        $withdrawal = $this->withdrawals->findOrFail($id);

        $previousLocale = app()->getLocale();
        app()->setLocale($withdrawal->locale);

        try {
            Mail::send(new WithdrawalConfirmation($withdrawal));

            $withdrawal->update([
                'confirmation_sent_at' => now(),
                'confirmation_error' => null,
            ]);

            session()->flash('success', trans('admin::app.eu_withdrawal.flash.confirmation_resent'));
        } catch (\Throwable $e) {
            $withdrawal->update([
                'confirmation_error' => mb_substr($e->getMessage(), 0, 500),
            ]);

            session()->flash('error', trans('admin::app.eu_withdrawal.flash.confirmation_failed'));
        } finally {
            app()->setLocale($previousLocale);
        }

        return redirect()->route('admin.sales.eu-withdrawals.view', $id);
    }
}
