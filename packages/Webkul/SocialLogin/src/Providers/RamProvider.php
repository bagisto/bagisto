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
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = [];

    /**
     * The base URL for RAM instance.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Create a new provider instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $clientId
     * @param  string  $clientSecret
     * @param  string  $redirectUrl
     * @param  string  $baseUrl
     * @param  array  $guzzle
     * @return void
     */
    public function __construct($request, $clientId, $clientSecret, $redirectUrl, $baseUrl, $guzzle = [])
    {
        parent::__construct($request, $clientId, $clientSecret, $redirectUrl, $guzzle);

        $this->baseUrl = rtrim($baseUrl, '/');
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
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return $this->baseUrl . '/index.php?link1=authorize';
    }

    /**
     * Get the access token response for the given code.
     *
     * @param  string  $code
     * @return array
     */
    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->get($this->getTokenUrl(), [
            'query' => $this->getTokenFields($code),
        ]);

        return json_decode($response->getBody(), true);
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
     *
     * @param  string  $token
     * @return array
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->baseUrl . '/index.php', [
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
