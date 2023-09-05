<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Core\Repositories\SubscribersListRepository;

class SubscriptionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected SubscribersListRepository $subscriptionRepository) {
    }

    /**
     * Subscribes email to the email subscription list
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'email' => 'email|required',
        ]);

        $email = request()->input('email');

        $subscription = $this->subscriptionRepository->findOneByField('email', $email);

        if ($subscription) {
            session()->flash('error', trans('shop::app.subscription.already'));

            return redirect()->back();
        }

        Event::dispatch('customer.subscription.before');

        $subscription = $this->subscriptionRepository->create([
            'email'         => $email,
            'channel_id'    => core()->getCurrentChannel()->id,
            'is_subscribed' => 1,
            'token'         => uniqid(),
        ]);

        Event::dispatch('customer.subscription.after', $subscription);

        session()->flash('success', trans('shop::app.subscription.subscribe-success'));

        return redirect()->back();
    }

    /**
     * To unsubscribe from a the subscription list
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function destroy($token)
    {
        $this->subscriptionRepository->deleteWhere(['token' => $token]);

        session()->flash('success', trans('shop::app.subscription.unsubscribe-success'));

        return redirect()->route('shop.home.index');
    }
}
