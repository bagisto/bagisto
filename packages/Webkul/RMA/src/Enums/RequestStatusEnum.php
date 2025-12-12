<?php

namespace Webkul\RMA\Enums;

enum RequestStatusEnum: string
{
    /**
     * Pending request status.
     */
    case PENDING = 'pending';

    /**
     * Canceled request status.
     */
    case CANCELED = 'canceled';

    /**
     * Item canceled request status.
     */
    case ITEM_CANCELED = 'item_canceled';

    /**
     * Declined request status.
     */
    case DECLINED = 'declined';

    /**
     * Received package request status.
     */
    case RECEIVED_PACKAGE = 'received_package';

    /**
     * Solved request status.
     */
    case SOLVED = 'solved';
}
