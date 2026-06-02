<?php

namespace Webkul\EUWithdrawal\Observers;

use RuntimeException;
use Webkul\EUWithdrawal\Models\Withdrawal;

class WithdrawalObserver
{
    /**
     * Reject any update that touches an evidence column.
     *
     * Operational columns (status, confirmation_sent_at, declined_*, refunded_*,
     * refund_note, confirmation_error) remain freely mutable for admin actions.
     *
     * @throws RuntimeException
     */
    public function updating(Withdrawal $withdrawal): void
    {
        foreach (Withdrawal::EVIDENCE_COLUMNS as $column) {
            if ($withdrawal->isDirty($column)) {
                throw new RuntimeException(
                    "Withdrawal evidence column '{$column}' is immutable after insert."
                );
            }
        }
    }

    /**
     * Reject any attempt to delete a withdrawal via Eloquent.
     *
     * Records may only be removed via the dedicated retention purge command,
     * which anonymises PII fields rather than dropping the row entirely.
     *
     * @throws RuntimeException
     */
    public function deleting(Withdrawal $withdrawal): void
    {
        throw new RuntimeException(
            'Withdrawal records are append-only and cannot be deleted via Eloquent. '
            .'Use the eu-withdrawal:purge command for retention-based anonymisation.'
        );
    }
}
