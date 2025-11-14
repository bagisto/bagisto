<?php

namespace Webkul\Admin\Mail\Admin\RMA;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminConversationEmail extends Mailable
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
            ->to($this->conversation['customerEmail'])
            ->subject( trans('shop::app.rma.mail.seller-conversation.subject') )
            ->view('admin::emails.admin.rma.conversation.message')
            ->with($this->conversation);
    }
}