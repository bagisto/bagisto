<?php

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Helpers\CacheGeneration;
use Webkul\Core\Models\CoreConfig;
use Webkul\Core\Repositories\CoreConfigRepository;
use Webkul\Sitemap\Jobs\ProcessSitemap;
use Webkul\Sitemap\Models\Sitemap;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should show the sitemap index page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.marketing.search_seo.sitemaps.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.title'))
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.create-btn'));
});

it('should fail the validation with errors when certain field not provided when store in the sitemap', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.sitemaps.store'))
        ->assertJsonValidationErrorFor('file_name')
        ->assertJsonValidationErrorFor('path')
        ->assertJsonValidationErrorFor('channels')
        ->assertUnprocessable();
});

it('should store the newly created sitemap', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.sitemaps.store'), [
        'file_name' => $fileName = strtolower(fake()->word()).'.xml',
        'path' => $filePath = '/',
        'channels' => [core()->getDefaultChannel()->id],
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.create.success'));

    $this->assertModelWise([
        Sitemap::class => [
            [
                'file_name' => $fileName,
                'path' => $filePath,
            ],
        ],
    ]);
});

it('should fail the validation with errors when certain field not provided when update in the sitemap', function () {
    // Arrange.
    $sitemap = Sitemap::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.sitemaps.update', $sitemap->id))
        ->assertJsonValidationErrorFor('file_name')
        ->assertJsonValidationErrorFor('path')
        ->assertJsonValidationErrorFor('channels')
        ->assertUnprocessable();
});

it('should update the sitemap', function () {
    // Arrange.
    $sitemap = Sitemap::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.sitemaps.update'), [
        'id' => $sitemap->id,
        'file_name' => $fileName = strtolower(fake()->word()).'.xml',
        'path' => $sitemap->path,
        'channels' => [core()->getDefaultChannel()->id],
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.edit.success'));

    $this->assertModelWise([
        Sitemap::class => [
            [
                'id' => $sitemap->id,
                'file_name' => $fileName,
                'path' => $sitemap->path,
            ],
        ],
    ]);
});

it('should delete the sitemap', function () {
    // Arrange.
    $sitemap = Sitemap::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.search_seo.sitemaps.delete', $sitemap->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.edit.delete-success'));

    $this->assertDatabaseMissing('sitemaps', [
        'id' => $sitemap->id,
    ]);
});

it('should write a generated sitemap the web server can read', function () {
    // Arrange.
    Storage::fake('public');

    CoreConfig::create([
        'code' => 'general.sitemap.settings.enabled',
        'value' => 1,
    ]);

    CacheGeneration::bump(CoreConfigRepository::class);

    $sitemap = Sitemap::factory()->create();

    $sitemap->channels()->sync([core()->getDefaultChannel()->id]);

    // Act.
    (new ProcessSitemap($sitemap->refresh()))->handle();

    // Assert.
    $generated = Storage::disk('public')->allFiles();

    expect($generated)->not->toBeEmpty();

    foreach ($generated as $path) {
        expect(Storage::disk('public')->getVisibility($path))->toBe('public');
    }
});
