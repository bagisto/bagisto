<?php

namespace Webkul\Admin\SystemHealth\Checks;

use Webkul\Core\Mail\Transport\DynamicMailTransport;

class MailConfigurationCheck extends AbstractCheck
{
    /**
     * Bagisto's own mailer, configured from the admin panel.
     */
    protected const DYNAMIC_MAILER = 'bagisto-dynamic-smtp';

    /**
     * The hosts a fresh install points at, where no mail server answers in production.
     */
    protected const LOOPBACK_HOSTS = ['127.0.0.1', 'localhost', '::1'];

    /**
     * The translation key segment naming what this check looks after.
     */
    public function name(): string
    {
        return 'mail';
    }

    /**
     * Whether Bagisto's mailer is in use without the settings it needs to send.
     */
    public function failing(): bool
    {
        if (config('mail.default') !== self::DYNAMIC_MAILER) {
            return false;
        }

        if (core()->getConfigData('emails.configure.smtp.driver') === DynamicMailTransport::DRIVER_BREVO_API) {
            return empty(core()->getConfigData('emails.configure.smtp.brevo_api_key'));
        }

        $host = core()->getConfigData('emails.configure.smtp.host') ?? config('mail.mailers.smtp.host');

        if (empty($host)) {
            return true;
        }

        return app()->isProduction()
            && in_array($host, self::LOOPBACK_HOSTS, true);
    }
}
