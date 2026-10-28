<?php

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\File;
use Illuminate\Testing\TestResponse;
use Webkul\Core\Core as BaseCore;
use Webkul\Core\Facades\Core;
use Webkul\Core\Models\Channel;
use Webkul\ImageCache\Exceptions\InvalidTemplate;
use Webkul\ImageCache\Exceptions\UnprocessableImage;
use Webkul\ImageCache\Providers\ImageCacheServiceProvider;
use Webkul\ImageCache\TemplateRegistry;
use Webkul\ImageCache\Templates\Large;
use Webkul\ImageCache\Templates\Medium;
use Webkul\ImageCache\Templates\Small;
use Webkul\Shop\Tests\Fixtures\ImageCache\AbortingFilter;
use Webkul\Shop\Tests\Fixtures\ImageCache\CountingTemplateRegistry;
use Webkul\Shop\Tests\Fixtures\ImageCache\FailingFilter;
use Webkul\Shop\Tests\Fixtures\ImageCache\HiddenFilter;
use Webkul\Shop\Tests\Fixtures\ImageCache\InjectedFilter;
use Webkul\Shop\Tests\Fixtures\ImageCache\NotATemplate;
use Webkul\Shop\Tests\Fixtures\ImageCache\PosterSmall;
use Webkul\Shop\Tests\Fixtures\ImageCache\ProductCard;
use Webkul\Shop\Tests\Fixtures\ImageCache\RespondingFilter;
use Webkul\Shop\Tests\Fixtures\ImageCache\SizedFilter;
use Webkul\Shop\Tests\Fixtures\ImageCache\WideSmall;

use function Pest\Laravel\get;

/**
 * Register a storefront theme with the given image templates, served by a channel on its own host.
 */
function themeWithImageTemplates(string $code, ?array $templates): Channel
{
    config(['themes.shop.'.$code => array_merge(config('themes.shop.default'), [
        'name' => ucfirst($code),
        'customize' => ['image_cache' => ['templates' => $templates]],
    ])]);

    return Channel::factory()->create([
        'theme' => $code,
        'hostname' => 'http://'.$code.'.test',
    ]);
}

/**
 * Request an image template on a host, resolving that host's channel afresh.
 */
function imageOn(string $host, string $template, string $path, array $headers = []): TestResponse
{
    Core::clearResolvedInstance(BaseCore::class);

    return get('http://'.$host.'/cache/'.$template.'/'.$path, $headers);
}

/**
 * The width and height of an image response.
 */
function dimensionsOf(TestResponse $response): array
{
    [$width, $height] = getimagesizefromstring($response->getContent());

    return [$width, $height];
}

beforeEach(function () {
    config(['imagecache.templates' => [
        'small' => Small::class,
        'medium' => Medium::class,
        'large' => Large::class,
    ]]);

    $this->source = 'imagecache-templates/source.png';

    File::ensureDirectoryExists(storage_path('app/public/imagecache-templates'));

    File::put(storage_path('app/public/'.$this->source), UploadedFile::fake()->image('source.png', 800, 600)->getContent());
});

afterEach(function () {
    File::deleteDirectory(storage_path('app/public/imagecache-templates'));
});

// ============================================================================
// Template Registry
// ============================================================================

it('should resolve the core templates for a theme that registers none', function () {
    themeWithImageTemplates('plain', null);

    $registry = app(TemplateRegistry::class);

    expect($registry->theme('plain'))->toBe([])
        ->and($registry->all('plain'))->toBe(config('imagecache.templates'))
        ->and($registry->all('default'))->toBe(config('imagecache.templates'))
        ->and($registry->all())->toBe(config('imagecache.templates'));
});

it('should let a theme override a core template and inherit the rest', function () {
    themeWithImageTemplates('poster', ['small' => PosterSmall::class]);

    $registry = app(TemplateRegistry::class);

    expect($registry->find('small', 'poster'))->toBe(PosterSmall::class)
        ->and($registry->find('medium', 'poster'))->toBe(Medium::class)
        ->and($registry->find('large', 'poster'))->toBe(Large::class)
        ->and(config('imagecache.templates.small'))->toBe(Small::class);
});

