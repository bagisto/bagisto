<?php

use Webkul\Core\Models\Channel;
use Webkul\ImageCache\Templates\Large;
use Webkul\Product\Models\ProductImage;
use Webkul\Shop\Http\Middleware\PreviewImageTheme;
use Webkul\Theme\Enums\SectionTypeEnum;
use Webkul\Theme\Models\Section;
use Webkul\Theme\ThemeCatalog;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

/**
 * Register a second storefront theme, built from the default one, that no channel runs yet.
 */
function installGalleryTheme(string $code = 'gallery'): string
{
    config(['themes.shop.'.$code => array_merge(config('themes.shop.default'), ['name' => 'Gallery'])]);

    return $code;
}

/**
 * A live section of a theme on a channel, carrying the given options.
 */
function sectionOfTheme(string $themeCode, int $channelId, string $type, array $options): Section
{
    $section = Section::factory()->create([
        'type' => $type,
        'status' => 1,
        'channel_id' => $channelId,
        'theme_code' => $themeCode,
    ]);

    $section->translateOrNew(app()->getLocale())->options = $options;

    $section->save();

    return $section;
}

/**
 * A storefront product with one image record, on a theme whose product images carry a card template of its own.
 */
function previewCatalogProduct(string $themeCode): array
{
    config(['themes.shop.'.$themeCode.'.customize.image_cache' => [
        'templates' => ['card' => Large::class],
        'product_images' => ['card'],
    ]]);

    $product = test()->createSimpleProduct();

    $path = 'products/'.$product->id.'/front.webp';

    ProductImage::create([
        'product_id' => $product->id,
        'type' => 'images',
        'path' => $path,
        'position' => 1,
    ]);

    return [$product, $path];
}

/**
 * Sign an admin in on the admin guard alone, as the storefront requests of the appearance preview see one, leaving
 * the storefront's default guard on customers.
 */
function adminSignedInBesideTheStorefront(?array $permissions = null): void
{
    if (is_null($permissions)) {
        test()->loginAsAdmin();
    } else {
        test()->loginAsAdminWithPermissions($permissions);
    }

    auth()->shouldUse('customer');
}

// ============================================================================
// Inactive Themes
// ============================================================================

it('should send an installed theme no channel runs back to the gallery', function () {
    $code = installGalleryTheme();

    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index', ['code' => $code]))
        ->assertRedirect(route('admin.appearance.themes.index'))
        ->assertSessionHas('warning', trans('admin::app.appearance.sections.index.inactive-theme'));
});

it('should refuse every theme wide write for a theme no channel runs', function (string $route) {
    $code = installGalleryTheme();

    $this->loginAsAdmin();

    postJson(route($route, ['code' => $code]), [
        'name' => 'Anything',
        'type' => SectionTypeEnum::STATIC_CONTENT->value,
    ])
        ->assertForbidden()
        ->assertJsonPath('message', trans('admin::app.appearance.sections.index.inactive-theme'));

    expect(Section::query()->where('theme_code', $code)->count())->toBe(0);
})->with([
    'create' => ['admin.appearance.sections.store'],
    'publish' => ['admin.appearance.sections.publish'],
    'discard' => ['admin.appearance.sections.discard'],
]);

it('should refuse to act on a section whose theme its channel no longer runs', function (string $action) {
    $code = installGalleryTheme();

    $channel = core()->getDefaultChannel();

    $section = sectionOfTheme($code, $channel->id, SectionTypeEnum::STATIC_CONTENT->value, ['html' => '<p>Stranded</p>']);

    $this->loginAsAdmin();

    $response = match ($action) {
        'fields' => getJson(route('admin.appearance.sections.fields', $section->id)),
        'draft' => postJson(route('admin.appearance.sections.draft', $section->id), ['options' => ['html' => 'x']]),
        'status' => postJson(route('admin.appearance.sections.status', $section->id), ['status' => false]),
        'duplicate' => postJson(route('admin.appearance.sections.duplicate', $section->id)),
        'reorder' => postJson(route('admin.appearance.sections.reorder'), ['sections' => [$section->id]]),
        'delete' => deleteJson(route('admin.appearance.sections.delete', $section->id)),
        'update' => postJson(route('admin.appearance.sections.update', $section->id), [
            'name' => 'Moved',
            'type' => SectionTypeEnum::STATIC_CONTENT->value,
            'sort_order' => 1,
            'channel_id' => $channel->id,
            'theme_code' => $code,
        ]),
    };

    $response->assertForbidden();

    expect($section->fresh())->not->toBeNull()
        ->and($section->fresh()->name)->not->toBe('Moved')
        ->and($section->fresh()->translate(app()->getLocale())->draft_options)->toBeNull();
})->with(['fields', 'draft', 'status', 'duplicate', 'reorder', 'delete', 'update']);

// ============================================================================
// Active Channels
// ============================================================================

