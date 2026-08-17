<?php

namespace Webkul\EUWithdrawal\Enums;

class WithdrawalStatus
{
    /**
     * Initial state — declaration recorded, awaiting admin action.
     */
    public const RECEIVED = 'received';

    /**
     * Terminal — refund has been issued (recorded by admin).
     */
    public const REFUNDED = 'refunded';

    /**
     * Terminal — merchant contests entitlement (recorded by admin with reason).
     */
    public const DECLINED = 'declined';

    /**
     * Return every valid status as a flat array.
     */
    public static function all(): array
    {
        return [self::RECEIVED, self::REFUNDED, self::DECLINED];
    }

    /**
     * Check whether the given status is a terminal state.
     */
    public static function isTerminal(string $status): bool
    {
        return in_array($status, [self::REFUNDED, self::DECLINED], true);
    }
}
