<?php

namespace Webkul\Stripe\Enums;

enum StripeTransactionStatus: string
{
    /**
     * Payment session created but not yet completed.
     */
    case PENDING = 'pending';

    /**
     * Payment successfully completed and order created.
     */
    case COMPLETED = 'completed';

    /**
     * Payment failed or encountered an error.
     */
    case FAILED = 'failed';

    /**
     * Payment was cancelled by the user.
     */
    case CANCELLED = 'cancelled';
}
