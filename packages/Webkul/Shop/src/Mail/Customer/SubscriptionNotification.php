<?php

namespace Webkul\Shop\Mail\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriptionNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a mailable instance
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
            ->to($this->customer->email)
            ->subject(trans('shop::app.emails.customers.subscribed.subject'))
            ->view('shop::emails.customers.subscribed', [
                'fullName' => $this->customer->first_name . ' ' . $this->customer->last_name,
            ]);
    }
}