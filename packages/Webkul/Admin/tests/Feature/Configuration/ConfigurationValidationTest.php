<?php

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

/**
 * The payload saving the header offer's redirection title, over its length limit, with any extra request fields.
 */
function overlongHeaderOfferPayload(array $extra = []): array
{
    return [
        'locale' => core()->getDefaultLocaleCodeFromDefaultChannel(),
        'channel' => core()->getDefaultChannel()->code,
        'general' => [
            'content' => [
                'header_offer' => [
                    'redirection_title' => str_repeat('a', 30),
                ],
            ],
        ],
        ...$extra,
    ];
}

// ============================================================================
// Validation
// ============================================================================

it('should validate a submitted configuration field against its own rules when the request describes none', function () {
    $this->loginAsAdmin();

    postJson(route('admin.configuration.store', ['general', 'content']), overlongHeaderOfferPayload())
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('general.content.header_offer.redirection_title');

    $this->assertDatabaseMissing('core_config', [
        'code' => 'general.content.header_offer.redirection_title',
        'value' => str_repeat('a', 30),
    ]);
});

it('should ignore the rules a request describes for a configuration field', function () {
    $this->loginAsAdmin();

    postJson(route('admin.configuration.store', ['general', 'content']), overlongHeaderOfferPayload([
        'keys' => [
            json_encode([
                'key' => 'general.content.header_offer',
                'fields' => [['name' => 'redirection_title', 'validation' => 'nullable']],
            ]),
        ],
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('general.content.header_offer.redirection_title');

    $this->assertDatabaseMissing('core_config', [
        'code' => 'general.content.header_offer.redirection_title',
        'value' => str_repeat('a', 30),
    ]);
});

// ============================================================================
// Storefront
// ============================================================================

it('should strip script from the footer copyright content on the storefront', function () {
    $this->setConfig(
        'general.content.footer.copyright_content',
        '<a href="/page/about-us">About Us</a><script>alert(document.domain)</script>',
    );

    get(route('shop.home.index'))
        ->assertOk()
        ->assertSee('<a href="/page/about-us">About Us</a>', false)
        ->assertDontSee('alert(document.domain)', false);
});
