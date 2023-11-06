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
        // Determine email notification configurations
        $notifyAdmin = core()->getConfigData('emails.general.notifications.emails.general.notifications.customer_registration_confirmation_mail_to_admin');
        $notifyCustomer = core()->getConfigData('emails.general.notifications.emails.general.notifications.registration');

        // Initialize the email builder
        $mailBuilder = $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->subject(trans('shop::app.emails.customers.registration.subject'))
            ->view('shop::emails.customers.registration');

        // If both admin and customer notification are enabled
        if ($notifyAdmin && $notifyCustomer) {
            return $mailBuilder->to($this->customer->email)
                ->bcc(core()->getAdminEmailDetails()['email']);
        }

        // If only customer notification is enabled
        if ($notifyCustomer) {
            return $mailBuilder->to($this->customer->email);
        }

        // If only admin notification is enabled
        if ($notifyAdmin) {
            return $mailBuilder->to(core()->getAdminEmailDetails()['email']);
        }

        // If no specific conditions are met
        return;
    }
}
