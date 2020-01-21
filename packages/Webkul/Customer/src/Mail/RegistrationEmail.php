<?php

namespace Webkul\Customer\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Registration Mail class
 *
 * @author    Prateek Srivastava
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class RegistrationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data) {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       return $this->to($this->data['email'])
                ->subject(trans('shop::app.mail.customer.registration.customer-registration'))
                ->view('shop::emails.customer.registration')->with('data', $this->data);
    }
}