it('should let a theme add a template of its own without touching the core ones', function () {
    themeWithImageTemplates('poster', ['product_card' => ProductCard::class]);

    $registry = app(TemplateRegistry::class);

    expect($registry->has('product_card', 'poster'))->toBeTrue()
        ->and($registry->has('product_card', 'default'))->toBeFalse()
        ->and(array_keys($registry->all('poster')))->toBe(['small', 'medium', 'large', 'product_card'])
        ->and(config('imagecache.templates'))->not->toHaveKey('product_card');
});

it('should share one template registry across a request', function () {
    expect(app(TemplateRegistry::class))->toBe(app(TemplateRegistry::class));
});

it('should check each template only once, however many image urls a page builds', function () {
    app()->scoped(TemplateRegistry::class, CountingTemplateRegistry::class);

    $registry = app(TemplateRegistry::class);

    foreach (range(1, 3) as $url) {
        $registry->find('small');
    }

    expect($registry->checks)->toBe(count(config('imagecache.templates')));
});

it('should keep a template registry subclass bound before the image cache registers', function () {
    app()->bind(TemplateRegistry::class, CountingTemplateRegistry::class);

    (new ImageCacheServiceProvider(app()))->register();

    expect(app(TemplateRegistry::class))->toBeInstanceOf(CountingTemplateRegistry::class);
});

it('should check the core templates again once their configuration changes', function () {
    $registry = app(TemplateRegistry::class);

    expect($registry->find('small'))->toBe(Small::class);

    config(['imagecache.templates.small' => WideSmall::class]);

    expect($registry->find('small'))->toBe(WideSmall::class);
});

it('should check the templates of a theme again once they change', function () {
    themeWithImageTemplates('poster', ['small' => PosterSmall::class]);

    $registry = app(TemplateRegistry::class);

    expect($registry->find('small', 'poster'))->toBe(PosterSmall::class);

    config(['themes.shop.poster.customize.image_cache.templates.small' => WideSmall::class]);

    expect($registry->find('small', 'poster'))->toBe(WideSmall::class);
});

it('should resolve the same template name to each theme own class', function () {
    themeWithImageTemplates('poster', ['small' => PosterSmall::class]);

    themeWithImageTemplates('wide', ['small' => WideSmall::class]);

    $registry = app(TemplateRegistry::class);

    expect($registry->find('small', 'poster'))->toBe(PosterSmall::class)
        ->and($registry->find('small', 'wide'))->toBe(WideSmall::class)
        ->and($registry->find('small', 'default'))->toBe(Small::class);
});

// ============================================================================
// Serving Images
// ============================================================================

it('should serve each channel the dimensions its theme defines for the same image', function () {
    themeWithImageTemplates('poster', ['small' => PosterSmall::class, 'product_card' => ProductCard::class]);

    themeWithImageTemplates('wide', ['small' => WideSmall::class]);

    themeWithImageTemplates('plain', null);

    expect(dimensionsOf(imageOn('poster.test', 'small', $this->source)->assertOk()))->toBe([300, 200])
        ->and(dimensionsOf(imageOn('wide.test', 'small', $this->source)->assertOk()))->toBe([400, 100])
        ->and(dimensionsOf(imageOn('plain.test', 'small', $this->source)->assertOk()))->toBe([100, 100])
        ->and(dimensionsOf(imageOn('poster.test', 'product_card', $this->source)->assertOk()))->toBe([240, 320]);

    imageOn('wide.test', 'product_card', $this->source)->assertNotFound();

    imageOn('plain.test', 'product_card', $this->source)->assertNotFound();
});

it('should fall back to the core template for a name the theme does not register', function () {
    themeWithImageTemplates('poster', ['small' => PosterSmall::class]);

    expect(dimensionsOf(imageOn('poster.test', 'medium', $this->source)->assertOk()))->toBe([300, 300])
        ->and(dimensionsOf(imageOn('poster.test', 'large', $this->source)->assertOk()))->toBe([600, 600]);
});

it('should serve a core size the configuration leaves out with the package template of that name', function () {
    config(['imagecache.templates' => ['small' => WideSmall::class]]);

    themeWithImageTemplates('plain', null);

    expect(dimensionsOf(imageOn('plain.test', 'small', $this->source)->assertOk()))->toBe([400, 100])
        ->and(dimensionsOf(imageOn('plain.test', 'medium', $this->source)->assertOk()))->toBe([300, 300])
        ->and(dimensionsOf(imageOn('plain.test', 'large', $this->source)->assertOk()))->toBe([600, 600]);
});

