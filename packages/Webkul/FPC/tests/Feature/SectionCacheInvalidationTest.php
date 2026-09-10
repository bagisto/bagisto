<?php

use Spatie\ResponseCache\CacheItemSelector\CacheItemSelector;
use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\FPC\Listeners\Section as SectionListener;
use Webkul\FPC\Tests\Fixtures\Sections\PromoBarSection;
use Webkul\Theme\Enums\SectionTypeEnum;
use Webkul\Theme\Models\Section;

it('should clear every page when a section the layout draws changes', function (string $type, ?array $sections) {
    // Arrange
    config(['themes.shop.default.customize.sections' => $sections]);

    $section = Section::factory()->make(['type' => $type, 'theme_code' => 'default']);

    ResponseCache::shouldReceive('clear')->once();

    ResponseCache::shouldReceive('selectCachedItems')->never();

    // Act & Assert
    app(SectionListener::class)->afterUpdate($section);
})->with([
    'footer links' => [SectionTypeEnum::FOOTER_LINKS->value, null],
    'service promises' => [SectionTypeEnum::SERVICES_CONTENT->value, null],
    'a theme own layout type' => ['promo_bar', [PromoBarSection::class]],
]);

it('should clear only the home page when a home page section changes', function () {
    // Arrange
    $section = Section::factory()->make([
        'type' => SectionTypeEnum::PRODUCT_CAROUSEL->value,
        'theme_code' => 'default',
    ]);

    $selection = Mockery::mock(CacheItemSelector::class);

    $selection->shouldReceive('forUrls')->once()->with(config('app.url').'/')->andReturnSelf();

    $selection->shouldReceive('forget')->once();

    ResponseCache::shouldReceive('clear')->never();

    ResponseCache::shouldReceive('selectCachedItems')->once()->andReturn($selection);

    // Act & Assert
    app(SectionListener::class)->afterCreate($section);
});
