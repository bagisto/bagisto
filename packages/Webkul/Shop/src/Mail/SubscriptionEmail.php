<?php

namespace Webkul\Shop\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
/**
 * Subscriber Mail class
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 *
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SubscriptionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscriptionData;

    public function __construct($subscriptionData) {
        $this->subscriptionData = $subscriptionData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->subscriptionData['email'])
                ->from(env('SHOP_MAIL_FROM'))
                ->subject(trans('shop::app.mail.customer.subscription.subject'))
                ->view('shop::emails.customer.subscription-email')->with('data', ['content' => 'You Are Subscribed', 'token' => $this->subscriptionData['token']]);
    }
}