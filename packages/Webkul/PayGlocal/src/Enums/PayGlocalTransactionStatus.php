<?php

namespace Webkul\PayGlocal\Enums;

enum PayGlocalTransactionStatus: string
{
    /**
     * Payment initiated with PayGlocal but not yet settled.
     */
    case PENDING = 'pending';

    /**
     * Payment captured and turned into an order.
     */
    case COMPLETED = 'completed';

    /**
     * Payment failed or was declined.
     */
    case FAILED = 'failed';

    /**
     * Payment was cancelled or abandoned by the customer.
     */
    case CANCELLED = 'cancelled';
}
