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
        // Note: Do NOT include query params here - Guzzle replaces existing params
        // Instead, include link1 in getTokenFields()
        return $this->internalUrl . '/index.php';
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

        $response = $this->getHttpClient()->get($tokenUrl, [
            'query' => $tokenFields,
            'allow_redirects' => false,
            'http_errors' => false,
        ]);

        $statusCode = $response->getStatusCode();
        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        // Handle redirects (302) - authentication failed or endpoint misconfigured
        if ($statusCode >= 300 && $statusCode < 400) {
            $location = $response->getHeader('Location')[0] ?? 'unknown';
            throw new \Exception("RAM OAuth failed - redirected to: {$location}");
        }

        // Handle WoWonder error response
        if (isset($data['status']) && $data['status'] != 200) {
            $errorMsg = $data['errors']['message'] ?? 'Unknown error';
            $errorCode = $data['errors']['error_code'] ?? 'N/A';
            throw new \Exception("RAM OAuth error [{$errorCode}]: {$errorMsg}");
        }

        // Transform WoWonder response to Socialite format
        if (isset($data['access_token'])) {
            return [
                'access_token' => $data['access_token'],
                'token_type' => 'bearer',
                'expires_in' => 3600,
            ];
        }

        throw new \Exception('Invalid token response from RAM');
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
            'link1' => 'authorize',  // WoWonder routing parameter
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
                'link1' => 'app_api',  // Note: singular, not apps_api
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
