<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FilesystemAdapter;
use Webkul\Category\Models\Category;
use Webkul\Core\Core as BaseCore;
use Webkul\Core\Facades\Core;
use Webkul\Core\Models\Channel;
use Webkul\ImageCache\Exceptions\InvalidTemplate;
use Webkul\ImageCache\TemplateRegistry;
use Webkul\ImageCache\Templates\Large;
use Webkul\ImageCache\Templates\Medium;
use Webkul\ImageCache\Templates\Small;
use Webkul\Shop\Http\Resources\CategoryResource;
use Webkul\Shop\Tests\Fixtures\ImageCache\CategoryCard;
use Webkul\Shop\Tests\Fixtures\ImageCache\ProductCard;

use function Pest\Laravel\getJson;

beforeEach(function () {
    config(['imagecache.templates' => [
        'small' => Small::class,
        'medium' => Medium::class,
        'large' => Large::class,
    ]]);

    $this->category = Category::factory()->create();

    $this->logoPath = 'category/'.$this->category->id.'/logo.png';

    $this->bannerPath = 'category/'.$this->category->id.'/banner.png';

    foreach ([$this->logoPath, $this->bannerPath] as $path) {
        Storage::put($path, UploadedFile::fake()->image('source.png', 400, 300)->getContent());
    }

    $this->category->translateOrNew(app()->getLocale())->fill([
        'name' => 'Summer Edit '.$this->category->id,
        'slug' => 'summer-edit-'.$this->category->id,
        'logo_alt' => 'Summer logo',
    ]);

    $this->category->logo_path = $this->logoPath;

    $this->category->banner_path = $this->bannerPath;

    $this->category->save();
});

afterEach(function () {
    Storage::deleteDirectory('category/'.$this->category->id);
});

/**
 * Register a storefront theme with image templates and a category image list, run by a channel on its own host.
 */
function channelRunningCategoryTemplates(string $code, ?array $templates, array $categoryImages = [], array $productImages = []): Channel
{
    config(['themes.shop.'.$code => array_merge(config('themes.shop.default'), [
        'name' => ucfirst($code),
        'customize' => [
            'image_cache' => [
                'templates' => $templates,
                'product_images' => $productImages,
                'category_images' => $categoryImages,
            ],
        ],
    ])]);

    return Channel::factory()->create([
        'theme' => $code,
        'hostname' => 'http://'.$code.'.test',
    ]);
}

/**
 * The category as the storefront category resource hands it out on a channel.
 */
function categoryOn(Channel $channel, Category $category): array
{
    core()->setCurrentChannel($channel);

    return (new CategoryResource($category->fresh()))->resolve(request());
}

it('should give a category image the core sizes and the original, exactly as before, when the theme lists nothing', function () {
    $category = categoryOn(channelRunningCategoryTemplates('plain', null), $this->category);

    expect($category['logo'])->toBe([
        'small_image_url' => url('cache/small/'.$this->logoPath),
        'medium_image_url' => url('cache/medium/'.$this->logoPath),
        'large_image_url' => url('cache/large/'.$this->logoPath),
        'original_image_url' => url('cache/original/'.$this->logoPath),
        'alt' => 'Summer logo',
    ])
        ->and(array_keys($category['banner']))->toBe(['small_image_url', 'medium_image_url', 'large_image_url', 'original_image_url', 'alt'])
        ->and($category['banner']['alt'])->toBe('Summer Edit '.$this->category->id);
});

it('should give a category logo and banner a url for a template the theme lists for category images', function () {
    $channel = channelRunningCategoryTemplates('fashion', ['category_card' => CategoryCard::class], ['category_card']);

    $category = categoryOn($channel, $this->category);

    expect(array_keys($category['logo']))
        ->toBe(['small_image_url', 'medium_image_url', 'large_image_url', 'category_card_image_url', 'original_image_url', 'alt'])
        ->and($category['logo']['category_card_image_url'])->toBe(url('cache/category_card/'.$this->logoPath))
        ->and($category['banner']['category_card_image_url'])->toBe(url('cache/category_card/'.$this->bannerPath));
});

