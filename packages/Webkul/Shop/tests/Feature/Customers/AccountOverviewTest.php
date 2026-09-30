<?php

use Webkul\Core\Models\Locale;

use function Pest\Laravel\get;

/**
 * The text inside the page's title tag.
 */
function pageTitle(string $html): string
{
    preg_match('/<title>(.*?)<\/title>/s', $html, $matches);

    return trim($matches[1] ?? '');
}

/**
 * Offer a locale on the storefront, whatever the installation seeded.
 */
function offerStorefrontLocale(string $code): void
{
    $locale = Locale::firstOrCreate(
        ['code' => $code],
        [
            'name' => $code,
            'direction' => 'ltr',
        ],
    );

    $channel = core()->getCurrentChannel();

    $channel->locales()->syncWithoutDetaching([$locale->id]);

    $channel->unsetRelation('locales');
}

beforeEach(function () {
    $this->customer = $this->loginAsCustomer();
});

// ============================================================================
// Page Identity
// ============================================================================

it('should title the account overview as the account, not the orders page', function () {
    $response = get(route('shop.customers.account.index'))->assertOk();

    expect(pageTitle($response->getContent()))
        ->toBe(trans('shop::app.layouts.my-account'))
        ->not->toBe(trans('shop::app.customers.account.orders.title'));
});

// ============================================================================
// Account Menu
// ============================================================================

it('should greet the customer in the storefront locale', function () {
    offerStorefrontLocale('de');

    get(route('shop.customers.account.index', ['locale' => 'de']))
        ->assertOk()
        ->assertSee(trans('shop::app.components.layouts.account.greeting', [
            'name' => $this->customer->first_name,
        ], 'de'), false)
        ->assertDontSee('Hello! '.$this->customer->first_name, false);
});

it('should describe the profile image in the storefront locale', function () {
    offerStorefrontLocale('de');

    get(route('shop.customers.account.index', ['locale' => 'de']))
        ->assertOk()
        ->assertSee(trans('shop::app.components.layouts.account.profile-image', [], 'de'), false)
        ->assertDontSee('alt="Profile Image"', false);
});
