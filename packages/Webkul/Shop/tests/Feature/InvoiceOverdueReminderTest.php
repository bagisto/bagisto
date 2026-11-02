<?php

use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Shop\Mail\Customer\InvoiceOverdueReminder;

/**
 * An overdue invoice on an order placed by a customer with an address of their own.
 */
function overdueInvoice(): Invoice
{
    $order = Order::factory()->create([
        'customer_email' => fake()->unique()->safeEmail(),
        'customer_first_name' => fake()->firstName(),
        'customer_last_name' => fake()->lastName(),
    ]);

    return Invoice::query()->create([
        'order_id' => $order->id,
        'state' => 'overdue',
    ]);
}

it('should send the overdue reminder to the customer who placed the order', function () {
    $invoice = overdueInvoice();

    expect((new InvoiceOverdueReminder($invoice))->hasTo($invoice->order->customer_email))->toBeTrue();
});

it('should not send the overdue reminder to the store sender address', function () {
    $invoice = overdueInvoice();

    expect((new InvoiceOverdueReminder($invoice))->hasTo(core()->getSenderEmailDetails()['email']))->toBeFalse();
});
