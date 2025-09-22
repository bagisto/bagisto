<?php

namespace Webkul\Admin\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Webkul\User\Models\Admin;

class BackupCodesNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Admin $admin) {}

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this
            ->subject(trans('admin::app.account.emails.backup-codes.subject').config('app.name'))
            ->view('admin::emails.admin.backup-codes')
            ->with([
                'appName' => config('app.name'),
            ]);
    }
}
