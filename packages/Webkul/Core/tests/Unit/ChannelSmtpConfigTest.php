<?php

use Webkul\ChannelSmtp\Mail\ChannelSmtpConfigApplier;
use Webkul\Core\Models\CoreConfig;

it('registers channel smtp configuration fields', function (): void {
    $field = core()->getConfigField('general.smtp.settings.host');

    expect($field)
        ->not->toBeNull()
        ->and($field['channel_based'] ?? false)->toBeTrue();
});

it('applies smtp settings from current channel configuration', function (): void {
    $mailerName = config('mail.default');

    $originalMailerConfiguration = config("mail.mailers.{$mailerName}");

    $originalFromConfiguration = config('mail.from');

    $channelCode = core()->getCurrentChannelCode();

    $smtpConfigRows = [
        'general.smtp.settings.enabled' => '1',
        'general.smtp.settings.host' => 'smtp.channel.example.com',
        'general.smtp.settings.port' => '587',
        'general.smtp.settings.encryption' => 'tls',
        'general.smtp.settings.username' => 'channel_user',
        'general.smtp.settings.password' => 'channel_password',
        'general.smtp.settings.timeout' => '45',
        'emails.configure.email_settings.sender_name' => 'Channel Sender',
        'emails.configure.email_settings.shop_email_from' => 'channel@example.com',
    ];

    foreach ($smtpConfigRows as $code => $value) {
        CoreConfig::query()->updateOrCreate(
            [
                'code' => $code,
                'channel_code' => $channelCode,
                'locale_code' => null,
            ],
            ['value' => $value]
        );
    }

    $configApplier = app(ChannelSmtpConfigApplier::class);

    expect($configApplier->apply($mailerName))->toBeTrue();

    expect(config("mail.mailers.{$mailerName}.transport"))->toBe('smtp')
        ->and(config("mail.mailers.{$mailerName}.host"))->toBe('smtp.channel.example.com')
        ->and(config("mail.mailers.{$mailerName}.port"))->toBe(587)
        ->and(config("mail.mailers.{$mailerName}.encryption"))->toBe('tls')
        ->and(config("mail.mailers.{$mailerName}.username"))->toBe('channel_user')
        ->and(config("mail.mailers.{$mailerName}.password"))->toBe('channel_password')
        ->and(config("mail.mailers.{$mailerName}.timeout"))->toBe(45)
        ->and(config('mail.from.name'))->toBe('Channel Sender')
        ->and(config('mail.from.address'))->toBe('channel@example.com');

    CoreConfig::query()->updateOrCreate(
        [
            'code' => 'general.smtp.settings.enabled',
            'channel_code' => $channelCode,
            'locale_code' => null,
        ],
        ['value' => '0']
    );

    expect($configApplier->apply($mailerName))->toBeTrue();

    expect(config("mail.mailers.{$mailerName}"))->toBe($originalMailerConfiguration)
        ->and(config('mail.from'))->toBe($originalFromConfiguration);
});
