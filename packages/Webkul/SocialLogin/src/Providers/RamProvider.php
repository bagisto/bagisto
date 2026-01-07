<?php

namespace Webkul\SocialLogin\Providers;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

/**
 * RAM OAuth 2.0 Provider
 *
 * Implements OAuth 2.0 authentication against RedActivaMéxico social network.
 *
 * @see WI #191
 */
class RamProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * Indicates if the session state should be utilized.
     *
     * @var bool
     */
    protected $stateless = true;

    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = [];

    /**
     * The base URL for RAM instance (browser-facing, for authorization redirects).
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * The internal URL for RAM instance (Docker container-to-container, for API calls).
     *
     * @var string
     */
    protected $internalUrl;

    /**
     * Create a new provider instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $clientId
     * @param  string  $clientSecret
     * @param  string  $redirectUrl
     * @param  string  $baseUrl
     * @param  string|null  $internalUrl
     * @param  array  $guzzle
     * @return void
     */
    public function __construct($request, $clientId, $clientSecret, $redirectUrl, $baseUrl, $internalUrl = null, $guzzle = [])
    {
        parent::__construct($request, $clientId, $clientSecret, $redirectUrl, $guzzle);

        $this->baseUrl = rtrim($baseUrl, '/');
        $this->internalUrl = $internalUrl ? rtrim($internalUrl, '/') : $this->baseUrl;
    }

    /**
     * Get the authentication URL for the provider.
     *
     * @param  string  $state
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->baseUrl . '/index.php', $state);
    }

    /**
     * Build the authentication URL with proper parameters.
     *
     * @param  string  $url
     * @param  string  $state
     * @return string
     */
    protected function buildAuthUrlFromBase($url, $state)
    {
        return $url . '?' . http_build_query([
            'link1' => 'oauth',
            'app_id' => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
            'state' => $state,
        ], '', '&', $this->encodingType);
    }

    /**
     * Get the token URL for the provider.
     * Uses internalUrl for Docker container-to-container communication.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return $this->internalUrl . '/index.php?link1=authorize';
    }

    /**
     * Get the access token response for the given code.
     *
     * @param  string  $code
     * @return array
     */
    public function getAccessTokenResponse($code)
    {
        $tokenUrl = $this->getTokenUrl();
        $tokenFields = $this->getTokenFields($code);

        \Log::info('RAM OAuth: Exchanging code for token', [
            'url' => $tokenUrl,
            'params' => $tokenFields,
        ]);

        $response = $this->getHttpClient()->get($tokenUrl, [
            'query' => $tokenFields,
            'allow_redirects' => false, // Don't follow redirects - API should return JSON
            'http_errors' => false, // Don't throw on 4xx/5xx
        ]);

        $statusCode = $response->getStatusCode();
        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        \Log::info('RAM OAuth: Token response', [
            'status' => $statusCode,
            'body' => $body,
            'data' => $data,
        ]);

        // Handle redirects (302) - means authentication failed
        if ($statusCode >= 300 && $statusCode < 400) {
            $location = $response->getHeader('Location')[0] ?? 'unknown';
            throw new \Exception("RAM OAuth failed - redirected to: {$location}. The authorization code may have expired or been used already.");
        }

        // Check for WoWonder error response
        if (isset($data['status']) && $data['status'] != 200) {
            $errorMsg = $data['errors']['message'] ?? 'Unknown error';
            $errorCode = $data['errors']['error_code'] ?? 'N/A';
            throw new \Exception("RAM OAuth error [{$errorCode}]: {$errorMsg}");
        }

        // WoWonder returns: {"status": 200, "access_token": "..."}
        // Laravel Socialite expects: {"access_token": "...", "token_type": "bearer"}
        // Transform response to match Socialite expectations
        if (isset($data['access_token'])) {
            return [
                'access_token' => $data['access_token'],
                'token_type' => 'bearer',
                'expires_in' => 3600, // WoWonder tokens expire in 1 hour
            ];
        }

        // If response doesn't match expected format, throw exception
        throw new \Exception('Invalid token response from RAM: ' . json_encode($data));
    }

    /**
     * Get the POST fields for the token request.
     *
     * @param  string  $code
     * @return array
     */
    protected function getTokenFields($code)
    {
        return [
            'app_id' => $this->clientId,
            'app_secret' => $this->clientSecret,
            'code' => $code,
        ];
    }

    /**
     * Get the raw user for the given access token.
     * Uses internalUrl for Docker container-to-container communication.
     *
     * @param  string  $token
     * @return array
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->internalUrl . '/index.php', [
            'query' => [
                'link1' => 'apps_api',
                'access_token' => $token,
                'type' => 'get_user_data',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param  array  $user
     * @return \Laravel\Socialite\Two\User
     */
    protected function mapUserToObject(array $user)
    {
        $userData = $user['user_data'] ?? [];

        return (new User)->setRaw($user)->map([
            'id'       => $userData['user_id'] ?? null,
            'nickname' => $userData['username'] ?? null,
            'name'     => ($userData['first_name'] ?? '') . ' ' . ($userData['last_name'] ?? ''),
            'email'    => $userData['email'] ?? null,
            'avatar'   => $userData['avatar'] ?? null,
        ]);
    }
}
