<?php

namespace Webkul\Shop\Listeners\EUWithdrawal;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Webkul\EUWithdrawal\Events\WithdrawalReceived;
use Webkul\Shop\Mail\Customer\EUWithdrawal\WithdrawalConfirmation;

class SendConfirmation
{
    /**
     * Handle the event.
     *
     * The transaction wrapping the withdrawal insert has already committed
     * by the time we run, so any failure here leaves the evidence intact —
     * we record the SMTP error against the row and surface it in the admin
     * UI where it can be resent manually.
     */
    public function handle(WithdrawalReceived $event): void
    {
        $withdrawal = $event->withdrawal;

        $previousLocale = app()->getLocale();
        app()->setLocale($withdrawal->locale);

        try {
            Mail::send(new WithdrawalConfirmation($withdrawal));

            $withdrawal->update([
                'confirmation_sent_at' => now(),
                'confirmation_error' => null,
            ]);
        } catch (\Throwable $e) {
            Log::error('EU withdrawal confirmation email failed', [
                'withdrawal_id' => $withdrawal->id,
                'uuid' => $withdrawal->uuid,
                'error' => $e->getMessage(),
            ]);

            $withdrawal->update([
                'confirmation_error' => mb_substr($e->getMessage(), 0, 500),
            ]);
        } finally {
            app()->setLocale($previousLocale);
        }
    }
}
