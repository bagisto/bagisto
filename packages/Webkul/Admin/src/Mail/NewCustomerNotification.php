<?php

namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * New Admin Mail class
 *
 * @author    Rahul Shukla <rahulshukla.symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewCustomerNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The customer instance.
     *
     * @var  \Webkul\Customer\Contracts\Customer
     */
    public $customer;

    /**
     * The password instance.
     *
     * @var string
     */
    public $password;

    /**
     * Create a new message instance.
     * 
     * @param  \Webkul\Customer\Contracts\Customer  $order
     * @param  string                               $password
     * @return void
     */
    public function __construct(
        $customer,
        $password
    )
    {
        $this->customer = $customer;

        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->customer->email)
                    ->subject(trans('shop::app.mail.customer.new.subject'))
                    ->view('shop::emails.customer.new-customer')->with(['customer' => $this->customer, 'password' => $this->password]);
    }
}