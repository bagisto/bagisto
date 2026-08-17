<?php

namespace Webkul\EUWithdrawal\Events;

use Webkul\EUWithdrawal\Models\Withdrawal;

class WithdrawalReceived
{
    /**
     * Create a new event instance.
     */
    public function __construct(public Withdrawal $withdrawal) {}
}
