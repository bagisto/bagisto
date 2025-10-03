<?php

namespace Webkul\Admin\Mail\Admin;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Admin\Mail\Mailable;
use Webkul\User\Contracts\Admin;

class BackupCodesNotification extends Mailable
{
    /**
     * Create a new mailable instance.
     */
    public function __construct(public Admin $admin) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address($this->admin->email),
            ],
            subject: trans('admin::app.account.emails.backup-codes.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin::emails.admin.backup-codes',
        );
    }
}
