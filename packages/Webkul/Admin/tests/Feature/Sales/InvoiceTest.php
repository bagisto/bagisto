<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Shop\Mail\Order\InvoicedNotification;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

/**
 * The markup an invoice is printed from, rendered the way the controller renders it.
 */
function invoiceMarkup(Invoice $invoice): string
{
    return view('shop::customers.account.orders.pdf', [
        'invoice' => $invoice,
        'orderCurrencyCode' => $invoice->order->order_currency_code,
    ])->render();
}

// ============================================================================
// Index
// ============================================================================

it('should return the invoices index page', function () {
    $this->loginAsAdmin();

    get(route('admin.sales.invoices.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.invoices.index.title'));
});

it('should deny guest access to the invoices index page', function () {
    get(route('admin.sales.invoices.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should invoice the ordered quantity, record it on the item and move the order to processing', function () {
    $order = $this->createOrder(items: [['product' => $this->createSimpleProduct(), 'qty_ordered' => 2, 'price' => 100]]);

    $item = $order->items->first();

    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.store', $order->id), [
        'invoice' => ['items' => [$item->id => 2]],
    ])
        ->assertRedirect(route('admin.sales.orders.view', $order->id))
        ->assertSessionHas('success', trans('admin::app.sales.invoices.create.create-success'));

    $invoice = Invoice::query()->where('order_id', $order->id)->firstOrFail();

    expect($invoice)
        ->state->toBe(Invoice::STATUS_PAID)
        ->total_qty->toBe(2)
        ->and((float) $invoice->sub_total)->toBePrice(200)
        ->and((float) $invoice->grand_total)->toBePrice(200);

    $this->assertDatabaseHas('invoice_items', [
        'invoice_id' => $invoice->id,
        'order_item_id' => $item->id,
        'qty' => 2,
    ]);

    $this->assertDatabaseHas('order_items', [
        'id' => $item->id,
        'qty_invoiced' => 2,
    ]);

    $order->refresh();

    expect($order->status)->toBe(Order::STATUS_PROCESSING)
        ->and((float) $order->grand_total_invoiced)->toBePrice(200)
        ->and((float) $order->total_due)->toBePrice(0);
});

it('should complete an order of non-stockable items as soon as it is invoiced', function () {
    $order = $this->createOrder(items: [['product' => $this->createVirtualProduct(), 'qty_ordered' => 1]]);

    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.store', $order->id), [
        'invoice' => ['items' => [$order->items->first()->id => 1]],
    ])
        ->assertRedirect(route('admin.sales.orders.view', $order->id));

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => Order::STATUS_COMPLETED,
    ]);
});

it('should accumulate partial invoices on the order item', function () {
    $order = $this->createOrder(items: [['product' => $this->createSimpleProduct(), 'qty_ordered' => 3]]);

    $item = $order->items->first();

    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.store', $order->id), [
        'invoice' => ['items' => [$item->id => 1]],
    ])->assertRedirect();

    postJson(route('admin.sales.invoices.store', $order->id), [
        'invoice' => ['items' => [$item->id => 2]],
    ])->assertRedirect();

    expect(Invoice::query()->where('order_id', $order->id)->count())->toBe(2);

    $this->assertDatabaseHas('order_items', [
        'id' => $item->id,
        'qty_invoiced' => 3,
    ]);
});

it('should refuse an invoice for more than the quantity left to invoice', function () {
    $order = $this->createOrder(items: [['product' => $this->createSimpleProduct(), 'qty_ordered' => 2]]);

    $this->loginAsAdmin();

    post(route('admin.sales.invoices.store', $order->id), [
        'invoice' => ['items' => [$order->items->first()->id => 3]],
    ])
        ->assertRedirect()
        ->assertSessionHas('error', trans('admin::app.sales.invoices.create.invalid-qty'));

    $this->assertDatabaseMissing('invoices', ['order_id' => $order->id]);
});

it('should refuse an invoice with no quantity at all', function () {
    $order = $this->createOrder();

    $this->loginAsAdmin();

    post(route('admin.sales.invoices.store', $order->id), [
        'invoice' => ['items' => [$order->items->first()->id => 0]],
    ])
        ->assertRedirect()
        ->assertSessionHas('error', trans('admin::app.sales.invoices.create.product-error'));

    $this->assertDatabaseMissing('invoices', ['order_id' => $order->id]);
});

it('should refuse to invoice an order with nothing left to invoice', function () {
    $order = $this->createOrder();

    $this->invoiceOrder($order);

    $this->loginAsAdmin();

    post(route('admin.sales.invoices.store', $order->id), [
        'invoice' => ['items' => [$order->items->first()->id => 1]],
    ])
        ->assertRedirect()
        ->assertSessionHas('error', trans('admin::app.sales.invoices.create.creation-error'));

    expect(Invoice::query()->where('order_id', $order->id)->count())->toBe(1);
});

