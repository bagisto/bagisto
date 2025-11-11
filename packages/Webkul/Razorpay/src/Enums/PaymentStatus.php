<?php

namespace Webkul\Razorpay\Enums;

enum PaymentStatus: string
{
    /**
     * Payment is awaiting completion.
     */
    case AWAITING_PAYMENT = 'awaiting_payment';

    /**
     * An error occurred during payment.
     */
    case PAYMENT_ERROR = 'payment_error';

    /**
     * Payment was cancelled.
     */
    case PAYMENT_CANCELLED = 'payment_cancelled';

    /**
     * Payment was captured successfully.
     */
    case CAPTURED = 'captured';

    /**
     * Payment was authorized.
     */
    case AUTHORIZED = 'authorized';

    /**
     * Payment failed.
     */
    case FAILED = 'failed';
}
