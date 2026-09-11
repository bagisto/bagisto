<?php

namespace Webkul\Shop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'customer')
    {
        if (! auth()->guard($guard)->check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => '',
                ], 401);
            }

            return redirect()->route('shop.customer.session.index');
        } else {
            $customer = auth()->guard($guard)->user();

            if (! $customer->status) {
                auth()->guard($guard)->logout();

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => trans('shop::app.customers.login-form.not-activated'),
                    ], 401);
                }

                session()->flash('warning', trans('shop::app.customers.login-form.not-activated'));

                return redirect()->route('shop.customer.session.index');
            }

            $currentChannel = core()->getCurrentChannel();

            if (
                $customer->channel_id
                && $customer->channel_id != $currentChannel?->id
            ) {
                if (
                    $customer->channel?->hostname
                    && $customer->channel->hostname != $currentChannel?->hostname
                ) {
                    return redirect(rtrim($customer->channel->hostname, '/').$request->getRequestUri());
                }

                auth()->guard($guard)->logout();

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => '',
                    ], 401);
                }

                return redirect()->route('shop.customer.session.index');
            }
        }

        return $next($request);
    }
}
