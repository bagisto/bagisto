<?php

namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewCustomerNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     * 
     * @param  \Webkul\Customer\Contracts\Customer  $order
     * @param  string  $password
     * @return void
     */
    public function __construct(
        public $customer,
        public $password
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
            ->subject(trans('shop::app.mail.customer.new.subject'))
            ->view('shop::emails.customer.new-customer')->with([
                'customer' => $this->customer,
                'password' => $this->password,
            ]);
    }
}