<?php

use Webkul\Admin\Tests\Fixtures\Sections\DealsCarousel;
use Webkul\Admin\Tests\Fixtures\Sections\LookbookSection;
use Webkul\Core\Models\Channel;
use Webkul\Theme\Enums\SectionTypeEnum;
use Webkul\Theme\Models\Section;
use Webkul\Theme\Sections\ImageCarousel;
use Webkul\Theme\Sections\SectionType;
use Webkul\Theme\SectionSchema;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

/**
 * Register a storefront theme offering the given section types, active on a channel of its own.
 */
function activeStudioTheme(?array $sections): Channel
{
    config(['themes.shop.studio' => array_merge(config('themes.shop.default'), [
        'name' => 'Studio',
        'sections' => $sections,
    ])]);

    return Channel::factory()->create(['theme' => 'studio']);
}

it('should offer the default theme every core section type', function () {
    expect(app(SectionSchema::class)->types('default')->keys()->all())
        ->toBe(SectionTypeEnum::getValues());
});

it('should offer only the section types a theme declares, in its order', function () {
    activeStudioTheme([LookbookSection::class, ImageCarousel::class, DealsCarousel::class]);

    expect(app(SectionSchema::class)->types('studio')->keys()->all())
        ->toBe(['lookbook', SectionTypeEnum::IMAGE_CAROUSEL->value, 'deals_carousel']);
});

it('should let a theme offer no section types at all', function () {
    activeStudioTheme([]);

    expect(app(SectionSchema::class)->types('studio'))->toBeEmpty();
});

it('should keep a theme own product section type out of every other theme', function () {
    $studio = activeStudioTheme([DealsCarousel::class]);

    expect(app(SectionSchema::class)->types('default')->has('deals_carousel'))->toBeFalse();

    $channel = core()->getDefaultChannel();

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.store', ['code' => $channel->theme, 'channel' => $channel->id]), [
        'name' => 'Deals On Default',
        'type' => 'deals_carousel',
    ])->assertJsonValidationErrorFor('type');

    postJson(route('admin.appearance.sections.store', ['code' => 'studio', 'channel' => $studio->id]), [
        'name' => 'Deals On Studio',
        'type' => 'deals_carousel',
    ])->assertOk()
        ->assertJsonPath('section.type', 'deals_carousel');
});

it('should refuse a core section type the theme does not offer', function () {
    $studio = activeStudioTheme([DealsCarousel::class]);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.store', ['code' => 'studio', 'channel' => $studio->id]), [
        'name' => 'Plain Carousel',
        'type' => SectionTypeEnum::PRODUCT_CAROUSEL->value,
    ])->assertJsonValidationErrorFor('type');
});

it('should extend a core section type field schema from the theme own type', function () {
    activeStudioTheme([DealsCarousel::class]);

    $filters = collect(app(SectionSchema::class)->for('deals_carousel', 'studio'))->firstWhere('key', 'filters');

    expect(collect($filters['keys'])->pluck('value'))
        ->toContain('sort', 'limit', 'on_sale');
});

it('should hand the editor the title, icon and flags of each type the theme offers', function () {
    activeStudioTheme([LookbookSection::class, DealsCarousel::class]);

    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index', ['code' => 'studio']))
        ->assertOk()
        ->assertSee('"code":"lookbook","title":"Lookbook","icon":"icon-cms","is_singleton":true', false)
        ->assertSee('"code":"deals_carousel"', false)
        ->assertSee('"icon":"icon-sales"', false)
        ->assertDontSee('"code":"'.SectionTypeEnum::STATIC_CONTENT->value.'"', false);
});

it('should resolve a core section type title and icon', function () {
    $type = app(SectionSchema::class)->type('default', SectionTypeEnum::PRODUCT_CAROUSEL->value);

    expect($type->getTitle())->toBe(trans('admin::app.appearance.sections.create.type.product-carousel'))
        ->and($type->getIcon())->toBe('icon-product')
        ->and($type->isSingleton())->toBeFalse();
});

