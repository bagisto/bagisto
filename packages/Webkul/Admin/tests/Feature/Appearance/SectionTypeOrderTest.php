<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Exceptions;
use Webkul\Admin\Tests\Fixtures\Sections\HeroSection;
use Webkul\Admin\Tests\Fixtures\Sections\NarrowFooterLinks;
use Webkul\Admin\Tests\Fixtures\Sections\PremiumProductCarousel;
use Webkul\Admin\Tests\Fixtures\Sections\ProductCarouselOverride;
use Webkul\Core\Models\Channel;
use Webkul\Theme\Enums\SectionTypeEnum;
use Webkul\Theme\Exceptions\InvalidSectionType;
use Webkul\Theme\Models\Section;
use Webkul\Theme\Sections\ImageCarousel;
use Webkul\Theme\SectionSchema;

use function Pest\Laravel\get;

/**
 * Register a storefront theme declaring the given section types, active on a channel of its own.
 */
function themeDeclaringSections(mixed $sections): Channel
{
    config(['themes.shop.ordered' => array_merge(config('themes.shop.default'), [
        'name' => 'Ordered',
        'customize' => ['sections' => $sections],
    ])]);

    return Channel::factory()->create(['theme' => 'ordered']);
}

/**
 * Codes of the section types a theme offers, in the order the editor lists them.
 */
function offeredCodes(string $themeCode = 'ordered'): array
{
    return app(SectionSchema::class)->types($themeCode)->keys()->all();
}

it('should declare the default theme section types explicitly, in enum order', function () {
    expect(config('themes.shop.default.customize.sections'))->toBe(SectionTypeEnum::cases())
        ->and(offeredCodes('default'))->toBe(SectionTypeEnum::getValues());
});

it('should keep the enum order for a theme that declares no sections', function () {
    config(['themes.shop.ordered' => array_merge(Arr::except(config('themes.shop.default'), 'customize'), ['name' => 'Ordered'])]);

    expect(config('themes.shop.ordered'))->not->toHaveKey('customize')
        ->and(offeredCodes())->toBe(SectionTypeEnum::getValues());
});

it('should offer a theme core types in exactly the order it lists their codes', function () {
    themeDeclaringSections([
        SectionTypeEnum::PRODUCT_CAROUSEL->value,
        SectionTypeEnum::IMAGE_CAROUSEL->value,
        SectionTypeEnum::CATEGORY_CAROUSEL->value,
    ]);

    expect(offeredCodes())->toBe([
        SectionTypeEnum::PRODUCT_CAROUSEL->value,
        SectionTypeEnum::IMAGE_CAROUSEL->value,
        SectionTypeEnum::CATEGORY_CAROUSEL->value,
    ]);
});

it('should order a theme own section types alongside core ones, however each is declared', function () {
    themeDeclaringSections([
        HeroSection::class,
        SectionTypeEnum::STATIC_CONTENT,
        SectionTypeEnum::PRODUCT_CAROUSEL->value,
        PremiumProductCarousel::class,
        ImageCarousel::class,
    ]);

    expect(offeredCodes())->toBe([
        'hero',
        SectionTypeEnum::STATIC_CONTENT->value,
        SectionTypeEnum::PRODUCT_CAROUSEL->value,
        'premium_product_carousel',
        SectionTypeEnum::IMAGE_CAROUSEL->value,
    ]);
});

it('should follow the explicitly ordered types with the remaining core types in enum order', function () {
    themeDeclaringSections([
        HeroSection::class,
        SectionTypeEnum::SERVICES_CONTENT->value,
        ...SectionTypeEnum::getValues(),
    ]);

    expect(offeredCodes())->toBe([
        'hero',
        SectionTypeEnum::SERVICES_CONTENT->value,
        SectionTypeEnum::IMAGE_CAROUSEL->value,
        SectionTypeEnum::PRODUCT_CAROUSEL->value,
        SectionTypeEnum::CATEGORY_CAROUSEL->value,
        SectionTypeEnum::FOOTER_LINKS->value,
        SectionTypeEnum::STATIC_CONTENT->value,
    ]);
});

it('should lead with a theme own type and fill in the rest from the spread enum cases', function () {
    themeDeclaringSections([
        HeroSection::class,
        SectionTypeEnum::STATIC_CONTENT,
        ...SectionTypeEnum::cases(),
    ]);

    expect(offeredCodes())->toBe([
        'hero',
        SectionTypeEnum::STATIC_CONTENT->value,
        SectionTypeEnum::IMAGE_CAROUSEL->value,
        SectionTypeEnum::PRODUCT_CAROUSEL->value,
        SectionTypeEnum::CATEGORY_CAROUSEL->value,
        SectionTypeEnum::FOOTER_LINKS->value,
        SectionTypeEnum::SERVICES_CONTENT->value,
    ]);
});

