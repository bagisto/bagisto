<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Webkul\Shop\Mail\SubscriptionEmail;
use Webkul\Core\Repositories\SubscribersListRepository;

/**
 * Subscription controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SubscriptionController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * SubscribersListRepository
     *
     * @var Object
     */
    protected $subscriptionRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\SubscribersListRepository $subscriptionRepository
     * @return void
     */
    public function __construct(SubscribersListRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;

        $this->_config = request('_config');
    }

    /**
     * Subscribes email to the email subscription list
     *
     * @return Redirect
     */
    public function subscribe()
    {
        $this->validate(request(), [
            'subscriber_email' => 'email|required'
        ]);

        $email = request()->input('subscriber_email');

        $unique = 0;

        $alreadySubscribed = $this->subscriptionRepository->findWhere(['email' => $email]);

        $unique = function () use ($alreadySubscribed) {
            return $alreadySubscribed->count() > 0 ? 0 : 1;
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
                report($e);
                session()->flash('error', trans('shop::app.subscription.not-subscribed'));

                $mailSent = false;
            }

            $result = false;

            if ($mailSent) {
                $result = $this->subscriptionRepository->create([
                    'email' => $email,
                    'channel_id' => core()->getCurrentChannel()->id,
                    'is_subscribed' => 1,
                    'token' => $token
                ]);

                if (! $result) {
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
        $subscriber = $this->subscriptionRepository->findOneByField('token', $token);

        if (isset($subscriber)) {
            if ($subscriber->count() > 0 && $subscriber->is_subscribed == 1 && $subscriber->update(['is_subscribed' => 0])) {
                session()->flash('info', trans('shop::app.subscription.unsubscribed'));
            } else {
                session()->flash('info', trans('shop::app.subscription.already-unsub'));
            }
        }

        return redirect()->route('shop.home.index');
    }
}
