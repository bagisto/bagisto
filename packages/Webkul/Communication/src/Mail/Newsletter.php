<?php

namespace Webkul\Communication\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Webkul\Core\Contracts\SubscribersList;
use Webkul\Communication\Contracts\NewsletterQueue;

class Newsletter extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Newsletter.
     *
     * @var Webkul\Communication\Contracts\NewsletterQueue
     */
    protected $newsletter;

    /**
     * Subscriber email.
     *
     * @var Webkul\Core\Contracts\SubscribersList
     */
    protected $subscriber;

    /**
     * Constructor.
     *
     * @param  Webkul\Communication\Contracts\NewsletterQueue
     * @param  Webkul\Core\Contracts\SubscribersList
     * @return void
     */
    public function __construct(
        NewsletterQueue $newsletter,
        SubscribersList $subscriber
    )
    {
        $this->newsletter = $newsletter;
        $this->subscriber = $subscriber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->newsletter->sender_email, $this->newsletter->sender_name)
                    ->to($this->subscriber->email)
                    ->subject($this->newsletter->subject)
                    ->html($this->newsletter->content);
    }
}