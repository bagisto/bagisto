<?php

use Illuminate\Support\Facades\Cache;
use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\Core\Models\CoreConfig;

use function Pest\Laravel\get;

/**
 * Set the storefront's copyright content, past the repository's cache.
 */
function setCopyrightContent(string $content): void
{
    CoreConfig::updateOrCreate(
        [
            'code' => 'general.content.footer.copyright_content',
            'channel_code' => core()->getRequestedChannelCode(),
            'locale_code' => core()->getRequestedLocaleCode(),
        ],
        ['value' => $content],
    );

    Cache::flush();
}

/**
 * The storefront pages that carry a copyright line, with the default each falls back to.
 */
dataset('pages carrying a copyright', [
    'home' => [fn () => route('shop.home.index'), 'shop::app.components.layouts.footer.footer-text'],
    'sign in' => [fn () => route('shop.customer.session.index'), 'shop::app.customers.login-form.footer'],
    'sign up' => [fn () => route('shop.customers.register.index'), 'shop::app.customers.signup-form.footer'],
    'forgot password' => [fn () => route('shop.customers.forgot_password.create'), 'shop::app.customers.forgot-password.footer'],
    'reset password' => [fn () => route('shop.customers.reset_password.create', 'a-token'), 'shop::app.customers.reset-password.footer'],
]);

beforeEach(function () {
    config(['responsecache.enabled' => false]);

    ResponseCache::clear();
});

it('should carry the configured copyright', function (Closure $url, string $fallbackKey) {
    setCopyrightContent('Copyright QA Ltd. All rights reserved.');

    get($url())
        ->assertOk()
        ->assertSee('Copyright QA Ltd. All rights reserved.', false)
        ->assertDontSee(trans($fallbackKey, ['current_year' => date('Y')]), false);
})->with('pages carrying a copyright');

it('should fall back to its own copyright when none is configured', function (Closure $url, string $fallbackKey) {
    setCopyrightContent('');

    get($url())
        ->assertOk()
        ->assertSee(trans($fallbackKey, ['current_year' => date('Y')]), false);
})->with('pages carrying a copyright');
