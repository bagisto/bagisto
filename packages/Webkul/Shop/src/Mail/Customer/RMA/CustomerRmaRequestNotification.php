<?php

namespace Webkul\Shop\Mail\Customer\RMA;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerRmaRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The RMA data.
     *
     * @var array
     */
    public $customerRmaData;

    /**
     * Create a new message instance.
     *
     * @param  array  $customerRmaData
     * @return void
     */
    public function __construct($customerRmaData)
    {
        $this->customerRmaData = $customerRmaData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->customerRmaData['email'])
            ->from(core()->getAdminEmailDetails()['email'])
            ->subject('New RMA Request')
            ->view('shop::emails.customers.rma.new-rma-request', [
                'customerRmaData' => $this->customerRmaData,
            ]);
    }
}