<?php

namespace Webkul\Admin\Mail\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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
    ) {
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
            ->subject(trans('shop::app.emails.customers.registration.subject'))
            ->view('shop::emails.customers.new-customer')->with([
                'customer' => $this->customer,
                'password' => $this->password,
            ]);
    }
}
