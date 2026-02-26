<?php

namespace Webkul\Core\Traits;

use Illuminate\Support\Facades\Config;

trait HasMailConfiguration
{
    /**
     * Laravel calls this automatically when building mailables.
     */
    public function build(): static
    {
        $this->applyConfiguredSmtpSettings();

        return $this->buildChildMessage();
    }

    /**
     * Child classes override this for their specific content.
     */
    protected function buildChildMessage()
    {
        $sender = core()->getSenderEmailDetails();

        $address = $this->from[0]['address'] ?? $sender['email'];
        $name = $this->from[0]['name'] ?? $sender['name'] ?? config('mail.from.name');

        $this->from($address, $name);

        return $this;
    }

    /**
     * Apply configured SMTP settings to Laravel mail config at runtime.
     */
    protected function applyConfiguredSmtpSettings(): void
    {
        if (! core()->getConfigData('emails.smtp.smtp_settings.smtp_enable')) {
            return;
        }

        $smtpHost = core()->getConfigData('emails.smtp.smtp_settings.smtp_host');
        $smtpPort = core()->getConfigData('emails.smtp.smtp_settings.smtp_port');

        if (empty($smtpHost) || empty($smtpPort)) {
            return;
        }

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $smtpHost);
        Config::set('mail.mailers.smtp.port', (int) $smtpPort);
        Config::set('mail.mailers.smtp.username', core()->getConfigData('emails.smtp.smtp_settings.smtp_username'));
        Config::set('mail.mailers.smtp.password', core()->getConfigData('emails.smtp.smtp_settings.smtp_password'));
        Config::set('mail.mailers.smtp.encryption', core()->getConfigData('emails.smtp.smtp_settings.smtp_encryption'));
    }
}
