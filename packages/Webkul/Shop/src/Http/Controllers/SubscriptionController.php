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
        $this->user = auth()->guard('customer')->user();

        $this->customer = $customer;

        $this->subscription = $subscription;

        $this->_config = request('_config');
    }

    /**
     * Subscribes Customers and Guests to subscription mailing list and checks if already subscribed
     */
    public function subscribe()
    {
        $this->validate(request(), [
            'email' => 'email|required'
        ]);

        $email = request()->input('email');

        $unique = 0;

        if(auth()->guard('customer')->check()) {
            $unique = function() use($email) {
                $count = $this->customer->findWhere(['email' => $email]);

                if($count->count() > 0 && $count->first()->subscribed_to_news_letter == 1) {
                    return 0;
                } else {
                    return 1;
                }
            };
        } else {
            $alreadySubscribed = $this->subscription->findWhere(['email' => $email]);

            $unique = function() use($alreadySubscribed){
                if($alreadySubscribed->count() > 0 ) {
                    return 0;
                } else {
                    return 1;
                }
            };
        }

        if($unique()) {
            $token = uniqid();
            $result = false;

            if(!auth()->guard('customer')->check()) {
                $result = $this->subscription->create([
                    'email' => $email,
                    'channel_id' => core()->getCurrentChannel()->id,
                    'is_subscribed' => 1,
                    'token' => $token
                ]);
            } else {
                $user = auth()->guard('customer')->user();
                $token = auth()->guard('customer')->user()->email;
                $result = $user->update(['subscribed_to_news_letter' => 1]);
            }

            if(!$result) {
                session()->flash('error', trans('shop::app.subscription.not-subscribed'));

                return redirect()->back();
            }

            $subscriptionData['email'] = $email;
            $subscriptionData['token'] = $token;

            Mail::send(new SubscriptionEmail($subscriptionData));

            session()->flash('success', trans('shop::app.subscription.subscribed'));

            return redirect()->back();
        } else {
            session()->flash('error', trans('shop::app.subscription.already'));

            return redirect()->back();
        }

        return redirect()->back();
    }

    /**
     * To unsubscribe from a the subcription list
     *
     * @var string $token
     */
    public function unsubscribe($token) {
        if(auth()->guard('customer')->check()) {
            if(auth()->guard('customer')->user()->email == $token) {
                auth()->guard('customer')->user()->update(['subscribed_to_news_letter' => 0]);
                session('info', 'You Are Unsubscribed');
            } else {
                session()->flash('warning', 'You must be logged in with correct to unsubscribe');
            }
        } else {
            $subscriber = $this->subscription->findOneByField('token', $token);

            if($subscriber->count() > 0 && $subscriber->delete()) {
                session('info', 'You Are Unsubscribed');
            } else {
                session('info', 'You Have Already Unsubscribed');
            }

            if($this->customer->findOneByField('email', $token)) {
                session('info', 'You must Logged In To Unsubscribe');
            }
        }

        return redirect()->back();
    }
}