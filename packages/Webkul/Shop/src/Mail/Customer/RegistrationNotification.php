<?php

namespace Webkul\Shop\Mail\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RegistrationNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new mailable instance.
     *
     * @param  \Webkul\Customer\Contracts\Customer  $customer
     * @param  string  $notify
     * @return void
     */
    public function __construct(
        public $customer,
        public $notify
    ) {
    }

    /**
     * Build & send registration notification.
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function build()
    {
        if ($this->notify === 'admin') {
            return $this->sendToAdmin();
        } elseif ($this->notify === 'customer') {
            return $this->sendToCustomer();
        }

        // Default action if $notify value doesn't match 'admin' or 'customer'
        Log::error('Invalid notification target: ' . $this->notify);
    }

    /**
     * Notify admin with email.
     *
     * @return $this
     */
    protected function sendToAdmin()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to(core()->getAdminEmailDetails()['email'])
            ->subject(trans('shop::app.emails.customers.admin.registration.subject'))
            ->view('shop::emails.customers.notify-admin-about-registration');
    }

    /**
     * Notify customer with email.
     *
     * @return $this
     */
    protected function sendToCustomer()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($this->customer->email)
            ->subject(trans('shop::app.emails.customers.registration.subject'))
            ->view('shop::emails.customers.registration');
    }
}
