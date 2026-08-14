<?php

namespace Webkul\PayGlocal\Enums;

/**
 * Transaction statuses returned by the PayGlocal API.
 */
enum PayGlocalPaymentStatus: string
{
    /**
     * The transaction is still executing and the customer is in the payment flow.
     */
    case INPROGRESS = 'INPROGRESS';

    /**
     * The payment was authorized and sent to the acquirer for capture.
     *
     * This is the ONLY success status for a sale. PayGlocal does not return
     * `SUCCESS` or `PAID`.
     */
    case SENT_FOR_CAPTURE = 'SENT_FOR_CAPTURE';

    /**
     * The customer closed the payment window or the session timed out.
     */
    case ABANDONED = 'ABANDONED';

    /**
     * The card issuer declined the payment.
     */
    case ISSUER_DECLINE = 'ISSUER_DECLINE';

    /**
     * The customer cancelled the payment on the PayGlocal page.
     */
    case CUSTOMER_CANCELLED = 'CUSTOMER_CANCELLED';

    /**
     * The customer timed out on the 3-D Secure authentication page.
     */
    case AUTHENTICATION_TIMEOUT = 'AUTHENTICATION_TIMEOUT';

    /**
     * The payment was declined by PayGlocal's risk engine.
     */
    case GENERAL_DECLINE = 'GENERAL_DECLINE';

    /**
     * The request contained a field level error.
     */
    case REQUEST_ERROR = 'REQUEST_ERROR';

    /**
     * PayGlocal encountered an internal error.
     */
    case SYSTEM_ERROR = 'SYSTEM_ERROR';

    /**
     * Determine whether the status represents a captured payment.
     */
    public function isSuccessful(): bool
    {
        return $this === self::SENT_FOR_CAPTURE;
    }

    /**
     * Determine whether the payment is still in flight.
     */
    public function isPending(): bool
    {
        return $this === self::INPROGRESS;
    }

    /**
     * Determine whether the customer cancelled or abandoned the payment.
     */
    public function isCancelled(): bool
    {
        return in_array($this, [self::ABANDONED, self::CUSTOMER_CANCELLED], true);
    }
}
