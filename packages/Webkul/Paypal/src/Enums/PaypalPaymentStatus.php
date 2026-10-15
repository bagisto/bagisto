<?php

namespace Webkul\Paypal\Enums;

enum PaypalPaymentStatus: string
{
    /**
     * The payment is complete and the money is in the merchant's account.
     */
    case COMPLETED = 'Completed';

    /**
     * The payment is held, for the reason the notification's pending_reason names.
     */
    case PENDING = 'Pending';

    /**
     * The payment has been accepted, which a partially refunded payment also reports.
     */
    case PROCESSED = 'Processed';

    /**
     * The payment failed, which only an instant transfer from a bank account reports.
     */
    case FAILED = 'Failed';

    /**
     * The merchant refused a payment that was pending their approval.
     */
    case DENIED = 'Denied';

    /**
     * The merchant refunded the payment.
     */
    case REFUNDED = 'Refunded';

    /**
     * The payment was reversed, by a chargeback or a buyer complaint.
     */
    case REVERSED = 'Reversed';

    /**
     * A reversal has been cancelled, so the money is with the merchant again.
     */
    case CANCELED_REVERSAL = 'Canceled_Reversal';

    /**
     * An authorization was voided before it was captured.
     */
    case VOIDED = 'Voided';

    /**
     * An authorization expired before it was captured.
     */
    case EXPIRED = 'Expired';
}
