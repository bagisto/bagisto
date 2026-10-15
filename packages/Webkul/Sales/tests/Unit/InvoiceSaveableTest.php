<?php

use Illuminate\Support\Facades\Schema;
use Webkul\Sales\Repositories\InvoiceRepository;

// ============================================================================
// An Invoice Stays Saveable After Creation
// ============================================================================

it('should leave no attribute on the invoice that its table has no column for', function () {
    $invoice = $this->invoiceOrder($this->createOrder());

    expect(array_diff(array_keys($invoice->getAttributes()), Schema::getColumnListing($invoice->getTable())))
        ->toBe([]);
});

it('should still save an invoice the repository has just created', function () {
    $invoice = $this->invoiceOrder($this->createOrder());

    app(InvoiceRepository::class)->updateState($invoice, 'paid');

    expect($invoice->refresh()->state)->toBe('paid');
});
