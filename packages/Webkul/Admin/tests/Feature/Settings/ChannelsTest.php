<?php

use Illuminate\Http\UploadedFile;
use Webkul\Core\Models\Channel;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the channel index page', function () {
    $this->loginAsAdmin();

    get(route('admin.settings.channels.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.channels.index.title'))
        ->assertSeeText(trans('admin::app.settings.channels.index.create-btn'));
});

it('should deny guest access to the channel index page', function () {
    get(route('admin.settings.channels.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Create
// ============================================================================

it('should return the channel create page', function () {
    $this->loginAsAdmin();

    get(route('admin.settings.channels.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.channels.create.title'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created channel', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.channels.store'), $data = [
        'code' => $code = fake()->numerify('code######'),
        'theme' => $code,
        'hostname' => 'http://'.fake()->ipv4(),
        'root_category_id' => 1,
        'default_locale_id' => 1,
        'base_currency_id' => 1,
        'name' => fake()->name(),
        'description' => fake()->sentence(),
        'inventory_sources' => [1],
        'locales' => [1],
        'currencies' => [1],
        'seo_title' => fake()->sentence(),
        'seo_description' => fake()->sentence(),
        'seo_keywords' => fake()->words(3, true),
        'is_maintenance_on' => false,
        'logo' => [UploadedFile::fake()->image('logo.png')],
        'favicon' => [UploadedFile::fake()->image('favicon.png')],
    ])
        ->assertRedirect(route('admin.settings.channels.index'));

    $this->assertDatabaseHas('channels', [
        'code' => $data['code'],
        'hostname' => $data['hostname'],
        'root_category_id' => 1,
        'default_locale_id' => 1,
        'base_currency_id' => 1,
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.channels.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('inventory_sources')
        ->assertJsonValidationErrorFor('root_category_id')
        ->assertJsonValidationErrorFor('locales')
        ->assertJsonValidationErrorFor('default_locale_id')
        ->assertJsonValidationErrorFor('currencies')
        ->assertJsonValidationErrorFor('base_currency_id')
        ->assertJsonValidationErrorFor('seo_title')
        ->assertJsonValidationErrorFor('seo_description')
        ->assertJsonValidationErrorFor('seo_keywords');
});

// ============================================================================
// Edit
// ============================================================================

it('should return the channel edit page', function () {
    $channel = Channel::factory()->create();

    $this->loginAsAdmin();

    get(route('admin.settings.channels.edit', $channel->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.channels.edit.title'));
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing channel', function () {
    $channel = Channel::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.channels.update', $channel->id), $data = [
        'code' => $channel->code,

        app()->getLocale() => [
            'name' => fake()->name(),
            'seo_title' => fake()->sentence(),
            'seo_description' => fake()->sentence(),
            'seo_keywords' => fake()->words(3, true),
            'description' => fake()->sentence(),
        ],

        'hostname' => 'http://'.fake()->ipv4(),
        'root_category_id' => 1,
        'default_locale_id' => 1,
        'base_currency_id' => 1,
        'inventory_sources' => [1],
        'locales' => [1],
        'currencies' => [1],
        'is_maintenance_on' => false,
    ])
        ->assertRedirect(route('admin.settings.channels.index'));

    $this->assertDatabaseHas('channels', [
        'id' => $channel->id,
        'code' => $data['code'],
        'hostname' => $data['hostname'],
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $channel = Channel::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.channels.update', $channel->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('en.name')
        ->assertJsonValidationErrorFor('en.seo_title')
        ->assertJsonValidationErrorFor('en.seo_description')
        ->assertJsonValidationErrorFor('en.seo_keywords')
        ->assertJsonValidationErrorFor('inventory_sources')
        ->assertJsonValidationErrorFor('root_category_id')
        ->assertJsonValidationErrorFor('locales')
        ->assertJsonValidationErrorFor('default_locale_id')
        ->assertJsonValidationErrorFor('currencies')
        ->assertJsonValidationErrorFor('base_currency_id');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a channel', function () {
    $channel = Channel::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.settings.channels.delete', $channel->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.channels.index.delete-success'));

    $this->assertDatabaseMissing('channels', ['id' => $channel->id]);
});
