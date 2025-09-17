<?php

namespace Webkul\Admin\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BackupCodesMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public $admin,
        public array $backupCodes
    ) {}

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Two-Factor Authentication Backup Codes - '.config('app.name'))
            ->view('admin::emails.admin.backup-codes')
            ->with([
                'admin'       => $this->admin,
                'backupCodes' => $this->backupCodes,
                'appName'     => config('app.name'),
            ]);
    }
}
