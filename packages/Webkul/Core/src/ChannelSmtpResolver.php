<?php

namespace Webkul\Core;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ChannelSmtpResolver
{
    private const SMTP_CONFIG_PATH = 'emails.smtp.smtp_settings';

    private const MAILER_KEYS = ['mailer', 'swift.mailer', 'mail.mailer'];

    /**
     * Apply channel-specific SMTP configuration
     */
    public function applyChannelSmtpConfig(?string $channelCode = null): void
    {
        $channelCode ??= $this->resolveChannelCode();

        if (! $channelCode || ! $this->isSmtpEnabled($channelCode)) {
            return;
        }

        $smtpConfig = $this->getSmtpConfig($channelCode);

        if (! $this->isValidSmtpConfig($smtpConfig)) {
            return;
        }

        $this->setSmtpMailerConfig($smtpConfig);
        $this->clearResolvedMailer();
    }

    private function resolveChannelCode(): ?string
    {
        return core()->getRequestedChannelCode()
            ?: core()->getDefaultChannelCode();
    }

    private function isSmtpEnabled(string $channelCode): bool
    {
        return (bool) $this->getConfig('smtp_enable', $channelCode);
    }

    private function getSmtpConfig(string $channelCode): array
    {
        return [
            'host' => $this->getConfig('smtp_host', $channelCode),
            'port' => $this->getConfig('smtp_port', $channelCode),
            'username' => $this->getConfig('smtp_username', $channelCode),
            'password' => $this->getConfig('smtp_password', $channelCode),
            'encryption' => $this->getConfig('smtp_encryption', $channelCode),
        ];
    }

    private function isValidSmtpConfig(array $config): bool
    {
        if (empty($config['host']) || empty($config['port'])) {
            Log::warning('Channel SMTP configuration incomplete. Host or Port is missing.');

            return false;
        }

        return true;
    }

    private function setSmtpMailerConfig(array $config): void
    {
        $existingConfig = Config::get('mail.mailers.smtp', []);

        Config::set('mail.mailers.smtp', array_merge($existingConfig, [
            'transport' => 'smtp',
            'host' => $config['host'],
            'port' => (int) $config['port'],
            'username' => $config['username'],
            'password' => $config['password'],
            'encryption' => $config['encryption'] ?: null,
        ]));

        Config::set('mail.default', 'smtp');
    }

    private function clearResolvedMailer(): void
    {
        foreach (self::MAILER_KEYS as $key) {
            if (app()->bound($key)) {
                app()->forgetInstance($key);
            }
        }
    }

    private function getConfig(string $key, string $channelCode): mixed
    {
        return core()->getConfigData(self::SMTP_CONFIG_PATH.'.'.$key, $channelCode);
    }
}