it('should open the editor on a channel that runs the theme rather than the default one', function () {
    $code = installGalleryTheme();

    $channel = Channel::factory()->create(['theme' => $code]);

    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index', ['code' => $code]))
        ->assertOk()
        ->assertSee(route('admin.appearance.sections.store', ['code' => $code, 'channel' => $channel->code]), false);
});

it('should refuse to create on a channel that runs a different theme', function () {
    $code = installGalleryTheme();

    Channel::factory()->create(['theme' => $code]);

    $default = core()->getDefaultChannel();

    $this->loginAsAdmin();

    postJson(route('admin.appearance.sections.store', ['code' => $code, 'channel' => $default->code]), [
        'name' => 'Wrong Channel',
        'type' => SectionTypeEnum::STATIC_CONTENT->value,
    ])->assertForbidden();
});

it('should report a theme active only on the channels that run it', function () {
    $code = installGalleryTheme();

    $idle = installGalleryTheme('atelier');

    $channel = Channel::factory()->create(['theme' => $code]);

    $catalog = app(ThemeCatalog::class);

    expect($catalog->isInstalled($idle))->toBeTrue()
        ->and($catalog->isActive($idle))->toBeFalse()
        ->and($catalog->isInstalled('not-a-theme'))->toBeFalse()
        ->and($catalog->isActive('not-a-theme'))->toBeFalse()
        ->and($catalog->isActive($code))->toBeTrue()
        ->and($catalog->isActive($code, $channel->id))->toBeTrue()
        ->and($catalog->isActive($code, core()->getDefaultChannel()->id))->toBeFalse();
});

// ============================================================================
// Gallery Actions
// ============================================================================

it('should offer customize only to an active theme and leave previewing to the editor', function () {
    $this->loginAsAdmin();

    get(route('admin.appearance.themes.index'))
        ->assertOk()
        ->assertSee('v-if="theme.status === \'active\'"', false)
        ->assertDontSee('appearance-preview', false);
});

// ============================================================================
// Preview
// ============================================================================

it('should preview the requested theme with that theme own sections', function () {
    $code = installGalleryTheme();

    $channel = core()->getDefaultChannel();

    sectionOfTheme($code, $channel->id, SectionTypeEnum::STATIC_CONTENT->value, ['html' => '<p>Gallery hero</p>']);

    sectionOfTheme($channel->theme, $channel->id, SectionTypeEnum::STATIC_CONTENT->value, ['html' => '<p>Default hero</p>']);

    $this->loginAsAdmin();

    get(route('shop.appearance.preview', ['theme' => $code, 'channel' => $channel->code]))
        ->assertOk()
        ->assertSee('<p>Gallery hero</p>', false)
        ->assertDontSee('<p>Default hero</p>', false);

    expect(themes()->current()->code)->toBe($code);
});

it('should hand the layout the previewed theme footer rather than the channel own', function () {
    $code = installGalleryTheme();

    $channel = core()->getDefaultChannel();

    Section::query()->where('type', SectionTypeEnum::FOOTER_LINKS->value)->get()->each->delete();

    sectionOfTheme($code, $channel->id, SectionTypeEnum::FOOTER_LINKS->value, [
        'column_1' => [['title' => 'Gallery Footer Link', 'url' => '/gallery']],
    ]);

    sectionOfTheme($channel->theme, $channel->id, SectionTypeEnum::FOOTER_LINKS->value, [
        'column_1' => [['title' => 'Default Footer Link', 'url' => '/default']],
    ]);

    $this->loginAsAdmin();

    get(route('shop.appearance.preview', ['theme' => $code, 'channel' => $channel->code]))
        ->assertOk()
        ->assertSee('Gallery Footer Link')
        ->assertDontSee('Default Footer Link');

    expect($channel->fresh()->theme)->not->toBe($code);
});

it('should preview an installed theme no channel runs yet', function () {
    $code = installGalleryTheme();

    $this->loginAsAdmin();

    get(route('shop.appearance.preview', ['theme' => $code]))
        ->assertOk()
        ->assertSee(e(trans('shop::app.home.index.preview-banner', [
            'theme' => 'Gallery',
            'channel' => core()->getDefaultChannel()->name,
        ])), false);
});

it('should not preview a theme this installation does not have', function (mixed $theme) {
    $this->loginAsAdmin();

    get(route('shop.appearance.preview').'?'.http_build_query(['theme' => $theme]))->assertNotFound();
})->with([
    'unknown' => ['not-a-theme'],
    'list' => [['default']],
]);

it('should keep the preview of any theme off limits to guests', function () {
    get(route('shop.appearance.preview', ['theme' => core()->getDefaultChannel()->theme]))->assertForbidden();
});

