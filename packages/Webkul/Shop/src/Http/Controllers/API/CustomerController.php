<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Event;
use Webkul\Shop\Http\Requests\Customer\LoginRequest;

class CustomerController extends APIController
{
    use ThrottlesLogins;

    /**
     * The failed login attempts an email address and caller may make before waiting.
     */
    protected $maxAttempts = 6;

    /**
     * How many minutes those failed attempts are remembered for.
     */
    protected $decayMinutes = 1;

    /**
     * The request field holding the identifier login attempts are counted against.
     */
    public function username(): string
    {
        return 'email';
    }

    /**
     * Login Customer
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = array_merge($request->only(['email', 'password']), [
            'channel_id' => core()->getCurrentChannel()->id,
        ]);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if (! auth()->guard('customer')->attempt($credentials)) {
            $this->incrementLoginAttempts($request);

            return response()->json([
                'message' => trans('shop::app.customers.login-form.invalid-credentials'),
            ], Response::HTTP_FORBIDDEN);
        }

        $this->clearLoginAttempts($request);

        if (! auth()->guard('customer')->user()->status) {
            auth()->guard('customer')->logout();

            return response()->json([
                'message' => trans('shop::app.customers.login-form.not-activated'),
            ], Response::HTTP_FORBIDDEN);
        }

        if (! auth()->guard('customer')->user()->is_verified) {
            Cookie::queue(Cookie::make('enable-resend', 'true', 1));

            Cookie::queue(Cookie::make('email-for-resend', $request->input('email'), 1));

            auth()->guard('customer')->logout();

            return response()->json([
                'message' => trans('shop::app.customers.login-form.verify-first'),
            ], Response::HTTP_FORBIDDEN);
        }

        /**
         * Event passed to prepare cart after login.
         */
        Event::dispatch('customer.after.login', auth()->guard()->user());

        return response()->json([]);
    }
}