it('should store an invoice and send email notification', function () {
    Mail::fake();

    $this->setConfig('emails.general.notifications.emails.general.notifications.new_invoice', 1);

    $order = $this->createOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.store', $order->id), [
        'invoice' => ['items' => [$order->items->first()->id => 1]],
    ])
        ->assertRedirect();

    $this->assertDatabaseHas('invoices', ['order_id' => $order->id]);

    Mail::assertQueued(InvoicedNotification::class, fn (InvoicedNotification $mail) => $mail->hasTo($order->customer_email));
});

it('should fail validation when invoice items are missing on store', function () {
    $order = $this->createOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.store', $order->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('invoice.items');
});

it('should fail validation when invoice item quantity is not numeric', function () {
    $order = $this->createOrder();

    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.store', $order->id), [
        'invoice' => ['items' => [$order->items->first()->id => 'invalid']],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('invoice.items.'.$order->items->first()->id);
});

// ============================================================================
// View
// ============================================================================

it('should return the invoice view page', function () {
    $order = $this->createOrder();

    $invoice = $this->invoiceOrder($order);

    $this->loginAsAdmin();

    get(route('admin.sales.invoices.view', $invoice->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.invoices.view.title', ['invoice_id' => $invoice->increment_id]))
        ->assertSeeText($order->customer_email);
});

// ============================================================================
// Print
// ============================================================================

it('should download the invoice as a pdf', function () {
    $invoice = $this->invoiceOrder($this->createOrder());

    $this->loginAsAdmin();

    get(route('admin.sales.invoices.print', $invoice->id))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf')
        ->assertDownload('invoice-'.$invoice->created_at->format('d-m-Y').'.pdf');
});

it('should show the saved logo on the invoice, whatever size it was saved at', function () {
    $logo = UploadedFile::fake()->image('logo.png', 3000, 2000)->get();

    Storage::disk(config('filesystems.default'))->put('configurations/logo.png', $logo);

    $this->setConfig('sales.invoice_settings.pdf_print_outs.logo', 'configurations/logo.png');

    expect(invoiceMarkup($this->invoiceOrder($this->createOrder())))
        ->toContain(base64_encode($logo));
});

it('should fall back to the default logo when the store has saved none', function () {
    expect(invoiceMarkup($this->invoiceOrder($this->createOrder())))
        ->toContain('data:image/png;base64,iVBORw0KGgo');
});

it('should fall back to the default logo when the saved one is no longer on disk', function () {
    $this->setConfig('sales.invoice_settings.pdf_print_outs.logo', 'configurations/deleted.png');

    expect(invoiceMarkup($this->invoiceOrder($this->createOrder())))
        ->toContain('data:image/png;base64,iVBORw0KGgo');
});

// ============================================================================
// Duplicate Email
// ============================================================================

it('should send a duplicate invoice to the given email address', function () {
    Event::fake();

    $invoice = $this->invoiceOrder($this->createOrder());

    $this->loginAsAdmin();

    post(route('admin.sales.invoices.send_duplicate_email', $invoice->id), [
        'email' => $email = fake()->safeEmail(),
    ])
        ->assertRedirect(route('admin.sales.invoices.view', $invoice->id))
        ->assertSessionHas('success', trans('admin::app.sales.invoices.view.invoice-sent'));

    Event::assertDispatched(
        'sales.invoice.send_duplicate_email',
        fn ($event, array $payload) => $payload['invoice']->is($invoice) && $payload['duplicate_invoice_email'] === $email
    );
});

it('should fail validation when the duplicate invoice email is invalid', function () {
    $invoice = $this->invoiceOrder($this->createOrder());

    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.send_duplicate_email', $invoice->id), [
        'email' => 'not-an-email',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('email');
});

// ============================================================================
// Mass Update
// ============================================================================

it('should deny a low-privilege admin from mass updating invoice state', function () {
    $invoice = $this->invoiceOrder($this->createOrder(), state: Invoice::STATUS_PENDING);

    $this->loginAsAdminWithPermissions(['dashboard']);

    postJson(route('admin.sales.invoices.mass_update.state'), [
        'indices' => [$invoice->id],
        'value' => Invoice::STATUS_PAID,
    ])
        ->assertUnauthorized();

    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
        'state' => Invoice::STATUS_PENDING,
    ]);
});

it('should allow an authorized admin to mass update invoice state', function () {
    $invoice = $this->invoiceOrder($this->createOrder(), state: Invoice::STATUS_PENDING);

    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.mass_update.state'), [
        'indices' => [$invoice->id],
        'value' => Invoice::STATUS_PAID,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.sales.invoices.index.datagrid.mass-update-success'));

    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
        'state' => Invoice::STATUS_PAID,
    ]);
});

it('should fail validation when mass updating invoice state to an invalid value', function () {
    $invoice = $this->invoiceOrder($this->createOrder(), state: Invoice::STATUS_PENDING);

    $this->loginAsAdmin();

    postJson(route('admin.sales.invoices.mass_update.state'), [
        'indices' => [$invoice->id],
        'value' => 'hacked',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('value');

    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
        'state' => Invoice::STATUS_PENDING,
    ]);
});
