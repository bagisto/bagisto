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
     * Create a new message instance.
     *
     * @param  \Webkul\Customer\Contracts\Customer  $customer
     * @param  \Webkul\Sales\Contracts\Invoice  $invoice
     * @return void
     */
    public function __construct(
        public $customer,
        public $invoice
    )
    {

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
            ->view('shop::emails.customer.invoice-reminder')->with([
                'customer' => $this->customer,
                'invoice'  => $this->invoice,
            ]);
    }
}