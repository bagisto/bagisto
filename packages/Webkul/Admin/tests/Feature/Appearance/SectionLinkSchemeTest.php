<?php

use Webkul\Theme\Enums\SectionTypeEnum;
use Webkul\Theme\Models\Section;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

/**
 * The published section of a type on the default channel, created when the channel has none, holding the given options.
 */
function sectionOfTypeWithOptions(string $type, array $options): Section
{
    $channel = core()->getDefaultChannel();

    $attributes = [
        'channel_id' => $channel->id,
        'theme_code' => $channel->theme ?: 'default',
        'type' => $type,
    ];

    $section = Section::query()->where($attributes)->first()
        ?? Section::factory()->create([...$attributes, 'status' => true]);

    $section->status = true;

    $section->translateOrNew(app()->getLocale())->options = $options;

    $section->save();

    return $section->refresh();
}

/**
 * The draft options of a section in the current locale.
 */
function draftOptionsOf(Section $section): array
{
    return $section->refresh()->translate(app()->getLocale())->draft_options;
}

// ============================================================================
// Drafts
// ============================================================================

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

    expect(collect(draftOptionsOf($section)['column_1'])->pluck('url')->all())
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

    expect(collect(draftOptionsOf($section)['images'])->pluck('link')->all())
        ->toBe(['', 'https://example.com/sale']);
});

// ============================================================================
// Storefront
// ============================================================================

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
