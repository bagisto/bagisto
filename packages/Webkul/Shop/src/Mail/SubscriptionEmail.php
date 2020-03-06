<?php

namespace Webkul\Shop\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriptionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscriptionData;

    /**
     * Create a mailable instance
     * 
     * @param  array  $subscriptionData
     */
    public function __construct($subscriptionData)
    {
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
                    ->subject(trans('shop::app.mail.customer.subscription.subject'))
                    ->view('shop::emails.customer.subscription-email')
                    ->with('data', [
                            'content' => 'You Are Subscribed',
                            'token'   => $this->subscriptionData['token'],
                        ]
                    );

    }
}