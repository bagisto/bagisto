<?php

namespace Webkul\Communication\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Webkul\Communication\Contracts\NewsletterTemplate;

class Newsletter extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Newsletter template.
     *
     * @var Webkul\Communication\Contracts\NewsletterTemplate
     */
    protected $newsletterTemplate;

    /**
     * Constructor.
     *
     * @param  Webkul\Communication\Contracts\NewsletterTemplate $newsletterTemplate
     * @return void
     */
    public function __construct(
        NewsletterTemplate $newsletterTemplate
    )
    {
        $this->newsletterTemplate = $newsletterTemplate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
                    ->to(core()->getAdminEmailDetails()['email'])
                    ->subject(trans('shop::app.mail.order.subject'))
                    ->view('shop::emails.sales.new-admin-order');
    }
}