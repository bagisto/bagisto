<?php

namespace Webkul\Shop\Mail\Customer\RMA;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerConversationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The conversation data.
     *
     * @var array
     */
    public $conversation;

    /**
     * Create a new message instance.
     *
     * @param  array  $conversation
     * @return void
     */
    public function __construct($conversation)
    {
        $this->conversation = $conversation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($this->conversation['adminEmail'])
            ->subject( trans('shop::app.rma.mail.customer-conversation.subject') )
            ->view('shop::emails.customers.rma.conversation.message')
            ->with($this->conversation);
    }
}