it('should name the previewed theme in every resized image url of the preview', function () {
    $code = installGalleryTheme();

    $channel = core()->getDefaultChannel();

    $image = 'themes/'.$code.'/sections/1/slide.webp';

    sectionOfTheme($code, $channel->id, SectionTypeEnum::IMAGE_CAROUSEL->value, [
        'images' => [['image' => $image, 'title' => 'Slide', 'link' => '']],
    ]);

    $this->loginAsAdmin();

    $html = get(route('shop.appearance.preview', ['theme' => $code, 'channel' => $channel->code]))
        ->assertOk()
        ->getContent();

    foreach (['large', 'medium', 'small'] as $size) {
        expect($html)->toContain('/cache/'.$size.'@'.$code.'/'.$image)
            ->and($html)->not->toContain('/cache/'.$size.'/'.$image);
    }
});

it('should ask the catalog for the preview carousels with the previewed theme named', function () {
    $code = installGalleryTheme();

    $channel = core()->getDefaultChannel();

    sectionOfTheme($code, $channel->id, SectionTypeEnum::PRODUCT_CAROUSEL->value, [
        'title' => 'Picks',
        'filters' => ['sort' => 'name-asc'],
    ]);

    sectionOfTheme($code, $channel->id, SectionTypeEnum::CATEGORY_CAROUSEL->value, [
        'title' => 'Aisles',
        'filters' => ['parent_id' => '1'],
    ]);

    $this->loginAsAdmin();

    get(route('shop.appearance.preview', ['theme' => $code, 'channel' => $channel->code]))
        ->assertOk()
        ->assertSee(route('shop.api.products.index', ['sort' => 'name-asc', PreviewImageTheme::PARAMETER => $code]))
        ->assertSee(route('shop.api.categories.index', ['parent_id' => '1', PreviewImageTheme::PARAMETER => $code]));
});

it('should render a carousel whose stored filters are a single value rather than a list', function () {
    $code = installGalleryTheme();

    $channel = core()->getDefaultChannel();

    sectionOfTheme($code, $channel->id, SectionTypeEnum::PRODUCT_CAROUSEL->value, [
        'title' => 'Picks',
        'filters' => 'sort',
    ]);

    $this->loginAsAdmin();

    get(route('shop.appearance.preview', ['theme' => $code, 'channel' => $channel->code]))
        ->assertOk()
        ->assertSee(PreviewImageTheme::PARAMETER.'='.$code, false);
});

it('should name the previewed theme in the catalog image urls the preview lists, without caching them', function () {
    $code = installGalleryTheme();

    [$product, $path] = previewCatalogProduct($code);

    adminSignedInBesideTheStorefront();

    $response = getJson(route('shop.api.products.index', [
        'sort' => 'created_at-desc',
        PreviewImageTheme::PARAMETER => $code,
    ]))
        ->assertOk()
        ->assertHeader('Cache-Control', 'no-cache, private');

    $image = collect($response->json('data'))->firstWhere('id', $product->id)['base_image'];

    expect($image['card_image_url'])->toEndWith('/cache/card@'.$code.'/'.$path)
        ->and($image['small_image_url'])->toEndWith('/cache/small@'.$code.'/'.$path)
        ->and($image['original_image_url'])->toEndWith('/cache/original/'.$path);
});

it('should not let a visitor or an admin who cannot edit sections pin the catalog images to a theme', function (?array $permissions) {
    $code = installGalleryTheme();

    [$product, $path] = previewCatalogProduct($code);

    if (! is_null($permissions)) {
        adminSignedInBesideTheStorefront($permissions);
    }

    $response = getJson(route('shop.api.products.index', [
        'sort' => 'created_at-desc',
        PreviewImageTheme::PARAMETER => $code,
    ]))
        ->assertOk()
        ->assertHeader('Cache-Control', 'max-age=60, public');

    $image = collect($response->json('data'))->firstWhere('id', $product->id)['base_image'];

    expect($image)->not->toHaveKey('card_image_url')
        ->and($image['small_image_url'])->toEndWith('/cache/small/'.$path);
})->with([
    'guest' => [null],
    'admin without the appearance permission' => [['sales', 'sales.orders']],
]);

it('should hand the editor a preview of the theme being customized', function () {
    $channel = core()->getDefaultChannel();

    $this->loginAsAdmin();

    get(route('admin.appearance.sections.index', ['code' => $channel->theme]))
        ->assertOk()
        ->assertSee('theme='.$channel->theme, false);
});

it('should name the channel by code in every url the editor hands out', function () {
    $other = Channel::factory()->create(['theme' => core()->getDefaultChannel()->theme]);

    $this->loginAsAdmin();

    $content = get(route('admin.appearance.sections.index', [
        'code' => $other->theme,
        'channel' => $other->code,
    ]))
        ->assertOk()
        ->content();

    expect($content)->toContain('channel='.$other->code)
        ->and($content)->not->toContain('channel='.$other->id);
});
