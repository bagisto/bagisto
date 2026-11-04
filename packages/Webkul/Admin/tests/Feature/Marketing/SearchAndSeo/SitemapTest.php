<?php

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\ChannelTranslation;
use Webkul\Sitemap\Jobs\ProcessSitemap;
use Webkul\Sitemap\Models\Sitemap;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the sitemap index page', function () {
    $this->loginAsAdmin();

    get(route('admin.marketing.search_seo.sitemaps.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.title'))
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.create-btn'));
});

it('should list the sitemap channels by their names in the current locale', function () {
    $channel = Channel::factory()->create();

    $channel->translations()->update(['name' => $name = 'Zeta '.fake()->unique()->lexify('??????')]);

    ChannelTranslation::factory()->create([
        'channel_id' => $channel->id,
        'locale' => 'fr',
        'name' => 'Canal '.fake()->unique()->lexify('??????'),
    ]);

    $sitemap = Sitemap::factory()->create();

    $sitemap->channels()->sync([core()->getDefaultChannel()->id, $channel->id]);

    $this->loginAsAdmin();

    getJson(route('admin.marketing.search_seo.sitemaps.index', [
        'filters' => ['id' => [$sitemap->id]],
    ]), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertOk()
        ->assertJsonCount(1, 'records')
        ->assertJsonPath('records.0.channel', core()->getDefaultChannel()->name.', '.$name);
});

it('should deny guest access to the sitemap index page', function () {
    get(route('admin.marketing.search_seo.sitemaps.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created sitemap', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.sitemaps.store'), [
        'file_name' => $fileName = strtolower(fake()->word()).'.xml',
        'path' => '/',
        'channels' => [core()->getDefaultChannel()->id],
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.create.success'));

    $this->assertDatabaseHas('sitemaps', [
        'file_name' => $fileName,
        'path' => '/',
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.sitemaps.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('file_name')
        ->assertJsonValidationErrorFor('path')
        ->assertJsonValidationErrorFor('channels');
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing sitemap', function () {
    $sitemap = Sitemap::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.sitemaps.update'), [
        'id' => $sitemap->id,
        'file_name' => $fileName = strtolower(fake()->word()).'.xml',
        'path' => $sitemap->path,
        'channels' => [core()->getDefaultChannel()->id],
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.edit.success'));

    $this->assertDatabaseHas('sitemaps', [
        'id' => $sitemap->id,
        'file_name' => $fileName,
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $sitemap = Sitemap::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.sitemaps.update', $sitemap->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('file_name')
        ->assertJsonValidationErrorFor('path');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a sitemap', function () {
    $sitemap = Sitemap::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.search_seo.sitemaps.delete', $sitemap->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.edit.delete-success'));

    $this->assertDatabaseMissing('sitemaps', ['id' => $sitemap->id]);
});

// ============================================================================
// Generated Files
// ============================================================================

it('should write a generated sitemap the web server can read', function () {
    Storage::fake('public');

    $this->setConfig('general.sitemap.settings.enabled', 1);

    $sitemap = Sitemap::factory()->create();

    $sitemap->channels()->sync([core()->getDefaultChannel()->id]);

    $sitemap->load('channels');

    (new ProcessSitemap($sitemap))->handle();

    $generated = Storage::disk('public')->allFiles();

    expect($generated)->not->toBeEmpty();

    foreach ($generated as $path) {
        expect(Storage::disk('public')->getVisibility($path))->toBe('public');
    }
});
