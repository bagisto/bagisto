<?php

namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DuplicateInvoiceNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The invoice instance.
     *
     * @var  \Webkul\Customer\Contracts\Invoice
     */
    public $invoice;

    /**
     * Customer email.
     *
     * @var string
     */
    public $customerEmail;

    /**
     * Create a new message instance.
     *
     * @param  \Webkul\Customer\Contracts\Invoice  $invoice
     * @param  string  $customerEmail
     * @return void
     */
    public function __construct($invoice, $customerEmail)
    {
        $this->invoice = $invoice;

        $this->customerEmail = $customerEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = $this->invoice->order;

        $email = $this->customerEmail ?? $order->customer_email;

        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($email, $order->customer_full_name)
            ->subject(trans('shop::app.mail.invoice.subject', ['order_id' => $order->increment_id]))
            ->view('shop::emails.sales.new-invoice');
    }
}
