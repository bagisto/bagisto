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
    public function __construct(public $customer, public $notify)
    {
        $this->senderDetails = core()->getSenderEmailDetails();
        $this->adminDetails = core()->getAdminEmailDetails();

        $this->senderEmail = $this->senderDetails['email'];
        $this->senderName = $this->senderDetails['name'];
    }

    /**
     * Build the registration notification message.
     *
     * This method is responsible for building the registration notification message.
     * It determines whether to send the notification to the admin or the customer based on the $notify value.
     * If the $notify value is 'admin', it calls the sendToAdmin() method; if it's 'customer', it calls the sendToCustomer() method.
     * If $notify value doesn't match 'admin' or 'customer', it logs an error.
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
     * Build and send the registration notification to the admin.
     *
     * This method constructs the email message to notify the admin about a customer registration.
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
     * Build and send the registration notification to the customer.
     *
     * This method constructs the email message to notify the customer about a successful registration.
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
