<?php

use Webkul\Core\Models\Channel;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should returns the channel index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.channels.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.channels.index.title'))
        ->assertSeeText(trans('admin::app.settings.channels.index.create-btn'));
});

it('should return the create page of channel', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.channels.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.channels.create.title'))
        ->assertSeeText(trans('admin::app.settings.channels.create.save-btn'));
});

it('should fail the validation with errors when certain field not provided when store the channels', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.channels.store'))
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
        ->assertJsonValidationErrorFor('seo_keywords')
        ->assertUnprocessable();
});

it('should store the newly created channels', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.channels.store'), [
        'code'              => $code = fake()->regexify('/^[a-zA-Z]+[a-zA-Z0-9_]+$/'),
        'theme'             => $code,
        'hostname'          => $hostName = 'http://'.fake()->ipv4(),
        'root_category_id'  => 1,
        'default_locale_id' => 1,
        'base_currency_id'  => 1,
        'name'              => fake()->name(),
        'description'       => substr(fake()->paragraph, 0, 50),
        'inventory_sources' => [1],
        'locales'           => [1],
        'currencies'        => [1],
        'seo_title'         => fake()->title(),
        'seo_description'   => substr(fake()->paragraph(), 0, 50),
        'seo_keywords'      => fake()->name(),
        'is_maintenance_on' => fake()->boolean(),
    ])
        ->assertRedirect(route('admin.settings.channels.index'))
        ->isRedirection();

    $this->assertModelWise([
        Channel::class => [
            [
                'code'     => $code,
                'theme'    => $code,
                'hostname' => $hostName,
            ],
        ],
    ]);
});

it('should returns the edit page of channels', function () {
    // Arrange
    $channel = Channel::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.channels.edit', $channel->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.channels.edit.title'))
        ->assertSeeText(trans('admin::app.settings.channels.edit.save-btn'));
});

it('should fail the validation with errors when certain field not provided when update the channels', function () {
    // Arrange
    $channel = Channel::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.settings.channels.update', $channel->id))
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('en.name')
        ->assertJsonValidationErrorFor('inventory_sources')
        ->assertJsonValidationErrorFor('root_category_id')
        ->assertJsonValidationErrorFor('locales')
        ->assertJsonValidationErrorFor('default_locale_id')
        ->assertJsonValidationErrorFor('currencies')
        ->assertJsonValidationErrorFor('base_currency_id')
        ->assertJsonValidationErrorFor('en.seo_title')
        ->assertJsonValidationErrorFor('en.seo_description')
        ->assertJsonValidationErrorFor('en.seo_keywords')
        ->assertUnprocessable();
});

it('should update the existing channel', function () {
    // Arrange
    $channel = Channel::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.settings.channels.update', $channel->id), [
        'code'              => $code = strtolower(fake()->regexify('/^[a-zA-Z]+[a-zA-Z0-9_]+$/')),

        app()->getLocale()  => [
            'name'            => fake()->name(),
            'seo_title'       => fake()->title(),
            'seo_description' => substr(fake()->paragraph(), 0, 50),
            'seo_keywords'    => fake()->name(),
            'description'     => substr(fake()->paragraph, 0, 50),
        ],

        'hostname'          => 'http://'.fake()->ipv4(),
        'root_category_id'  => 1,
        'default_locale_id' => 1,
        'base_currency_id'  => 1,
        'inventory_sources' => [1],
        'locales'           => [1],
        'currencies'        => [1],
        'is_maintenance_on' => fake()->boolean(),
    ])
        ->assertRedirect(route('admin.settings.channels.index'))
        ->isRedirection();

    $this->assertModelWise([
        Channel::class => [
            [
                'code'              => $code,
                'base_currency_id'  => 1,
                'root_category_id'  => 1,
                'default_locale_id' => 1,
            ],
        ],
    ]);
});

it('should delete the existing channel', function () {
    // Arrange
    $channel = Channel::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.settings.channels.delete', $channel->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.channels.index.delete-success'));
});
