<?php

namespace Webkul\ChannelSmtp\Mail;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Mail\MailManager;

class ChannelAwareMailManager extends MailManager
{
    /**
     * Create a new mail manager instance.
     */
    public function __construct(
        Application $app,
        protected ChannelSmtpConfigApplier $channelSmtpConfigApplier
    ) {
        parent::__construct($app);
    }

    /**
     * Get a mailer instance by name.
     */
    public function mailer($name = null): \Illuminate\Contracts\Mail\Mailer
    {
        $name = $name ?: $this->getDefaultDriver();

        if ($this->channelSmtpConfigApplier->apply($name)) {
            $this->purge($name);
        }

        return parent::mailer($name);
    }
}
