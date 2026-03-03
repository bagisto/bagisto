<?php

namespace Webkul\Core\Mail\Transport;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

class DynamicSmtpTransport extends AbstractTransport
{
    /**
     * Send the given message.
     */
    protected function doSend(SentMessage $message): void
    {
        $transport = $this->buildTransport();

        $transport->send($message->getOriginalMessage(), $message->getEnvelope());
    }

    /**
     * Build the SMTP transport from Bagisto core config,
     * falling back to .env / config/mail.php if not set.
     */
    protected function buildTransport(): EsmtpTransport
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
     * Get the string representation of the transport.
     */
    public function __toString(): string
    {
        return 'bagisto-dynamic-smtp';
    }
}
