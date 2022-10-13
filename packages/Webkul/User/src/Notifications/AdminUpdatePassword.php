<?php

namespace Webkul\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminUpdatePassword extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * Create a new admin instance.
     *
     * @param  \Webkul\User\Contracts\Admin  $admin
     * @return void
     */
    public function __construct(public $admin)
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
            ->to($this->admin->email, $this->admin->name)
            ->subject(trans('shop::app.mail.update-password.subject'))
            ->view('shop::emails.admin.update-password', ['user' => $this->admin]);
    }
}