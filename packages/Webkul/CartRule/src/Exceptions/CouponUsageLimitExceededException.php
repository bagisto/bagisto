<?php

namespace Webkul\CartRule\Exceptions;

use RuntimeException;

/**
 * Thrown when a coupon's usage limit has been reached during order placement.
 */
class CouponUsageLimitExceededException extends RuntimeException {}
