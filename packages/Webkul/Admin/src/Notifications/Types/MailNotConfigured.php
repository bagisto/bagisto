<?php

namespace Webkul\Admin\Notifications\Types;

class MailNotConfigured extends SystemProblem
{
    /**
     * The icon class shown beside the notification.
     */
    protected string $icon = 'icon-mail';

    /**
     * The email configuration screen.
     */
    public function url(array $data): ?string
    {
        return route('admin.configuration.index', ['slug' => 'emails', 'slug2' => 'configure']);
    }

    /**
     * The translation key segment holding this problem's title and message.
     */
    protected function name(): string
    {
        return 'mail-not-configured';
    }
}
