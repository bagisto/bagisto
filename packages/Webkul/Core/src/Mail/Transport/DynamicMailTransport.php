<?php

namespace Webkul\Core\Mail\Transport;

use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoApiTransport;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Transport\TransportInterface;

class DynamicMailTransport extends AbstractTransport
{
    /**
     * Send over an SMTP server of the admin's choosing.
     */
    public const DRIVER_SMTP = 'smtp';

    /**
     * Send over Brevo's HTTP API.
     */
    public const DRIVER_BREVO_API = 'brevo_api';

    /**
     * Send the given message.
     */
    protected function doSend(SentMessage $message): void
    {
        $transport = $this->buildTransport();

        $transport->send($message->getOriginalMessage(), $message->getEnvelope());
    }

    /**
     * Build the transport the admin has chosen in Admin → Configuration → Emails.
     */
    protected function buildTransport(): TransportInterface
    {
        return match (core()->getConfigData('emails.configure.smtp.driver')) {
            self::DRIVER_BREVO_API => $this->buildBrevoTransport(),
            default => $this->buildSmtpTransport(),
        };
    }

    /**
     * Build the SMTP transport from Bagisto core config,
     * falling back to .env / config/mail.php if not set.
     */
    protected function buildSmtpTransport(): EsmtpTransport
    {
        $host = core()->getConfigData('emails.configure.smtp.host') ?? config('mail.mailers.smtp.host');
        $port = core()->getConfigData('emails.configure.smtp.port') ?? config('mail.mailers.smtp.port');
        $encryption = core()->getConfigData('emails.configure.smtp.encryption') ?? config('mail.mailers.smtp.encryption');
        $username = core()->getConfigData('emails.configure.smtp.username') ?? config('mail.mailers.smtp.username');
        $password = core()->getConfigData('emails.configure.smtp.password') ?? config('mail.mailers.smtp.password');

        if (! $host) {
            throw new \RuntimeException(
                'Mail SMTP host is not configured. Please set it in Admin → Configuration → Emails → SMTP.'
            );
        }

        $transport = new EsmtpTransport(
            host: $host,
            port: (int) $port,
            tls: strtolower((string) $encryption) === 'ssl',
        );

        $transport->setUsername((string) $username);

        $transport->setPassword((string) $password);

        return $transport;
    }

    /**
     * Build the Brevo API transport from Bagisto core config.
     */
    protected function buildBrevoTransport(): BrevoApiTransport
    {
        $key = core()->getConfigData('emails.configure.smtp.brevo_api_key');

        if (! $key) {
            throw new \RuntimeException(
                'Brevo API key is not configured. Please set it in Admin → Configuration → Emails → SMTP.'
            );
        }

        return new BrevoApiTransport((string) $key);
    }

    /**
     * Get the string representation of the transport.
     */
    public function __toString(): string
    {
        return 'bagisto-dynamic-smtp';
    }
}
