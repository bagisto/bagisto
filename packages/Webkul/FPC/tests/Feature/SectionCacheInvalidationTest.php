<?php

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

it('should drop the home page in every locale, currency and channel host when a home page section changes', function () {
    // Arrange
    $this->useIsolatedPageCache();

    $otherHostScope = $this->addChannelOnHost('shop-two.test');

    $secondScope = $this->addSecondScope();

    $section = Section::factory()->make([
        'type' => SectionTypeEnum::PRODUCT_CAROUSEL->value,
        'theme_code' => 'default',
    ]);

    $home = $this->cachePage('/');

    $otherScopeHome = $this->cachePage('/', $secondScope);

    $otherHostHome = $this->cachePage('/', $otherHostScope, 'shop-two.test');

    $bystander = $this->cachePage('/summer-sale');

    // Act
    app(SectionListener::class)->afterCreate($section);

    // Assert
    $this->assertPageNotCached($home);

    $this->assertPageNotCached($otherScopeHome, 'The home page kept the old section in a second locale and currency.');

    $this->assertPageNotCached($otherHostHome, 'The home page kept the old section on a channel served on its own domain.');

    $this->assertPageCached($bystander, 'A home page section emptied pages it is not drawn on.');
});
