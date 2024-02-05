<?php

use Illuminate\Http\UploadedFile;
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

it('should fail the validation with errors when certain field not provided when store the locale', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.locales.store'))
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('direction')
        ->assertUnprocessable();
});

it('should store the newly created locale', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.locales.store'), [
        'code'      => $code = fake()->locale(),
        'name'      => $name = fake()->name(),
        'direction' => fake()->randomElement(['ltr', 'rtl']),
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

it('should not store the new locale if the file has been tampered with', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.locales.store'), [
        'code'      => fake()->locale(),
        'name'      => fake()->name(),
        'logo_path' => [
            UploadedFile::fake()->image('tampered.php'),
        ],
    ])
        ->assertUnprocessable();
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

it('should fail the validation with errors when certain field not provided when update the locale', function () {
    // Arrange
    $locale = Locale::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.settings.locales.update'), [
        'id'        => $locale->id,
        'logo_path' => UploadedFile::fake()->image(fake()->word().'.png'),
    ])
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('direction')
        ->assertUnprocessable();
});

it('should update the specified locale', function () {
    // Arrange
    $locale = Locale::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.settings.locales.update'), [
        'id'        => $locale->id,
        'name'      => $name = fake()->name(),
        'direction' => fake()->randomElement(['ltr', 'rtl']),
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

it('should not update the specified locale if the file has been tampered with', function () {
    // Arrange
    $locale = Locale::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.settings.locales.update'), [
        'id'        => $locale->id,
        'name'      => fake()->name(),
        'logo_path' => [
            UploadedFile::fake()->image('tampered.php'),
        ],
    ])
        ->assertUnprocessable();
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
