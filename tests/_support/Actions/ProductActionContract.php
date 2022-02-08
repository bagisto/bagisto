<?php

namespace Actions;

interface ProductActionContract
{
    /**
     * Simple product code.
     *
     * @var int
     */
    public const SIMPLE_PRODUCT = 1;

    /**
     * Virtual product code.
     *
     * @var int
     */
    public const VIRTUAL_PRODUCT = 2;

    /**
     * Downloadable product code.
     *
     * @var int
     */
    public const DOWNLOADABLE_PRODUCT = 3;

    /**
     * Booking product code.
     *
     * @var int
     */
    public const BOOKING_EVENT_PRODUCT = 4;
}
