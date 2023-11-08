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
        // Retrieve sender and admin email details
        $senderDetails = core()->getSenderEmailDetails();
        $adminDetails = core()->getAdminEmailDetails();

        // Extract necessary sender details
        $senderEmail = $senderDetails['email'];
        $senderName = $senderDetails['name'];

        // Compose and send the email
        return  $this->from($senderEmail, $senderName)
                ->to($this->customer->email)
                ->bcc($adminDetails['email'])
                ->subject(trans('shop::app.emails.customers.registration.subject'))
                ->view('shop::emails.customers.registration');
                
    }
}
