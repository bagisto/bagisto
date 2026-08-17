<?php

namespace Webkul\EUWithdrawal\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Webkul\EUWithdrawal\Models\Withdrawal;
use Webkul\EUWithdrawal\Observers\WithdrawalObserver;

class EUWithdrawalServiceProvider extends ServiceProvider
{
    /**
     * Register package services into the container.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap package services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->registerRateLimiters();

        Withdrawal::observe(WithdrawalObserver::class);
    }

    /**
     * Named rate limiters consumed by Shop's storefront routes for the
     * public guest-withdrawal flow.
     *
     * Defined here because the limit policy is package-owned even though
     * the routes that use them live in Shop. Limits are deliberately strict
     * — Article 11a allows rate-limiting (defence against enumeration) but
     * forbids hard human-challenges that would obstruct the right of
     * withdrawal.
     */
    protected function registerRateLimiters(): void
    {
        RateLimiter::for('eu-withdraw-lookup', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('eu-withdraw-submit', function (Request $request) {
            return Limit::perMinute(20)->by($request->ip());
        });
    }
}
