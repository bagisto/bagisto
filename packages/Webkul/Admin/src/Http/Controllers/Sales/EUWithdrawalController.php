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

        // Setting the row to declined clears any prior refund metadata so the
        // record reflects a single terminal state. Admins can flip back via
        // Mark Refunded if this was a misclick.
        $withdrawal->update([
            'status' => WithdrawalStatus::DECLINED,
            'declined_at' => now(),
            'declined_reason' => request('declined_reason'),
            'declined_by_user_id' => auth()->guard('admin')->id(),
            'refunded_at' => null,
            'refunded_by_user_id' => null,
            'refund_note' => null,
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

        // Setting the row to refunded clears any prior decline metadata so the
        // record reflects a single terminal state. Admins can flip back via
        // Decline if this was a misclick.
        $withdrawal->update([
            'status' => WithdrawalStatus::REFUNDED,
            'refunded_at' => now(),
            'refund_note' => request('refund_note'),
            'refunded_by_user_id' => auth()->guard('admin')->id(),
            'declined_at' => null,
            'declined_reason' => null,
            'declined_by_user_id' => null,
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

        $isTerminal = in_array($withdrawal->status, [WithdrawalStatus::REFUNDED, WithdrawalStatus::DECLINED], true);

        try {
            Mail::send(new WithdrawalConfirmation($withdrawal));

            $updates = ['confirmation_error' => null];

            if ($isTerminal) {
                // Final status email recorded separately so the initial
                // confirmation_sent_at — legal evidence of durable-medium
                // delivery under Article 11a(3) — is preserved.
                $updates['final_confirmation_sent_at'] = now();
            } elseif ($withdrawal->confirmation_sent_at === null) {
                // Recovery path: initial send failed earlier, this resend is
                // the first successful durable-medium delivery.
                $updates['confirmation_sent_at'] = now();
            }

            $withdrawal->update($updates);

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
