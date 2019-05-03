<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Shop\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Webkul\Shop\Mail\SubscriptionEmail;
use Webkul\Customer\Repositories\CustomerRepository as Customer;
use Webkul\Core\Repositories\SubscribersListRepository as Subscription;

/**
 * Subscription controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SubscriptionController extends Controller
{
    /**
     * User object
     *
     * @var array
     */
    protected $user;

    /**
     * Customer Repository object
     *
     * @var array
     */
    protected $customer;

    /**
     * Subscription List Repository object
     *
     * @var array
     */
    protected $subscription;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, Subscription $subscription)
    {
        $this->subscription = $subscription;

        $this->_config = request('_config');
    }

    /**
     * Subscribes email to the email subscription list
     */
    public function subscribe()
    {
        $this->validate(request(), [
            'subscriber_email' => 'email|required'
        ]);

        $email = request()->input('subscriber_email');

        $unique = 0;

        $alreadySubscribed = $this->subscription->findWhere(['email' => $email]);

        $unique = function () use ($alreadySubscribed) {
            if ($alreadySubscribed->count() > 0) {
                return 0;
            } else {
                return 1;
            }
        };

        if ($unique()) {
            $token = uniqid();

            $subscriptionData['email'] = $email;
            $subscriptionData['token'] = $token;

            $mailSent = true;

            try {
                Mail::queue(new SubscriptionEmail($subscriptionData));

                session()->flash('success', trans('shop::app.subscription.subscribed'));
            } catch (\Exception $e) {
                session()->flash('error', trans('shop::app.subscription.not-subscribed'));

                $mailSent = false;
            }

            $result = false;

            if ($mailSent) {
                $result = $this->subscription->create([
                    'email' => $email,
                    'channel_id' => core()->getCurrentChannel()->id,
                    'is_subscribed' => 1,
                    'token' => $token
                ]);

                if (!$result) {
                    session()->flash('error', trans('shop::app.subscription.not-subscribed'));

                    return redirect()->back();
                }
            }
        } else {
            session()->flash('error', trans('shop::app.subscription.already'));
        }

        return redirect()->back();
    }

    /**
     * To unsubscribe from a the subcription list
     *
     * @var string $token
     */
    public function unsubscribe($token)
    {
        $subscriber = $this->subscription->findOneByField('token', $token);

        if (isset($subscriber))
            if ($subscriber->count() > 0 && $subscriber->is_subscribed == 1 && $subscriber->update(['is_subscribed' => 0])) {
                session()->flash('info', trans('shop::app.subscription.unsubscribed'));
            } else {
                session()->flash('info', trans('shop::app.subscription.already-unsub'));
            }

        return redirect()->route('shop.home.index');
    }
}
