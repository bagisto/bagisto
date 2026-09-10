<?php

use Webkul\Admin\Tests\Fixtures\Sections\NarrowFooterLinks;
use Webkul\Core\Models\Channel;
use Webkul\Theme\Enums\SectionTypeEnum;
use Webkul\Theme\Models\Section;
use Webkul\Theme\Sections\FooterLinks;
use Webkul\Theme\SectionSchema;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

/**
 * The channel's only footer, holding the given stored options.
 */
function footerWith(array $options, ?Channel $channel = null): Section
{
    $channel ??= core()->getDefaultChannel();

    Section::query()
        ->where('type', SectionTypeEnum::FOOTER_LINKS->value)
        ->where('channel_id', $channel->id)
        ->get()
        ->each
        ->delete();

    $section = Section::factory()->create([
        'type' => SectionTypeEnum::FOOTER_LINKS->value,
        'status' => 1,
        'channel_id' => $channel->id,
        'theme_code' => $channel->theme,
    ]);

    $section->translateOrNew(app()->getLocale())->options = $options;

    $section->save();

    return $section->refresh();
}

/**
 * A column of footer links, one per title.
 */
function footerColumn(string ...$titles): array
{
    return array_map(fn ($title) => ['title' => $title, 'url' => '/'.strtolower($title)], $titles);
}

it('should let the operator add columns rather than fixing how many there are', function () {
    $schema = app(SectionSchema::class)->for(SectionTypeEnum::FOOTER_LINKS->value);

    expect($schema)->toHaveCount(1)
        ->and($schema[0]['key'])->toBe('columns')
        ->and($schema[0]['type'])->toBe(SectionSchema::REPEATER)
        ->and($schema[0])->not->toHaveKey('max')
        ->and(collect($schema[0]['fields'])->pluck('key')->all())->toBe(['links']);
});

it('should hand the editor a saved two column footer as a list of columns', function () {
    $section = footerWith([
        'column_1' => footerColumn('About', 'Careers'),
        'column_2' => footerColumn('Help'),
    ]);

    $this->loginAsAdmin();

    getJson(route('admin.appearance.sections.fields', $section->id))
        ->assertOk()
        ->assertJsonCount(2, 'options.columns')
        ->assertJsonPath('options.columns.0.links.1.title', 'Careers')
        ->assertJsonPath('options.columns.1.links.0.title', 'Help');
});

it('should read stored columns in their numbered order, past nine', function () {
    $options = [];

    foreach ([10, 2, 1] as $number) {
        $options['column_'.$number] = footerColumn('Column '.$number);
    }

    $columns = app(FooterLinks::class)->prepareForEditor($options)['columns'];

    expect(collect($columns)->pluck('links.0.title')->all())->toBe(['Column 1', 'Column 2', 'Column 10']);
});

it('should store edited columns as the numbered keys the storefront reads', function () {
    $section = footerWith([]);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.draft', $section->id), [
        'options' => [
            'columns' => [
                ['links' => footerColumn('One')],
                ['links' => footerColumn('Two')],
                ['links' => footerColumn('Three')],
                ['links' => footerColumn('Four')],
            ],
        ],
    ])->assertOk();

    $draft = $section->fresh()->translate(app()->getLocale())->draft_options;

    expect(array_keys($draft))->toBe(['column_1', 'column_2', 'column_3', 'column_4'])
        ->and($draft['column_4'][0]['title'])->toBe('Four');
});

it('should render every configured footer column on the storefront', function () {
    footerWith([
        'column_1' => footerColumn('First Column Link'),
        'column_2' => footerColumn('Second Column Link'),
        'column_3' => footerColumn('Third Column Link'),
        'column_4' => footerColumn('Fourth Column Link'),
    ]);

    get(route('shop.home.index'))
        ->assertOk()
        ->assertSeeInOrder([
            'First Column Link',
            'Second Column Link',
            'Third Column Link',
            'Fourth Column Link',
        ]);
});

it('should publish an edited column count as the columns the storefront renders', function () {
    $section = footerWith([
        'column_1' => footerColumn('Old Link'),
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.draft', $section->id), [
        'options' => [
            'columns' => [
                ['links' => footerColumn('Kept Link')],
                ['links' => footerColumn('Added Link')],
                ['links' => footerColumn('Third Added Link')],
            ],
        ],
    ])->assertOk();

    postJson(route('admin.appearance.sections.publish', $section->theme_code))->assertOk();

    $published = $section->fresh()->translate(app()->getLocale())->options;

    expect(array_keys($published))->toBe(['column_1', 'column_2', 'column_3'])
        ->and($published['column_3'][0]['title'])->toBe('Third Added Link');
});

it('should hold a theme footer to the number of columns it lays out', function () {
    config(['themes.shop.narrow' => array_merge(config('themes.shop.default'), [
        'sections' => [NarrowFooterLinks::class],
    ])]);

    $channel = Channel::factory()->create(['theme' => 'narrow']);

    $section = footerWith([], $channel);

    expect(app(SectionSchema::class)->for(SectionTypeEnum::FOOTER_LINKS->value, 'narrow')[0]['max'])->toBe(3);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.draft', $section->id), [
        'options' => [
            'columns' => array_map(fn ($number) => ['links' => footerColumn('Link '.$number)], range(1, 5)),
        ],
    ])->assertOk();

    expect(array_keys($section->fresh()->translate(app()->getLocale())->draft_options))
        ->toBe(['column_1', 'column_2', 'column_3']);
});

it('should keep a footer posted in the stored shape as it was sent', function () {
    $section = footerWith([]);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.draft', $section->id), [
        'options' => [
            'column_1' => footerColumn('Legacy Link'),
        ],
    ])->assertOk();

    expect($section->fresh()->translate(app()->getLocale())->draft_options)
        ->toEqual(['column_1' => footerColumn('Legacy Link')]);
});

it('should hand the editor an empty footer as no columns yet', function () {
    $section = footerWith([]);

    $this->loginAsAdmin();

    getJson(route('admin.appearance.sections.fields', $section->id))
        ->assertOk()
        ->assertJsonPath('options.columns', []);
});
