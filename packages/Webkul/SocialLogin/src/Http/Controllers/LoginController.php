<?php

namespace Webkul\SocialLogin\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Event;
use Laravel\Socialite\Facades\Socialite;
use Webkul\SocialLogin\Repositories\CustomerSocialAccountRepository;

class LoginController extends Controller
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CustomerSocialAccountRepository $customerSocialAccountRepository) {}

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
            \Log::error('Social login redirect error: ' . $e->getMessage(), [
                'provider' => $provider,
                'exception' => $e
            ]);

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
            \Log::error('Social login callback error: ' . $e->getMessage(), [
                'provider' => $provider,
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('error', 'Error during social login: ' . $e->getMessage());
            return redirect()->route('shop.customer.session.index');
        }

        $customer = $this->customerSocialAccountRepository->findOrCreateCustomer($user, $provider);

        auth()->guard('customer')->login($customer, true);

        Event::dispatch('customer.after.login', $customer);

        // Check for auto-provision redirect from RamAutoLogin middleware #191
        if ($redirectUrl = session()->pull('ram_auto_provision_redirect')) {
            return redirect($redirectUrl);
        }

        return redirect()->intended(route('shop.customers.account.profile.index'));
    }
}
