<?php

namespace Webkul\Customer\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerUpdatePassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The admin instance.
     *
     * @var  \Webkul\User\Contracts\Admin  $admin
     */
    public $customer;

    /**
     * Create a new message instance.
     *
     * @param  \Webkul\User\Contracts\Admin  $admin
     * @return void
     */
    public function __construct($customer)
    {
        $this->customer = $customer;
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
                    ->subject(trans('shop::app.mail.update-password.subject'))
                    ->view('shop::emails.customer.update-password', ['user' => $this->customer]);
    }
}