<?php

use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\File;
use Illuminate\Testing\TestResponse;
use Webkul\Core\Core as BaseCore;
use Webkul\Core\Facades\Core;
use Webkul\Core\Models\Channel;
use Webkul\ImageCache\Exceptions\InvalidTemplate;
use Webkul\ImageCache\TemplateRegistry;
use Webkul\ImageCache\Templates\Large;
use Webkul\ImageCache\Templates\Medium;
use Webkul\ImageCache\Templates\Small;
use Webkul\Shop\Tests\Fixtures\ImageCache\HiddenFilter;
use Webkul\Shop\Tests\Fixtures\ImageCache\NotATemplate;
use Webkul\Shop\Tests\Fixtures\ImageCache\PosterSmall;
use Webkul\Shop\Tests\Fixtures\ImageCache\ProductCard;
use Webkul\Shop\Tests\Fixtures\ImageCache\WideSmall;

use function Pest\Laravel\get;

beforeEach(function () {
    config(['imagecache.templates' => [
        'small' => Small::class,
        'medium' => Medium::class,
        'large' => Large::class,
    ]]);

    $this->source = 'imagecache-templates/source.png';

    File::ensureDirectoryExists(storage_path('app/public/imagecache-templates'));

    image_manager()->create(800, 600)->fill('ff0000')->toPng()->save(storage_path('app/public/'.$this->source));
});

afterEach(function () {
    File::deleteDirectory(storage_path('app/public/imagecache-templates'));
});

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

it('should resolve the same template name to each theme own class', function () {
    themeWithImageTemplates('poster', ['small' => PosterSmall::class]);

    themeWithImageTemplates('wide', ['small' => WideSmall::class]);

    $registry = app(TemplateRegistry::class);

    expect($registry->find('small', 'poster'))->toBe(PosterSmall::class)
        ->and($registry->find('small', 'wide'))->toBe(WideSmall::class)
        ->and($registry->find('small', 'default'))->toBe(Small::class);
});

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

it('should use the default storefront theme templates for a channel whose theme is not installed', function () {
    config(['themes.shop.'.config('themes.shop-default').'.customize.image_cache.templates' => ['small' => WideSmall::class]]);

    Channel::factory()->create([
        'theme' => 'uninstalled',
        'hostname' => 'http://uninstalled.test',
    ]);

    expect(dimensionsOf(imageOn('uninstalled.test', 'small', $this->source)->assertOk()))->toBe([400, 100]);
});

it('should still apply a closure registered in the core configuration', function () {
    config(['imagecache.templates.thumb' => fn ($image) => $image->cover(50, 40)]);

    themeWithImageTemplates('plain', null);

    expect(dimensionsOf(imageOn('plain.test', 'thumb', $this->source)->assertOk()))->toBe([50, 40]);
});
