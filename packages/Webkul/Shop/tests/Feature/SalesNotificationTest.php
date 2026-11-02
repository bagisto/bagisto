<?php

use Illuminate\Support\Facades\Mail;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\Refund;
use Webkul\Sales\Models\Shipment;
use Webkul\Shop\Listeners\Invoice as InvoiceListener;
use Webkul\Shop\Listeners\Refund as RefundListener;
use Webkul\Shop\Listeners\Shipment as ShipmentListener;

/**
 * A not yet emailed invoice, shipment or refund for a fresh order, with one item in the customer's locale.
 */
function unsentSalesDocument(string $model): object
{
    $document = $model::query()->create([
        'order_id' => Order::factory()->create()->id,
        'email_sent' => false,
    ]);

    $document->items()->create(['additional' => ['locale' => 'en']]);

    return $document->refresh();
}

it('should mark only the document it mails as emailed, leaving every other one as it was', function (string $model, string $listener) {
    Mail::fake();

    $earlier = unsentSalesDocument($model);

    $document = unsentSalesDocument($model);

    app($listener)->afterCreated($document);

    expect((bool) $document->fresh()->email_sent)->toBeTrue()
        ->and((bool) $earlier->fresh()->email_sent)->toBeFalse();
})->with([
    'invoice' => [Invoice::class, InvoiceListener::class],
    'shipment' => [Shipment::class, ShipmentListener::class],
    'refund' => [Refund::class, RefundListener::class],
]);
