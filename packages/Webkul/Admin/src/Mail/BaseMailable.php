<?php

namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BaseMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Add the sender to the message.
     *
     * @param  \Illuminate\Mail\Message  $message
     */
    protected function buildFrom($message): BaseMailable
    {
        ! empty($this->from)
            ? $message->from($this->from[0]['address'], $this->from[0]['name'])
            : $message->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name']);

        return $this;
    }
}
