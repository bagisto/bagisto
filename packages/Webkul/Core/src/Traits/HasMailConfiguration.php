<?php

namespace Webkul\Core\Traits;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

trait HasMailConfiguration
{
    private ?string $channelMailerName = null;

    /**
     * Laravel calls this automatically when building mailables.
     */
    public function build(): static
    {
        $this->configureForChannel();

        if ($this->channelMailerName) {
            $this->mailer($this->channelMailerName);
        }

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
     * Configure channel-specific mailer (same as before).
     */
    protected function configureForChannel(): static
    {
        $channelCode = $this->resolveChannelCode();

        if (! $channelCode || ! $this->isSmtpEnabled($channelCode)) {
            return $this;
        }

        $smtpConfig = $this->getSmtpConfig($channelCode);

        $this->channelMailerName = $this->createChannelMailer($channelCode, $smtpConfig);

        return $this;
    }

    private function createChannelMailer(string $channelCode, array $config): string
    {
        $mailerName = 'smtp-channel-'.Str::slug($channelCode);

        $mailerConfig = [
            'transport' => 'smtp',
            'host' => $config['host'],
            'port' => (int) $config['port'],
            'username' => $config['username'],
            'password' => $config['password'],
            'encryption' => $config['encryption'] ?: null,
            'timeout' => 30,
        ];

        Config::set("mail.mailers.{$mailerName}", $mailerConfig);
        Mail::mailer($mailerName);

        return $mailerName;
    }

    private function resolveChannelCode(): ?string
    {
        return core()->getRequestedChannelCode() ?: core()->getDefaultChannelCode();
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

    private function getConfig(string $key, string $channelCode): mixed
    {
        return core()->getConfigData('emails.smtp.smtp_settings.'.$key, $channelCode);
    }
}
