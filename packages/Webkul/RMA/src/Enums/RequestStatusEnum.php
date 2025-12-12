<?php

namespace Webkul\RMA\Enums;

enum RequestStatusEnum: string
{
    /**
     * Accepted request status.
     */
    case ACCEPT = 'Accept';

    /**
     * Canceled request status.
     */
    case CANCELED = 'Canceled';

    /**
     * Declined request status.
     */
    case DECLINED = 'Declined';

    /**
     * Dispatched package request status.
     */
    case DISPATCHED_PACKAGE = 'Dispatched Package';

    /**
     * Item canceled request status.
     */
    case ITEM_CANCELED = 'Item Canceled';

    /**
     * Pending request status.
     */
    case PENDING = 'Pending';

    /**
     * Received package request status.
     */
    case RECEIVED_PACKAGE = 'Received Package';

    /**
     * Solved request status.
     */
    case SOLVED = 'Solved';
}
