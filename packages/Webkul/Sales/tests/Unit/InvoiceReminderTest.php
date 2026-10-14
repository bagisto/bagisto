<?php

use Illuminate\Support\Facades\Mail;
use Webkul\Sales\Models\Invoice;
use Webkul\Shop\Mail\Customer\InvoiceOverdueReminder;

/**
 * An invoice sitting past its due date, read back clean so the reminder can be sent off it.
 */
function overdueInvoice(int $reminders = 0): Invoice
{
    $invoice = test()->invoiceOrder(test()->createOrder(), [], 'overdue');

    Invoice::query()->where('id', $invoice->id)->update([
        'reminders' => $reminders,
        'next_reminder_at' => null,
    ]);

    return Invoice::query()->findOrFail($invoice->id);
}

// ============================================================================
// Sending The Reminder
// ============================================================================

it('should send the overdue reminder through the storefront listener', function () {
    Mail::fake();

    overdueInvoice()->sendInvoiceReminder();

    Mail::assertQueued(InvoiceOverdueReminder::class);
});

it('should record the reminder it sent and when the next one is due', function () {
    Mail::fake();

    $invoice = overdueInvoice();

    $invoice->sendInvoiceReminder();

    expect((int) $invoice->refresh()->reminders)->toBe(1)
        ->and($invoice->next_reminder_at)->not->toBeNull();
});

it('should stop reminding once the configured limit is reached', function () {
    $this->setConfig(['sales.invoice_settings.invoice_reminders.reminders_limit' => 2]);

    Mail::fake();

    overdueInvoice(reminders: 2)->sendInvoiceReminder();

    Mail::assertNotQueued(InvoiceOverdueReminder::class);
});

it('should keep reminding while it is under the limit', function () {
    $this->setConfig(['sales.invoice_settings.invoice_reminders.reminders_limit' => 3]);

    Mail::fake();

    overdueInvoice(reminders: 1)->sendInvoiceReminder();

    Mail::assertQueued(InvoiceOverdueReminder::class);
});
