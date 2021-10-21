<?php

namespace Webkul\Customer\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Mail type i.e. `customer` or `admin`.
     *
     * @var array
     */
    protected $mailType = ['customer', 'admin'];

    /**
     * Selected mail type.
     *
     * @var string
     */
    public $selectedMailType;

    /**
     * Request data.
     *
     * @var array
     */
    public $data;

    /**
     * Create a new mailable instance.
     *
     * @param  array  $data
     * @param  string  $mailType
     * @return void
     */
    public function __construct($data, $mailType)
    {
        $this->data = $data;

        $this->selectedMailType = in_array($mailType, $this->mailType) ? $mailType : 'customer';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->selectedMailType === 'customer') {
            return $this->mailToCustomer();
        }

        return $this->mailToAdmin();
    }

    /**
     * Mail to customer.
     *
     * @return $this
     */
    public function mailToCustomer()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($this->data['email'])
            ->subject(trans('shop::app.mail.customer.registration.customer-registration'))
            ->view('shop::emails.customer.registration')->with('data', $this->data);
    }

    /**
     * Mail to admin.
     *
     * @return $this
     */
    public function mailToAdmin()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to(core()->getAdminEmailDetails()['email'])
            ->subject(trans('shop::app.mail.customer.registration.customer-registration'))
            ->view('shop::emails.admin.registration')->with('data', $this->data);
    }
}
