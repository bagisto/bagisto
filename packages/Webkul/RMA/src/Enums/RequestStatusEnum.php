<?php

namespace Webkul\RMA\Enums;

enum RequestStatusEnum: string
{
    /**
     * Pending request status.
     */
    case PENDING = 'Pending';

    /**
     * Canceled request status.
     */
    case CANCELED = 'Canceled';

    /**
     * Item canceled request status.
     */
    case ITEM_CANCELED = 'Item Canceled';

    /**
     * Declined request status.
     */
    case DECLINED = 'Declined';

    /**
     * Received package request status.
     */
    case RECEIVED_PACKAGE = 'Received Package';

    /**
     * Solved request status.
     */
    case SOLVED = 'Solved';

    /**
     * Accepted request status.
     */
    case ACCEPT = 'Accept';
}
