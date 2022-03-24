<?php

namespace Webkul\Marketing\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var  string
     */
    public $email;

    /**
     * The campaign instance.
     *
     * @var  \Webkul\Marketing\Contracts\Campaign
     */
    public $campaign;

    /**
     * Create a new message instance.
     *
     * @param  string  $email
     * @param  \Webkul\Marketing\Contracts\Campaign  $campaign
     * @return void
     */
    public function __construct($email, $campaign)
    {
        $this->email = $email;

        $this->campaign = $campaign;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to([$this->email])
            ->subject($this->campaign->subject)
            ->html($this->campaign->email_template->content);
    }
}