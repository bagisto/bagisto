<?php

namespace Webkul\SocialLogin;

use Laravel\Socialite\One\TwitterProvider;
use Laravel\Socialite\SocialiteManager as BaseSocialiteManager;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Two\GithubProvider;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\LinkedInOpenIdProvider;
use League\OAuth1\Client\Server\Twitter as TwitterServer;
use Webkul\SocialLogin\Providers\RamProvider;

class SocialiteManager extends BaseSocialiteManager
{
    /**
     * Create an instance of the specified driver.
     *
     * @return \Laravel\Socialite\Two\AbstractProvider
     */
    protected function createGithubDriver()
    {
        $clientId = core()->getConfigData('customer.settings.social_login.github_client_id');
        $clientSecret = core()->getConfigData('customer.settings.social_login.github_client_secret');
        $callbackUrl = core()->getConfigData('customer.settings.social_login.github_callback_url');

        if ($clientId || $clientSecret || $callbackUrl) {
            $config = [
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'redirect'      => $callbackUrl,
            ];
        } else {
            $config = $this->config->get('services.github');
        }

        return $this->buildProvider(
            GithubProvider::class, $config
        );
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Laravel\Socialite\Two\AbstractProvider
     */
    protected function createFacebookDriver()
    {
        $clientId = core()->getConfigData('customer.settings.social_login.facebook_client_id');
        $clientSecret = core()->getConfigData('customer.settings.social_login.facebook_client_secret');
        $callbackUrl = core()->getConfigData('customer.settings.social_login.facebook_callback_url');

        if ($clientId || $clientSecret || $callbackUrl) {
            $config = [
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'redirect'      => $callbackUrl,
            ];
        } else {
            $config = $this->config->get('services.facebook');
        }

        return $this->buildProvider(
            FacebookProvider::class, $config
        );
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Laravel\Socialite\Two\AbstractProvider
     */
    protected function createGoogleDriver()
    {
        $clientId = core()->getConfigData('customer.settings.social_login.google_client_id');
        $clientSecret = core()->getConfigData('customer.settings.social_login.google_client_secret');
        $callbackUrl = core()->getConfigData('customer.settings.social_login.google_callback_url');

        if ($clientId || $clientSecret || $callbackUrl) {
            $config = [
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'redirect'      => $callbackUrl,
            ];
        } else {
            $config = $this->config->get('services.google');
        }

        return $this->buildProvider(
            GoogleProvider::class, $config
        );
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Laravel\Socialite\Two\AbstractProvider
     */
    protected function createLinkedinOpenidDriver()
    {
        $clientId = core()->getConfigData('customer.settings.social_login.linkedin_client_id');
        $clientSecret = core()->getConfigData('customer.settings.social_login.linkedin_client_secret');
        $callbackUrl = core()->getConfigData('customer.settings.social_login.linkedin_callback_url');

        if ($clientId || $clientSecret || $callbackUrl) {
            $config = [
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'redirect'      => $callbackUrl,
            ];
        } else {
            $config = $this->config->get('services.linkedin-openid');
        }

        return $this->buildProvider(
            LinkedInOpenIdProvider::class, $config
        );
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Laravel\Socialite\One\AbstractProvider|\Laravel\Socialite\Two\AbstractProvider
     */
    protected function createTwitterDriver()
    {
        $clientId = core()->getConfigData('customer.settings.social_login.twitter_client_id');
        $clientSecret = core()->getConfigData('customer.settings.social_login.twitter_client_secret');
        $callbackUrl = core()->getConfigData('customer.settings.social_login.twitter_callback_url');

        if ($clientId || $clientSecret || $callbackUrl) {
            $config = [
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'redirect'      => $callbackUrl,
            ];
        } else {
            $config = $this->config->get('services.twitter');
        }

        if (($config['oauth'] ?? null) === 2) {
            return $this->createTwitterOAuth2Driver();
        }

        return new TwitterProvider(
            $this->container->make('request'), new TwitterServer($this->formatConfig($config))
        );
    }

    /**
     * Create an instance of the RAM driver.
     *
     * @return \Laravel\Socialite\Two\AbstractProvider
     */
    protected function createRamDriver()
    {
        $clientId = core()->getConfigData('customer.settings.social_login.ram_client_id');
        $clientSecret = core()->getConfigData('customer.settings.social_login.ram_client_secret');
        $callbackUrl = core()->getConfigData('customer.settings.social_login.ram_callback_url');
        $baseUrl = core()->getConfigData('customer.settings.social_login.ram_base_url');
        $internalUrl = core()->getConfigData('customer.settings.social_login.ram_internal_url');

        if ($clientId || $clientSecret || $callbackUrl || $baseUrl) {
            $config = [
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'redirect'      => $callbackUrl,
                'base_url'      => $baseUrl,
                'internal_url'  => $internalUrl,
            ];
        } else {
            $config = $this->config->get('services.ram');
        }

        return new RamProvider(
            $this->container->make('request'),
            $config['client_id'],
            $config['client_secret'],
            $config['redirect'],
            $config['base_url'],
            $config['internal_url'] ?? null,
            $config['guzzle'] ?? []
        );
    }
}