it('should never hand one theme the image another theme produced under the same name', function () {
    themeWithImageTemplates('poster', ['small' => PosterSmall::class]);

    themeWithImageTemplates('wide', ['small' => WideSmall::class]);

    $poster = imageOn('poster.test', 'small', $this->source)->assertOk();

    $wide = imageOn('wide.test', 'small', $this->source)->assertOk();

    expect($poster->getContent())->not->toBe($wide->getContent())
        ->and($poster->headers->get('Etag'))->not->toBe($wide->headers->get('Etag'));

    imageOn('poster.test', 'small', $this->source, ['If-None-Match' => $poster->headers->get('Etag')])
        ->assertStatus(304);

    $revalidated = imageOn('wide.test', 'small', $this->source, ['If-None-Match' => $poster->headers->get('Etag')])
        ->assertOk();

    expect(dimensionsOf($revalidated))->toBe([400, 100]);
});

it('should only resolve a requested name against the registered templates', function (string $template) {
    themeWithImageTemplates('poster', ['small' => PosterSmall::class]);

    imageOn('poster.test', $template, $this->source)->assertNotFound();
})->with([
    'unregistered name' => ['thumbnail'],
    'core class name' => [rawurlencode(Large::class)],
    'theme class name' => [rawurlencode(PosterSmall::class)],
    'php class' => ['stdClass'],
]);

// ============================================================================
// Invalid Templates
// ============================================================================

it('should skip and report an invalid theme template, keeping the core template of that name', function () {
    Exceptions::fake();

    themeWithImageTemplates('broken', [
        'small' => NotATemplate::class,
        'hidden' => HiddenFilter::class,
        'missing' => 'Webkul\\Missing\\ImageTemplate',
        'numeric' => 42,
        'card' => ProductCard::class,
    ]);

    expect(app(TemplateRegistry::class)->theme('broken'))->toBe(['card' => ProductCard::class]);

    Exceptions::assertReported(InvalidTemplate::class);

    expect(dimensionsOf(imageOn('broken.test', 'small', $this->source)->assertOk()))->toBe([100, 100]);

    imageOn('broken.test', 'hidden', $this->source)->assertNotFound();

    imageOn('broken.test', 'missing', $this->source)->assertNotFound();
});

it('should report each invalid theme template once, however many images ask for it', function () {
    Exceptions::fake();

    themeWithImageTemplates('broken', [
        'small' => NotATemplate::class,
        'missing' => 'Webkul\\Missing\\ImageTemplate',
    ]);

    foreach (['first', 'second', 'third'] as $name) {
        File::copy(storage_path('app/public/'.$this->source), storage_path('app/public/imagecache-templates/'.$name.'.png'));

        imageOn('broken.test', 'small', 'imagecache-templates/'.$name.'.png')->assertOk();
    }

    expect(collect(Exceptions::reported())->whereInstanceOf(InvalidTemplate::class))->toHaveCount(2);

    Exceptions::assertReported(fn (InvalidTemplate $exception) => str_contains($exception->getMessage(), '[small]'));

    Exceptions::assertReported(fn (InvalidTemplate $exception) => str_contains($exception->getMessage(), '[missing]'));
});

it('should report a core template that cannot be applied and serve the package template of that name instead', function (mixed $template) {
    Exceptions::fake();

    config(['imagecache.templates.small' => $template]);

    themeWithImageTemplates('plain', null);

    expect(app(TemplateRegistry::class)->find('small', 'plain'))->toBe(Small::class)
        ->and(dimensionsOf(imageOn('plain.test', 'small', $this->source)->assertOk()))->toBe([100, 100]);

    Exceptions::assertReported(fn (InvalidTemplate $exception) => str_contains($exception->getMessage(), '[small] of config/imagecache.php'));
})->with([
    'class that no longer exists' => ['Webkul\\Missing\\ImageTemplate'],
    'closure' => [fn () => fn ($image) => $image->cover(10, 10)],
    'class without a filter' => [NotATemplate::class],
    'class hiding its filter' => [HiddenFilter::class],
    'object rather than a class name' => [new ProductCard],
    'number' => [42],
]);

