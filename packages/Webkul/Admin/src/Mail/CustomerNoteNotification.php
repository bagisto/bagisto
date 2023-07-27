<?php

namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerNoteNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param  \Webkul\Sales\Contracts\OrderComment  $note
     * @return void
     */
    public function __construct(public $customer, public $note)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        dd($this->note , $this->customer);

        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($this->customer->customer_email, $this->note->customer->customer_full_name)
            ->subject(trans('shop::app.mail.order.comment.subject', ['customer_id' => $this->note->customer->id]))
            ->view('shop::emails.sales.new-order-comment');
    }
}
