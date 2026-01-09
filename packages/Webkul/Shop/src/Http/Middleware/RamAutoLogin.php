<?php

namespace Webkul\Shop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\SocialLogin\Repositories\CustomerSocialAccountRepository;

/**
 * RAM Auto-Login Middleware
 *
 * Validates signed URLs from RAM and auto-logs in customers.
 * Uses HMAC-SHA256 signature validation with shared service token.
 * Looks up customers by provider_id (RAM user_id) in customer_social_accounts.
 * If customer doesn't exist, redirects to OAuth flow for auto-provisioning.
 *
 * @see WI #191
 */
class RamAutoLogin
{
    public function __construct(
        protected CustomerSocialAccountRepository $socialAccountRepository
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $this->shouldAutoLogin($request)) {
            return $next($request);
        }

        $userId = $request->query('ram_user_id');
        $timestamp = $request->query('ram_ts');
        $signature = $request->query('ram_sig');

        // Invalid or expired signature - redirect without auto-login params
        if (! $this->isValidSignature($userId, $timestamp, $signature)) {
            return redirect($this->getCleanUrl($request));
        }

        if (! $this->isValidTimestamp($timestamp)) {
            return redirect($this->getCleanUrl($request));
        }

        // Look up customer by provider_id in customer_social_accounts
        $socialAccount = $this->socialAccountRepository->findOneWhere([
            'provider_name' => 'ram',
            'provider_id'   => $userId,
        ]);

        // Customer not found - redirect to OAuth for auto-provisioning #191
        if (! $socialAccount || ! $socialAccount->customer) {
            session(['ram_auto_provision_redirect' => $this->getCleanUrl($request)]);
            return redirect()->route('customer.social-login.index', 'ram');
        }

        auth()->guard('customer')->login($socialAccount->customer, true);

        return redirect($this->getCleanUrl($request));
    }

    /**
     * Check if request should trigger auto-login
     */
    protected function shouldAutoLogin(Request $request): bool
    {
        if (! $request->has(['ram_user_id', 'ram_ts', 'ram_sig'])) {
            return false;
        }

        // Skip if already logged in via same RAM user
        if (auth()->guard('customer')->check()) {
            $customerId = auth()->guard('customer')->user()->id;
            $socialAccount = $this->socialAccountRepository->findOneWhere([
                'customer_id'   => $customerId,
                'provider_name' => 'ram',
            ]);

            if ($socialAccount && $socialAccount->provider_id === $request->query('ram_user_id')) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate HMAC signature
     */
    protected function isValidSignature(string $userId, string $timestamp, string $signature): bool
    {
        $serviceToken = config('services.ram.service_token');

        if (! $serviceToken) {
            return false;
        }

        $expected = hash_hmac('sha256', "{$userId}:{$timestamp}", $serviceToken);

        return hash_equals($expected, $signature);
    }

    /**
     * Validate timestamp (15 minutes expiration)
     */
    protected function isValidTimestamp(string $timestamp): bool
    {
        $maxAge = 15 * 60;

        return (time() - (int) $timestamp) <= $maxAge;
    }

    /**
     * Get URL without auto-login parameters
     */
    protected function getCleanUrl(Request $request): string
    {
        $query = $request->query();
        unset($query['ram_user_id'], $query['ram_ts'], $query['ram_sig']);

        $url = $request->url();

        if (! empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        return $url;
    }
}
