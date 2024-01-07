<?php

namespace Webkul\Admin\Mail\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $adminName;

    /**
     * Create a new mailable instance.
     *
     * @param  \Webkul\Customer\Contracts\Customer  $customer
     * @return void
     */
    public function __construct(
        public $customer
    ) {
        $this->adminName = core()->getAdminEmailDetails()['name'];
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
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to(core()->getAdminEmailDetails()['email'])
            ->subject(trans('admin::app.emails.admin.registration.subject'))
            ->view('admin::emails.admin.notify-admin-about-registration');
    }
}
