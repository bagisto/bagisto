<?php

use Webkul\Core\Models\CoreConfig;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

it('should validate a submitted configuration field against its own rules when the request describes none', function () {
    $this->loginAsAdmin();

    postJson(route('admin.configuration.store', ['general', 'content']), [
        'locale' => core()->getDefaultLocaleCodeFromDefaultChannel(),
        'channel' => core()->getDefaultChannel()->code,
        'general' => [
            'content' => [
                'header_offer' => [
                    'redirection_title' => str_repeat('a', 30),
                ],
            ],
        ],
    ])
        ->assertJsonValidationErrorFor('general.content.header_offer.redirection_title');
});

it('should ignore the rules a request describes for a configuration field', function () {
    $this->loginAsAdmin();

    postJson(route('admin.configuration.store', ['general', 'content']), [
        'locale' => core()->getDefaultLocaleCodeFromDefaultChannel(),
        'channel' => core()->getDefaultChannel()->code,
        'keys' => [
            json_encode([
                'key' => 'general.content.header_offer',
                'fields' => [['name' => 'redirection_title', 'validation' => 'nullable']],
            ]),
        ],
        'general' => [
            'content' => [
                'header_offer' => [
                    'redirection_title' => str_repeat('a', 30),
                ],
            ],
        ],
    ])
        ->assertJsonValidationErrorFor('general.content.header_offer.redirection_title');
});

it('should strip script from the footer copyright content on the storefront', function () {
    CoreConfig::create([
        'code' => 'general.content.footer.copyright_content',
        'value' => '<a href="/page/about-us">About Us</a><script>alert(document.domain)</script>',
        'locale_code' => app()->getLocale(),
    ]);

    get(route('shop.home.index'))
        ->assertOk()
        ->assertSee('<a href="/page/about-us">About Us</a>', false)
        ->assertDontSee('alert(document.domain)', false);
});
