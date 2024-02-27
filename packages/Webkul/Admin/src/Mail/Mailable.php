<?php

namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable as BaseMailable;
use Illuminate\Queue\SerializesModels;

class Mailable extends BaseMailable
{
    use Queueable, SerializesModels;

    /**
     * Add the sender to the message.
     *
     * @param  \Illuminate\Mail\Message  $message
     */
    protected function buildFrom($message): Mailable
    {
        ! empty($this->from)
            ? $message->from($this->from[0]['address'], $this->from[0]['name'])
            : $message->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name']);

        return $this;
    }
}