it('should let a theme replace a core type ahead of the spread enum cases', function () {
    themeDeclaringSections([
        NarrowFooterLinks::class,
        ...SectionTypeEnum::cases(),
    ]);

    expect(app(SectionSchema::class)->type('ordered', SectionTypeEnum::FOOTER_LINKS->value))
        ->toBeInstanceOf(NarrowFooterLinks::class)
        ->and(offeredCodes())->toHaveCount(count(SectionTypeEnum::cases()));
});

it('should keep a theme override of a core type where the theme first declares it', function () {
    themeDeclaringSections([
        ProductCarouselOverride::class,
        ...SectionTypeEnum::getClassNames(),
    ]);

    $types = app(SectionSchema::class)->types('ordered');

    expect($types->keys()->first())->toBe(SectionTypeEnum::PRODUCT_CAROUSEL->value)
        ->and($types->get(SectionTypeEnum::PRODUCT_CAROUSEL->value))->toBeInstanceOf(ProductCarouselOverride::class)
        ->and($types)->toHaveCount(count(SectionTypeEnum::cases()));
});

it('should list a type declared more than once a single time, where it first appears', function () {
    themeDeclaringSections([
        SectionTypeEnum::IMAGE_CAROUSEL->value,
        ImageCarousel::class,
        SectionTypeEnum::PRODUCT_CAROUSEL,
        SectionTypeEnum::IMAGE_CAROUSEL,
        SectionTypeEnum::PRODUCT_CAROUSEL->value,
    ]);

    expect(offeredCodes())->toBe([
        SectionTypeEnum::IMAGE_CAROUSEL->value,
        SectionTypeEnum::PRODUCT_CAROUSEL->value,
    ]);

    $this->loginAsAdmin();

    $html = get(route('admin.appearance.sections.index', ['code' => 'ordered']))
        ->assertOk()
        ->getContent();

    expect(substr_count($html, '"code":"'.SectionTypeEnum::IMAGE_CAROUSEL->value.'"'))->toBe(1);
});

it('should skip and report a declared entry that is not a section type, keeping the rest', function () {
    Exceptions::fake();

    themeDeclaringSections([
        'hero_without_a_class',
        stdClass::class,
        'Webkul\\Missing\\Sections\\Banner',
        42,
        ['nested'],
        SectionTypeEnum::IMAGE_CAROUSEL->value,
        HeroSection::class,
    ]);

    expect(offeredCodes())->toBe([SectionTypeEnum::IMAGE_CAROUSEL->value, 'hero']);

    Exceptions::assertReported(fn (InvalidSectionType $exception) => str_contains($exception->getMessage(), 'hero_without_a_class'));

    Exceptions::assertReported(fn (InvalidSectionType $exception) => str_contains($exception->getMessage(), '[int]'));

    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index', ['code' => 'ordered']))->assertOk();
});

it('should hand the editor the section types already in the theme order', function () {
    themeDeclaringSections([
        HeroSection::class,
        SectionTypeEnum::FOOTER_LINKS->value,
        SectionTypeEnum::PRODUCT_CAROUSEL->value,
        SectionTypeEnum::IMAGE_CAROUSEL->value,
    ]);

    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index', ['code' => 'ordered']))
        ->assertOk()
        ->assertSeeInOrder([
            '"code":"hero"',
            '"code":"'.SectionTypeEnum::FOOTER_LINKS->value.'"',
            '"code":"'.SectionTypeEnum::PRODUCT_CAROUSEL->value.'"',
            '"code":"'.SectionTypeEnum::IMAGE_CAROUSEL->value.'"',
        ], false);
});

it('should leave the order of the sections already placed on the page alone', function () {
    $channel = themeDeclaringSections([
        SectionTypeEnum::STATIC_CONTENT->value,
        SectionTypeEnum::PRODUCT_CAROUSEL->value,
    ]);

    foreach (['First Placed Carousel' => SectionTypeEnum::PRODUCT_CAROUSEL, 'Second Placed Content' => SectionTypeEnum::STATIC_CONTENT] as $name => $type) {
        Section::factory()->create([
            'name' => $name,
            'type' => $type->value,
            'sort_order' => $type === SectionTypeEnum::PRODUCT_CAROUSEL ? 1 : 2,
            'channel_id' => $channel->id,
            'theme_code' => 'ordered',
        ]);
    }

    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index', ['code' => 'ordered']))
        ->assertOk()
        ->assertSeeInOrder(['First Placed Carousel', 'Second Placed Content']);

    expect(Section::query()->where('theme_code', 'ordered')->orderBy('sort_order')->pluck('name')->all())
        ->toBe(['First Placed Carousel', 'Second Placed Content']);
});

it('should still resolve a stored section of a core type the theme no longer lists', function () {
    themeDeclaringSections([SectionTypeEnum::IMAGE_CAROUSEL->value]);

    $section = Section::factory()->make([
        'type' => SectionTypeEnum::STATIC_CONTENT->value,
        'theme_code' => 'ordered',
    ]);

    expect(offeredCodes())->not->toContain(SectionTypeEnum::STATIC_CONTENT->value)
        ->and($section->getTypeInstance()?->getCode())->toBe(SectionTypeEnum::STATIC_CONTENT->value);
});
