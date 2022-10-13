<?php

namespace Webkul\SocialLogin\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Event;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Webkul\SocialLogin\Repositories\CustomerSocialAccountRepository;

class LoginController extends Controller
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;
    
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\SocialLogin\Repositories\CustomerSocialAccountRepository  $customerSocialAccountRepository
     * @return void
     */
    public function __construct(protected CustomerSocialAccountRepository $customerSocialAccountRepository)
    {
        $this->_config = request('_config');
    }

    /**
     * Redirects to the social provider
     *
     * @param  string  $provider
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            return redirect()->route('shop.customer.session.index');
        }
    }

    /**
     * Handles callback
     *
     * @param  string  $provider
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('shop.customer.session.index');
        }

        $customer = $this->customerSocialAccountRepository->findOrCreateCustomer($user, $provider);

        auth()->guard('customer')->login($customer, true);

        // Event passed to prepare cart after login
        Event::dispatch('customer.after.login', $customer->email);

        return redirect()->intended(route($this->_config['redirect']));
    }
}