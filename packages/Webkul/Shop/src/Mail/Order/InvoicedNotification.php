<?php

namespace Webkul\Shop\Mail\Order;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Core\Traits\PDFHandler;
use Webkul\Sales\Contracts\Invoice;
use Webkul\Shop\Mail\Mailable;

class InvoicedNotification extends Mailable
{
    use PDFHandler;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public Invoice $invoice) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(
                    $this->invoice->order->customer_email,
                    $this->invoice->order->customer_full_name
                ),
            ],
            subject: trans('shop::app.emails.orders.invoiced.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'shop::emails.orders.invoiced',
        );
    }

    /**
     * Get the attachments.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        try {
            $orderCurrencyCode = $this->invoice->order->order_currency_code;

            $pdfContent = $this->generateInvoicePdf(
                view('shop::customers.account.orders.pdf', [
                    'invoice' => $this->invoice,
                    'orderCurrencyCode' => $orderCurrencyCode,
                ])->render(),
                'invoice-'.$this->invoice->created_at->format('d-m-Y')
            );

            return [
                Attachment::fromData(
                    fn () => $pdfContent,
                    'invoice-'.$this->invoice->id.'.pdf'
                )->withMime('application/pdf'),
            ];

        } catch (\Exception $e) {
            report($e);

            return [];
        }
    }
}
