<?php

namespace Webkul\Shop\Mail\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdatePasswordNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param  \Webkul\Customer\Models\Customer  $customer
     * @return void
     */
    public function __construct(public $customer)
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
            ->to($this->customer->email, $this->customer->name)
            ->subject(trans('shop::app.emails.customers.update-password.subject'))
            ->view('shop::emails.customers.update-password', [
                'fullName' => $this->customer->first_name . ' ' . $this->customer->last_name,
            ]);
    }
}