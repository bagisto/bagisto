<?php

use Webkul\Core\Models\Locale;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should returns the locale index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.locales.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.locales.index.title'))
        ->assertSeeText(trans('admin::app.settings.locales.index.create-btn'));
});

it('should store the newly created locale', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.locales.store'), [
        'code' => $code = fake()->locale(),
        'name' => $name = fake()->name(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.locales.index.create-success'));

    $this->assertModelWise([
        Locale::class => [
            [
                'code' => $code,
                'name' => $name,
            ],
        ],
    ]);
});

it('should return the locale for edit', function () {
    // Arrange
    $locale = Locale::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.locales.edit', $locale->id))
        ->assertOk()
        ->assertJsonPath('data.id', $locale->id)
        ->assertJsonPath('data.code', $locale->code)
        ->assertJsonPath('data.name', $locale->name);
});

it('should update the specified locale', function () {
    // Arrange
    $locale = Locale::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.settings.locales.update'), [
        'id'   => $locale->id,
        'name' => $name = fake()->name(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.locales.index.update-success'));

    $this->assertModelWise([
        Locale::class => [
            [
                'code' => $locale->code,
                'name' => $name,
            ],
        ],
    ]);
});

it('should delete the locale', function () {
    // Arrange
    $locale = Locale::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.settings.locales.delete', $locale->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.locales.index.delete-success'));

    $this->assertDatabaseMissing('locales', [
        'id' => $locale->id,
    ]);
});
