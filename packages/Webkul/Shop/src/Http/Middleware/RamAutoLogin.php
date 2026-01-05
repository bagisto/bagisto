<?php

namespace Webkul\Shop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\SocialLogin\Repositories\CustomerSocialAccountRepository;

/**
 * RAM Auto-Login Middleware
 *
 * Validates signed URLs from Muro Loco and auto-logs in customers.
 * Uses HMAC-SHA256 signature validation with service token.
 *
 * @see WI #191
 */
class RamAutoLogin
{
    /**
     * Create a new middleware instance.
     *
     * @return void
     */
    public function __construct(
        protected CustomerSocialAccountRepository $customerSocialAccountRepository
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $this->shouldAutoLogin($request)) {
            return $next($request);
        }

        $email = $request->query('ram_email');
        $timestamp = $request->query('ram_ts');
        $signature = $request->query('ram_sig');

        if (! $this->isValidSignature($email, $timestamp, $signature)) {
            return response('Invalid signature', 403);
        }

        if (! $this->isValidTimestamp($timestamp)) {
            return response('Signature expired', 403);
        }

        $customer = $this->findCustomerByEmail($email);

        if (! $customer) {
            return response('Customer not found', 404);
        }

        auth()->guard('customer')->login($customer, true);

        return redirect($this->getCleanUrl($request));
    }

    /**
     * Check if request should trigger auto-login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function shouldAutoLogin(Request $request): bool
    {
        return $request->has(['ram_email', 'ram_ts', 'ram_sig']);
    }

    /**
     * Validate HMAC signature
     *
     * @param  string  $email
     * @param  string  $timestamp
     * @param  string  $signature
     * @return bool
     */
    protected function isValidSignature(string $email, string $timestamp, string $signature): bool
    {
        $serviceToken = config('services.ram.service_token');

        if (! $serviceToken) {
            return false;
        }

        $expected = hash_hmac('sha256', "{$email}:{$timestamp}", $serviceToken);

        return hash_equals($expected, $signature);
    }

    /**
     * Validate timestamp (15 minutes expiration)
     *
     * @param  string  $timestamp
     * @return bool
     */
    protected function isValidTimestamp(string $timestamp): bool
    {
        $maxAge = 15 * 60; // 15 minutes

        return (time() - (int) $timestamp) <= $maxAge;
    }

    /**
     * Find customer by email using repository
     *
     * @param  string  $email
     * @return \Webkul\Customer\Contracts\Customer|null
     */
    protected function findCustomerByEmail(string $email)
    {
        $account = $this->customerSocialAccountRepository->findOneWhere([
            'provider_name' => 'ram',
        ]);

        if (! $account) {
            return null;
        }

        if ($account->customer->email !== $email) {
            return null;
        }

        return $account->customer;
    }

    /**
     * Get URL without auto-login parameters
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function getCleanUrl(Request $request): string
    {
        $query = $request->query();
        unset($query['ram_email'], $query['ram_ts'], $query['ram_sig']);

        $url = $request->url();

        if (! empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        return $url;
    }
}