<?php

namespace Webkul\ChannelSmtp\Mail;

class ChannelSmtpConfigApplier
{
    /**
     * Default mailer configuration cache.
     *
     * @var array<string, array<string, mixed>>
     */
    protected array $defaultMailers = [];

    /**
     * Default "from" configuration cache.
     *
     * @var array<string, string|null>
     */
    protected array $defaultFrom = [];

    /**
     * Last applied signature.
     */
    protected ?string $lastAppliedSignature = null;

    /**
     * Apply channel SMTP config to the selected mailer.
     */
    public function apply(string $mailerName): bool
    {
        $this->cacheDefaults($mailerName);

        $channelCode = core()->getRequestedChannelCode();

        $smtpConfiguration = $this->resolveChannelSmtpConfiguration($channelCode);

        $signature = md5(json_encode([$mailerName, $channelCode, $smtpConfiguration]) ?: '');

        if ($signature === $this->lastAppliedSignature) {
            return false;
        }

        $this->lastAppliedSignature = $signature;

        $this->resetToDefaults($mailerName);

        if (! $smtpConfiguration['enabled']) {
            return true;
        }

        $baseMailerConfiguration = $this->defaultMailers[$mailerName] ?? [];

        $channelMailerConfiguration = array_filter([
            ...$baseMailerConfiguration,
            'transport' => 'smtp',
            'host' => $smtpConfiguration['host'],
            'port' => $smtpConfiguration['port'],
            'encryption' => $smtpConfiguration['encryption'],
            'username' => $smtpConfiguration['username'],
            'password' => $smtpConfiguration['password'],
            'timeout' => $smtpConfiguration['timeout'],
        ], static fn ($value): bool => $value !== null);

        config([
            "mail.mailers.{$mailerName}" => $channelMailerConfiguration,
            'mail.from.address' => $smtpConfiguration['from_address'],
            'mail.from.name' => $smtpConfiguration['from_name'],
        ]);

        return true;
    }

    /**
     * Cache default mail values only once.
     */
    protected function cacheDefaults(string $mailerName): void
    {
        if (! isset($this->defaultMailers[$mailerName])) {
            $this->defaultMailers[$mailerName] = config("mail.mailers.{$mailerName}", []);
        }

        if (! $this->defaultFrom) {
            $this->defaultFrom = [
                'address' => config('mail.from.address'),
                'name' => config('mail.from.name'),
            ];
        }
    }

    /**
     * Restore cached default mail config.
     */
    protected function resetToDefaults(string $mailerName): void
    {
        config([
            "mail.mailers.{$mailerName}" => $this->defaultMailers[$mailerName] ?? [],
            'mail.from.address' => $this->defaultFrom['address'] ?? null,
            'mail.from.name' => $this->defaultFrom['name'] ?? null,
        ]);
    }

    /**
     * Resolve channel SMTP configuration values.
     *
     * @return array<string, bool|int|string|null>
     */
    protected function resolveChannelSmtpConfiguration(string $channelCode): array
    {
        $enabled = (bool) core()->getConfigData('general.smtp.settings.enabled', $channelCode);

        return [
            'enabled' => $enabled,
            'host' => core()->getConfigData('general.smtp.settings.host', $channelCode),
            'port' => $this->normalizePort(core()->getConfigData('general.smtp.settings.port', $channelCode)),
            'encryption' => $this->normalizeEncryption(core()->getConfigData('general.smtp.settings.encryption', $channelCode)),
            'username' => core()->getConfigData('general.smtp.settings.username', $channelCode),
            'password' => core()->getConfigData('general.smtp.settings.password', $channelCode),
            'timeout' => $this->normalizePort(core()->getConfigData('general.smtp.settings.timeout', $channelCode)),
            'from_name' => core()->getConfigData('emails.configure.email_settings.sender_name', $channelCode)
                ?: ($this->defaultFrom['name'] ?? config('mail.from.name')),
            'from_address' => core()->getConfigData('emails.configure.email_settings.shop_email_from', $channelCode)
                ?: ($this->defaultFrom['address'] ?? config('mail.from.address')),
        ];
    }

    /**
     * Normalize encryption config.
     */
    protected function normalizeEncryption(mixed $value): ?string
    {
        if (
            $value === null
            || $value === ''
            || $value === 'none'
        ) {
            return null;
        }

        return (string) $value;
    }

    /**
     * Normalize numeric config values.
     */
    protected function normalizePort(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }
}
