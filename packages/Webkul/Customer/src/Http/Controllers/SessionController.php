<?php

namespace Webkul\Customer\Http\Controllers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Event;
use Webkul\Customer\Http\Requests\CustomerLoginRequest;

class SessionController extends Controller
{
    /**
     * Display the resource.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show()
    {
        return auth()->guard('customer')->check()
            ? redirect()->route('shop.home.index')
            : view('shop::customers.sign-in');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Webkul\Customer\Http\Requests\CustomerLoginRequest $customerLoginRequest
     * @return \Illuminate\Http\Response
     */
    public function create(CustomerLoginRequest $customerLoginRequest)
    {
        if (! auth()->guard('customer')->attempt($customerLoginRequest->only(['email', 'password']))) {
            session()->flash('error', trans('shop::app.customer.login-form.invalid-creds'));

            return redirect()->back();
        }

        if (! auth()->guard('customer')->user()->status) {
            auth()->guard('customer')->logout();

            session()->flash('warning', trans('shop::app.customer.login-form.not-activated'));

            return redirect()->back();
        }

        if (! auth()->guard('customer')->user()->is_verified) {
            session()->flash('info', trans('shop::app.customer.login-form.verify-first'));

            Cookie::queue(Cookie::make('enable-resend', 'true', 1));

            Cookie::queue(Cookie::make('email-for-resend', $customerLoginRequest->get('email'), 1));

            auth()->guard('customer')->logout();

            return redirect()->back();
        }

        /**
         * Event passed to prepare cart after login.
         */
        Event::dispatch('customer.after.login', $customerLoginRequest->get('email'));

        return redirect()->route('shop.home.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        auth()->guard('customer')->logout();

        Event::dispatch('customer.after.logout', $id);

        return redirect()->route('shop.home.index');
    }
}