it('should answer a core template under a name the package does not ship as not found when it cannot be applied, reporting it', function () {
    Exceptions::fake();

    config(['imagecache.templates.thumb' => NotATemplate::class]);

    themeWithImageTemplates('plain', null);

    imageOn('plain.test', 'thumb', $this->source)->assertNotFound();

    Exceptions::assertReported(fn (InvalidTemplate $exception) => str_contains($exception->getMessage(), '[thumb] of config/imagecache.php'));
});

it('should report each invalid core template once, however many images ask for it', function () {
    Exceptions::fake();

    config(['imagecache.templates.small' => 'Webkul\\Missing\\ImageTemplate']);

    themeWithImageTemplates('plain', null);

    foreach (['first', 'second', 'third'] as $name) {
        File::copy(storage_path('app/public/'.$this->source), storage_path('app/public/imagecache-templates/'.$name.'.png'));

        imageOn('plain.test', 'small', 'imagecache-templates/'.$name.'.png')->assertOk();
    }

    expect(collect(Exceptions::reported())->whereInstanceOf(InvalidTemplate::class))->toHaveCount(1);
});

// ============================================================================
// Failing Templates
// ============================================================================

it('should answer an image whose template filter fails as not found, and report the failure', function () {
    Exceptions::fake();

    themeWithImageTemplates('failing', ['small' => FailingFilter::class]);

    imageOn('failing.test', 'small', $this->source)->assertNotFound();

    Exceptions::assertReported(fn (UnprocessableImage $exception) => $exception->getPrevious() instanceof Error);

    Exceptions::assertReported(fn (UnprocessableImage $exception) => $exception->getMessage() === 'Image ['.$this->source.'] could not be processed through template [small]: The filter could not run.');

    Exceptions::assertReported(fn (UnprocessableImage $exception) => $exception->context() === [
        'template' => 'small',
        'filename' => $this->source,
        'theme' => 'failing',
        'mime' => 'image/png',
    ]);
});

it('should answer an image whose template filter aborts as not found, reporting nothing', function (string $filter) {
    Exceptions::fake();

    themeWithImageTemplates('aborting', ['small' => $filter]);

    imageOn('aborting.test', 'small', $this->source)->assertNotFound();

    Exceptions::assertNothingReported();
})->with([
    'abort with a status' => [AbortingFilter::class],
    'abort with a response' => [RespondingFilter::class],
]);

// ============================================================================
// Building Templates
// ============================================================================

it('should build a template filter through the container, injecting what its constructor asks for', function () {
    themeWithImageTemplates('injected', ['small' => InjectedFilter::class]);

    expect(dimensionsOf(imageOn('injected.test', 'small', $this->source)->assertOk()))->toBe([200, 200]);
});

it('should let a container binding replace the class of a core template', function () {
    app()->bind(Small::class, WideSmall::class);

    themeWithImageTemplates('plain', null);

    expect(dimensionsOf(imageOn('plain.test', 'small', $this->source)->assertOk()))->toBe([400, 100]);
});

it('should report a template filter whose constructor the container cannot satisfy, naming what it asks for', function () {
    Exceptions::fake();

    themeWithImageTemplates('sized', ['small' => SizedFilter::class]);

    imageOn('sized.test', 'small', $this->source)->assertNotFound();

    Exceptions::assertReported(fn (UnprocessableImage $exception) => $exception->getPrevious() instanceof BindingResolutionException);

    Exceptions::assertReported(fn (UnprocessableImage $exception) => str_contains($exception->getMessage(), 'int $size'));
});

// ============================================================================
// Reserved Names
// ============================================================================

it('should not let a theme take over a reserved image route name', function () {
    themeWithImageTemplates('poster', ['original' => PosterSmall::class]);

    expect(dimensionsOf(imageOn('poster.test', 'original', $this->source)->assertOk()))->toBe([800, 600]);
});

it('should refuse a reserved image route name in any letter case, which the route could never reach', function () {
    Exceptions::fake();

    themeWithImageTemplates('poster', [
        'Original' => PosterSmall::class,
        'LOGO' => PosterSmall::class,
        'poster_small' => PosterSmall::class,
    ]);

    expect(array_keys(app(TemplateRegistry::class)->theme('poster')))->toBe(['poster_small']);

    Exceptions::assertReported(fn (InvalidTemplate $exception) => str_contains($exception->getMessage(), '[Original]'));

    Exceptions::assertReported(fn (InvalidTemplate $exception) => str_contains($exception->getMessage(), '[LOGO]'));
});

