<?php

use Webkul\Theme\Enums\SectionTypeEnum;
use Webkul\Theme\Models\Section;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

/**
 * Get the default channel's section of a type, with the given published options.
 */
function sectionOfTypeWithOptions(string $type, array $options): Section
{
    $channel = core()->getDefaultChannel();

    $section = Section::where([
        'channel_id' => $channel->id,
        'theme_code' => $channel->theme ?: 'default',
        'type' => $type,
    ])->first() ?? Section::factory()->create([
        'channel_id' => $channel->id,
        'theme_code' => $channel->theme ?: 'default',
        'type' => $type,
        'status' => 1,
    ]);

    $section->status = 1;

    $section->translateOrNew(app()->getLocale())->options = $options;

    $section->save();

    return $section->refresh();
}

it('should clear a footer link that would run script when a draft is saved', function () {
    $section = sectionOfTypeWithOptions(SectionTypeEnum::FOOTER_LINKS->value, []);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.draft', $section->id), [
        'options' => [
            'columns' => [
                [
                    'links' => [
                        ['title' => 'Script', 'url' => 'javascript:alert(document.domain)'],
                        ['title' => 'Obfuscated', 'url' => " JaVa\tScRiPt:alert(1)"],
                        ['title' => 'Data', 'url' => 'data:text/html,<script>alert(1)</script>'],
                        ['title' => 'About', 'url' => 'https://example.com/about'],
                        ['title' => 'Contact', 'url' => '/page/contact-us'],
                        ['title' => 'Mail', 'url' => 'mailto:support@example.com'],
                    ],
                ],
            ],
        ],
    ])->assertOk();

    expect(collect($section->refresh()->translate(app()->getLocale())->draft_options['column_1'])->pluck('url')->all())
        ->toBe(['', '', '', 'https://example.com/about', '/page/contact-us', 'mailto:support@example.com']);
});

it('should clear a slide link that would run script when a draft is saved', function () {
    $section = sectionOfTypeWithOptions(SectionTypeEnum::IMAGE_CAROUSEL->value, ['images' => []]);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.draft', $section->id), [
        'options' => [
            'images' => [
                ['image' => 'storage/theme/1/slide.webp', 'title' => 'Script', 'link' => 'javascript:alert(1)'],
                ['image' => 'storage/theme/1/slide.webp', 'title' => 'Sale', 'link' => 'https://example.com/sale'],
            ],
        ],
    ])->assertOk();

    expect(collect($section->refresh()->translate(app()->getLocale())->draft_options['images'])->pluck('link')->all())
        ->toBe(['', 'https://example.com/sale']);
});

it('should never render a stored footer link that would run script on the storefront', function () {
    sectionOfTypeWithOptions(SectionTypeEnum::FOOTER_LINKS->value, [
        'column_1' => [
            ['title' => 'Stored Before The Fix', 'url' => 'javascript:alert(document.domain)'],
        ],
    ]);

    get(route('shop.home.index'))
        ->assertOk()
        ->assertSeeText('Stored Before The Fix')
        ->assertDontSee('javascript:alert', false);
});