it('should fall back to a title and icon when a section type declares neither', function () {
    $type = new LookbookSection;

    expect($type->getTitle())->toBe('Lookbook')
        ->and($type->getIcon())->toBe('icon-cms')
        ->and($type->getFields())->toBe([]);
});

it('should refuse a second section of a theme own singleton type', function () {
    $studio = activeStudioTheme([LookbookSection::class]);

    Section::factory()->create([
        'type' => 'lookbook',
        'channel_id' => $studio->id,
        'theme_code' => 'studio',
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.store', ['code' => 'studio', 'channel' => $studio->id]), [
        'name' => 'Second Lookbook',
        'type' => 'lookbook',
    ])->assertJsonValidationErrors([
        'type' => trans('admin::app.appearance.sections.create.singleton-exists', ['type' => 'Lookbook']),
    ]);
});

/**
 * A live section of a type no theme offers, as one left behind by a removed theme package.
 */
function retiredSection(): Section
{
    $channel = core()->getDefaultChannel();

    $section = Section::factory()->create([
        'type' => 'retired_type',
        'name' => 'Retired Section',
        'status' => 1,
        'channel_id' => $channel->id,
        'theme_code' => $channel->theme,
    ]);

    $section->translateOrNew(app()->getLocale())->options = ['legacy' => 'kept'];

    $section->save();

    return $section;
}

it('should hand the editor an empty schema for a stored type the theme no longer offers', function () {
    $section = retiredSection();

    $this->loginAsAdmin();

    getJson(route('admin.appearance.sections.fields', $section->id))
        ->assertOk()
        ->assertJsonPath('schema', [])
        ->assertJsonPath('options.legacy', 'kept');
});

it('should still list a stored type the theme no longer offers', function () {
    retiredSection();

    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index', ['code' => core()->getDefaultChannel()->theme]))
        ->assertOk()
        ->assertSee('"type":"retired_type"', false);
});

it('should still preview a page holding a stored type the theme no longer offers', function () {
    $section = retiredSection();

    $this->loginAsAdmin();

    get(route('shop.appearance.preview'))
        ->assertOk()
        ->assertSee('data-section-id="'.$section->id.'"', false);
});

it('should still clean static content a theme stopped offering', function () {
    $studio = activeStudioTheme([DealsCarousel::class]);

    $section = Section::factory()->create([
        'type' => SectionTypeEnum::STATIC_CONTENT->value,
        'channel_id' => $studio->id,
        'theme_code' => 'studio',
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.draft', $section->id), [
        'options' => ['html' => '<p>Safe</p><script>alert(1)</script>'],
    ])->assertOk();

    expect($section->fresh()->translate(app()->getLocale())->draft_options['html'])
        ->toContain('<p>Safe</p>')
        ->not->toContain('<script');
});

it('should implement every core section type with a class of the same code', function (SectionTypeEnum $case) {
    expect(app($case->getClassName())->getCode())->toBe($case->value);
})->with(SectionTypeEnum::cases());

it('should keep the deprecated model constants in step with the enum', function () {
    expect(Section::TYPES)->toBe(SectionTypeEnum::getValues())
        ->and(Section::FOOTER_LINKS)->toBe(SectionTypeEnum::FOOTER_LINKS->value)
        ->and(Section::STATIC_CONTENT)->toBe(SectionTypeEnum::STATIC_CONTENT->value);
});

it('should mark only the footer and the service promises as drawn by the layout', function () {
    $layout = app(SectionSchema::class)
        ->types()
        ->filter(fn (SectionType $type) => $type->rendersInLayout())
        ->keys()
        ->all();

    expect($layout)->toEqualCanonicalizing([
        SectionTypeEnum::FOOTER_LINKS->value,
        SectionTypeEnum::SERVICES_CONTENT->value,
    ]);
});

it('should resolve a stored section to the type its theme handles it with', function () {
    activeStudioTheme([DealsCarousel::class]);

    $section = Section::factory()->make([
        'type' => 'deals_carousel',
        'theme_code' => 'studio',
    ]);

    expect($section->getTypeInstance())->toBeInstanceOf(DealsCarousel::class);

    $section->theme_code = 'default';

    expect($section->getTypeInstance())->toBeNull();
});
