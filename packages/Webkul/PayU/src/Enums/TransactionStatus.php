<?php

namespace Webkul\PayU\Enums;

enum TransactionStatus: string
{
    /**
     * The transaction is pending.
     */
    case PENDING = 'pending';

    /**
     * The transaction was successful.
     */
    case SUCCESS = 'success';

    /**
     * The transaction failed.
     */
    case FAILED = 'failed';

    /**
     * The transaction was cancelled.
     */
    case CANCELLED = 'cancelled';
}