// ============================================================================
// Uninstalled Themes
// ============================================================================

it('should use the default storefront theme templates for a channel whose theme is not installed', function () {
    config(['themes.shop.'.config('themes.shop-default').'.customize.image_cache.templates' => ['small' => WideSmall::class]]);

    Channel::factory()->create([
        'theme' => 'uninstalled',
        'hostname' => 'http://uninstalled.test',
    ]);

    expect(dimensionsOf(imageOn('uninstalled.test', 'small', $this->source)->assertOk()))->toBe([400, 100]);
});

// ============================================================================
// Pinned Themes
// ============================================================================

it('should list the pinned theme templates and name that theme in every image url but the original', function () {
    themeWithImageTemplates('poster', ['product_card' => ProductCard::class]);

    config(['themes.shop.poster.customize.image_cache.product_images' => ['product_card']]);

    request()->attributes->set(TemplateRegistry::PINNED_THEME, 'poster');

    expect(image_urls($this->source, TemplateRegistry::PRODUCT_IMAGES))->toBe([
        'small_image_url' => url('cache/small@poster/'.$this->source),
        'medium_image_url' => url('cache/medium@poster/'.$this->source),
        'large_image_url' => url('cache/large@poster/'.$this->source),
        'product_card_image_url' => url('cache/product_card@poster/'.$this->source),
        'original_image_url' => url('cache/original/'.$this->source),
    ]);
});

it('should leave image urls bare when no installed theme is pinned', function (?string $pinned) {
    themeWithImageTemplates('poster', null);

    request()->attributes->set(TemplateRegistry::PINNED_THEME, $pinned);

    expect(image_urls($this->source))->toBe([
        'small_image_url' => url('cache/small/'.$this->source),
        'medium_image_url' => url('cache/medium/'.$this->source),
        'large_image_url' => url('cache/large/'.$this->source),
        'original_image_url' => url('cache/original/'.$this->source),
    ]);
})->with([
    'nothing pinned' => [null],
    'uninstalled theme' => ['uninstalled'],
    'theme configuration path' => ['poster.customize'],
]);

// ============================================================================
// Theme Qualified Templates
// ============================================================================

it('should cut an image as the theme its url names, whatever theme the host channel runs', function () {
    themeWithImageTemplates('poster', ['small' => PosterSmall::class, 'product_card' => ProductCard::class]);

    themeWithImageTemplates('wide', ['small' => WideSmall::class]);

    expect(dimensionsOf(imageOn('wide.test', 'small@poster', $this->source)->assertOk()))->toBe([300, 200])
        ->and(dimensionsOf(imageOn('wide.test', 'medium@poster', $this->source)->assertOk()))->toBe([300, 300])
        ->and(dimensionsOf(imageOn('wide.test', 'product_card@poster', $this->source)->assertOk()))->toBe([240, 320])
        ->and(dimensionsOf(imageOn('wide.test', 'small', $this->source)->assertOk()))->toBe([400, 100]);
});

it('should serve a url that names its theme the same image on every host', function () {
    themeWithImageTemplates('poster', ['small' => PosterSmall::class]);

    themeWithImageTemplates('wide', ['small' => WideSmall::class]);

    $onPoster = imageOn('poster.test', 'small@poster', $this->source)->assertOk();

    $onWide = imageOn('wide.test', 'small@poster', $this->source)->assertOk();

    expect($onWide->headers->get('Etag'))->toBe($onPoster->headers->get('Etag'))
        ->and($onWide->headers->get('Etag'))->not->toBe(imageOn('wide.test', 'small', $this->source)->headers->get('Etag'));
});

it('should answer a url naming no installed theme, or a reserved name with a theme, as not found', function (string $template) {
    themeWithImageTemplates('poster', ['small' => PosterSmall::class]);

    imageOn('poster.test', $template, $this->source)->assertNotFound();
})->with([
    'uninstalled theme' => ['small@uninstalled'],
    'theme configuration path' => ['small@poster.customize'],
    'no theme' => ['small@'],
    'no template' => ['@poster'],
    'original with a theme' => ['original@poster'],
    'download with a theme' => ['download@poster'],
]);
