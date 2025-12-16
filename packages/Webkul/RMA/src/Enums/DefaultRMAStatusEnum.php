<?php

namespace Webkul\RMA\Enums;

enum DefaultRMAStatusEnum: int
{
    /**
     * Pending request status.
     */
    case PENDING = 1;

    /**
     * Accepted request status.
     */
    case ACCEPT = 2;

    /**
     * Awaiting request status.
     */
    case AWAITING = 3;

    /**
     * Dispatched package request status.
     */
    case DISPATCHED_PACKAGE = 4;

    /**
     * Received package request status.
     */
    case RECEIVED_PACKAGE = 5;

    /**
     * Solved request status.
     */
    case SOLVED = 6;

    /**
     * Declined request status.
     */
    case DECLINED = 7;

    /**
     * Item canceled request status.
     */
    case ITEM_CANCELED = 8;

    /**
     * Canceled request status.
     */
    case CANCELED = 9;
}
