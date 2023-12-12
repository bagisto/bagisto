<?php

namespace Webkul\Shop\Mail\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RegistrationNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected $senderDetails;

    protected $adminDetails;

    protected $senderEmail;

    protected $senderName;

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
        $this->senderDetails = core()->getSenderEmailDetails();
        $this->adminDetails = core()->getAdminEmailDetails();

        $this->senderEmail = $this->senderDetails['email'];
        $this->senderName = $this->senderDetails['name'];
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
        return $this->from($this->senderEmail, $this->senderName)
            ->to($this->adminDetails['email'])
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
        return $this->from($this->senderEmail, $this->senderName)
            ->to($this->customer->email)
            ->subject(trans('shop::app.emails.customers.registration.subject'))
            ->view('shop::emails.customers.registration');
    }
}
