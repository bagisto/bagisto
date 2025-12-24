<?php

namespace Webkul\Admin\Mail\Admin\RMA;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Webkul\RMA\Contracts\RMAMessage;

class CustomerToAdminConversationNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public RMAMessage $rmaMessage) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                $this->rmaMessage->rma->order->customer->email,
                $this->rmaMessage->rma->order->customer->name
            ),
            to: [new Address(
                core()->getConfigData('emails.configure.email_settings.admin_email') ?: config('mail.admin.address'),
                core()->getConfigData('emails.configure.email_settings.admin_name') ?: config('mail.admin.name')
            )],
            subject: trans('admin::app.emails.rma.conversation.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin::emails.rma.conversation.message',
        );
    }
}
