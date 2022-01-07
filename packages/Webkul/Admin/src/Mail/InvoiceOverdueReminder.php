<?php

namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoiceOverdueReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The customer instance.
     *
     * @var  \Webkul\Customer\Contracts\Customer
     */
    public $customer;

    /**
     * The invoice instance.
     *
     * @var  \Webkul\Sales\Contracts\Invoice
     */
    public $invoice;

    /**
     * Create a new message instance.
     *
     * @param  \Webkul\Customer\Contracts\Customer  $customer
     * @param  \Webkul\Sales\Contracts\Invoice  $invoice
     */
    public function __construct(
        $customer,
        $invoice
    )
    {
        $this->customer = $customer;

        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
                    ->to($this->customer->email)
                    ->subject(trans('shop::app.mail.invoice.reminder.subject'))
                    ->view('shop::emails.customer.invoice-reminder')->with(['customer' => $this->customer, 'invoice' => $this->invoice]);
    }
}