it('should keep templates listed for product images out of category images, and category ones out of product images', function () {
    $channel = channelRunningCategoryTemplates(
        'fashion',
        ['category_card' => CategoryCard::class, 'product_card' => ProductCard::class],
        ['category_card'],
        ['product_card']
    );

    $category = categoryOn($channel, $this->category);

    expect($category['logo'])->toHaveKey('category_card_image_url')
        ->and($category['logo'])->not->toHaveKey('product_card_image_url')
        ->and(app(TemplateRegistry::class)->listed('fashion', TemplateRegistry::PRODUCT_IMAGES))->toBe(['product_card']);
});

it('should not add a template to category images that the theme registers but does not list', function () {
    $channel = channelRunningCategoryTemplates('fashion', ['category_card' => CategoryCard::class]);

    expect(categoryOn($channel, $this->category)['logo'])->not->toHaveKey('category_card_image_url');
});

it('should give each channel the category images of the theme it runs', function () {
    $fashion = channelRunningCategoryTemplates('fashion', ['category_card' => CategoryCard::class], ['category_card']);

    $plain = channelRunningCategoryTemplates('plain', ['category_card' => CategoryCard::class]);

    expect(categoryOn($fashion, $this->category)['logo'])->toHaveKey('category_card_image_url')
        ->and(categoryOn($plain, $this->category)['logo'])->not->toHaveKey('category_card_image_url');
});

it('should skip and report a category image name that is not a registered template', function () {
    Exceptions::fake();

    $channel = channelRunningCategoryTemplates('broken', ['category_card' => CategoryCard::class], ['missing', 'category_card', 'category_card']);

    expect(array_keys(categoryOn($channel, $this->category)['logo']))
        ->toBe(['small_image_url', 'medium_image_url', 'large_image_url', 'category_card_image_url', 'original_image_url', 'alt']);

    Exceptions::assertReported(fn (InvalidTemplate $exception) => str_contains($exception->getMessage(), '[missing]')
        && str_contains($exception->getMessage(), 'category_images'));
});

it('should leave out the image of a category whose file is gone', function () {
    Storage::delete($this->logoPath);

    $category = categoryOn(core()->getDefaultChannel(), $this->category);

    expect($category)->not->toHaveKey('logo')
        ->and($category)->toHaveKey('banner');
});

it('should size every category image through the image cache on a disk that is not local', function () {
    Storage::shouldReceive('exists')->andReturnTrue();

    Storage::shouldReceive('getAdapter')->andReturn(Mockery::mock(FilesystemAdapter::class));

    Storage::shouldReceive('url')->andReturnUsing(fn ($path) => 'https://cdn.example.com/'.$path);

    Storage::shouldReceive('deleteDirectory');

    $channel = channelRunningCategoryTemplates('fashion', ['category_card' => CategoryCard::class], ['category_card']);

    expect(categoryOn($channel, $this->category)['logo'])->toBe([
        'small_image_url' => url('cache/small/'.$this->logoPath),
        'medium_image_url' => url('cache/medium/'.$this->logoPath),
        'large_image_url' => url('cache/large/'.$this->logoPath),
        'category_card_image_url' => url('cache/category_card/'.$this->logoPath),
        'original_image_url' => url('cache/original/'.$this->logoPath),
        'alt' => 'Summer logo',
    ]);
});

it('should carry the theme category images into the storefront category api', function () {
    config(['themes.shop.'.core()->getDefaultChannel()->theme.'.customize.image_cache' => [
        'templates' => ['category_card' => CategoryCard::class],
        'category_images' => ['category_card'],
    ]]);

    Core::clearResolvedInstance(BaseCore::class);

    $listed = collect(getJson(route('shop.api.categories.index', ['name' => 'Summer Edit '.$this->category->id]))->assertOk()->json('data'))
        ->firstWhere('id', $this->category->id);

    expect($listed['logo']['category_card_image_url'])->toEndWith('/cache/category_card/'.$this->logoPath)
        ->and($listed['banner']['small_image_url'])->toEndWith('/cache/small/'.$this->bannerPath);
});
