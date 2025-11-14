<?php

namespace Webkul\Shop\Mail\Customer\RMA;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerRMAStatusEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The RMA status data.
     *
     * @var array
     */
    public $rmaStatus;

    /**
     * Create a new message instance.
     *
     * @param  array  $rmaStatus
     * @return void
     */
    public function __construct($rmaStatus)
    {
        $this->rmaStatus = $rmaStatus;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($this->rmaStatus['email'])
            ->subject('Status Updated')
            ->view('shop::emails.customers.rma.status')
            ->with($this->rmaStatus);
    }
}