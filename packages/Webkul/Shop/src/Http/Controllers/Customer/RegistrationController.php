<?php

namespace Webkul\Shop\Http\Controllers\Customer;

use Cookie;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Webkul\Core\Repositories\SubscribersListRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Shop\Http\Requests\Customer\RegistrationRequest;
use Webkul\Shop\Mail\Customer\EmailVerificationNotification;
use Webkul\Shop\Mail\Customer\RegistrationNotification;

class RegistrationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected CustomerGroupRepository $customerGroupRepository,
        protected SubscribersListRepository $subscriptionRepository
    ) {}

    /**
     * Opens up the user's sign up form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('shop::customers.sign-up');
    }

    /**
     * Method to store user's sign up form data to DB.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RegistrationRequest $registrationRequest)
    {
        $customerGroup = core()->getConfigData('customer.settings.create_new_account_options.default_group');

        $subscription = $this->subscriptionRepository->findOneWhere(['email' => request()->input('email')]);

        $data = array_merge($registrationRequest->only([
            'first_name',
            'last_name',
            'email',
            'password_confirmation',
            'is_subscribed',
        ]), [
            'password'                  => bcrypt(request()->input('password')),
            'api_token'                 => Str::random(80),
            'is_verified'               => ! core()->getConfigData('customer.settings.email.verification'),
            'customer_group_id'         => $this->customerGroupRepository->findOneWhere(['code' => $customerGroup])->id,
            'channel_id'                => core()->getCurrentChannel()->id,
            'token'                     => md5(uniqid(rand(), true)),
            'subscribed_to_news_letter' => (bool) (request()->input('is_subscribed') ?? $subscription?->is_subscribed),
        ]);

        Event::dispatch('customer.registration.before');

        $customer = $this->customerRepository->create($data);

        if ($subscription) {
            $this->subscriptionRepository->update([
                'customer_id' => $customer->id,
            ], $subscription->id);
        } 
        
        if (
            ! empty($data['is_subscribed'])
            && ! $subscription
        ) {
            Event::dispatch('customer.subscription.before');

            $subscription = $this->subscriptionRepository->create([
                'email'         => $data['email'],
                'customer_id'   => $customer->id,
                'channel_id'    => core()->getCurrentChannel()->id,
                'is_subscribed' => 1,
                'token'         => uniqid(),
            ]);

            Event::dispatch('customer.subscription.after', $subscription);
        }

        Event::dispatch('customer.create.after', $customer);

        Event::dispatch('customer.registration.after', $customer);

        if (core()->getConfigData('emails.general.notifications.emails.general.notifications.verification')) {
            session()->flash('success', trans('shop::app.customers.signup-form.success-verify'));
        } else {
            session()->flash('success', trans('shop::app.customers.signup-form.success'));
        }

        return redirect()->route('shop.customer.session.index');
    }

    /**
     * Method to verify account.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function verifyAccount($token)
    {
        $customer = $this->customerRepository->findOneByField('token', $token);

        if ($customer) {
            $this->customerRepository->update([
                'is_verified' => 1,
                'token'       => null,
            ], $customer->id);

            if ((bool) core()->getConfigData('emails.general.notifications.emails.general.notifications.registration')) {
                Mail::queue(new RegistrationNotification($customer));
            }

            $this->customerRepository->syncNewRegisteredCustomerInformation($customer);

            session()->flash('success', trans('shop::app.customers.signup-form.verified'));
        } else {
            session()->flash('warning', trans('shop::app.customers.signup-form.verify-failed'));
        }

        return redirect()->route('shop.customer.session.index');
    }

    /**
     * Resend verification email.
     *
     * @param  string  $email
     * @return \Illuminate\Http\Response
     */
    public function resendVerificationEmail($email)
    {
        $verificationData = [
            'email' => $email,
            'token' => md5(uniqid(rand(), true)),
        ];

        $customer = $this->customerRepository->findOneByField('email', $email);

        $this->customerRepository->update(['token' => $verificationData['token']], $customer->id);

        try {
            Mail::queue(new EmailVerificationNotification($verificationData));

            if (Cookie::has('enable-resend')) {
                \Cookie::queue(\Cookie::forget('enable-resend'));
            }

            if (Cookie::has('email-for-resend')) {
                \Cookie::queue(\Cookie::forget('email-for-resend'));
            }
        } catch (\Exception $e) {
            report($e);

            session()->flash('error', trans('shop::app.customers.signup-form.verification-not-sent'));

            return redirect()->back();
        }

        session()->flash('success', trans('shop::app.customers.signup-form.verification-sent'));

        return redirect()->back();
    }
}