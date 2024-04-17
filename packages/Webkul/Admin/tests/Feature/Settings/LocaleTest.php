<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Models\Locale;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should returns the locale index page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.settings.locales.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.locales.index.title'))
        ->assertSeeText(trans('admin::app.settings.locales.index.create-btn'));
});

it('should fail the validation with errors when certain field not provided when store the locale', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.settings.locales.store'), [
        'logo_path' => [
            'INVALID_FORMAT_OF_LOGO_PATH',
        ],
    ])
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('direction')
        ->assertJsonValidationErrorFor('logo_path.0')
        ->assertUnprocessable();
});

it('should store the newly created locale', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.settings.locales.store'), $data = [
        'code'      => fake()->locale(),
        'name'      => fake()->name(),
        'direction' => fake()->randomElement(['ltr', 'rtl']),
        'logo_path' => [
            UploadedFile::fake()->image('logo.png'),
        ],
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.locales.index.create-success'));

    $this->assertModelWise([
        Locale::class => [
            [
                'code'      => $data['code'],
                'name'      => $data['name'],
                'direction' => $data['direction'],
                'logo_path' => 'locales/'.$data['code'].'.png',
            ],
        ],
    ]);

    Storage::assertExists('locales/'.$data['code'].'.png');
});

it('should not store the new locale if the file has been tampered with', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.settings.locales.store'), $data = [
        'code'      => fake()->locale(),
        'name'      => fake()->name(),
        'direction' => fake()->randomElement(['ltr', 'rtl']),
        'logo_path' => [
            UploadedFile::fake()->image('tampered.php'),
        ],
    ])
        ->assertJsonValidationErrorFor('logo_path.0')
        ->assertUnprocessable();

    Storage::assertMissing('locales/'.$data['code'].'.php');
});

it('should return the locale for edit', function () {
    // Arrange.
    $locale = Locale::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.settings.locales.edit', $locale->id))
        ->assertOk()
        ->assertJsonFragment($locale->toArray());
});

it('should fail the validation with errors when certain field not provided when update the locale', function () {
    // Arrange.
    $locale = Locale::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.settings.locales.update'), [
        'id'        => $locale->id,
        'logo_path' => [
            UploadedFile::fake()->image(fake()->word().'.png'),
        ],
    ])
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('direction')
        ->assertUnprocessable();

    Storage::assertMissing('locales/'.$locale->code.'.png');
});

it('should update the specified locale', function () {
    // Arrange.
    $locale = Locale::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.settings.locales.update'), $data = [
        'id'        => $locale->id,
        'code'      => $locale->code,
        'name'      => fake()->name(),
        'direction' => fake()->randomElement(['ltr', 'rtl']),
        'logo_path' => [
            UploadedFile::fake()->image('logo.png'),
        ],
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.locales.index.update-success'));

    $this->assertModelWise([
        Locale::class => [
            [
                'name'      => $data['name'],
                'code'      => $data['code'],
                'direction' => $data['direction'],
                'logo_path' => 'locales/'.$locale->code.'.png',
            ],
        ],
    ]);

    Storage::assertExists('locales/'.$locale->code.'.png');
});

it('should not update the specified locale if the file has been tampered with', function () {
    // Arrange.
    $locale = Locale::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.settings.locales.update'), [
        'id'        => $locale->id,
        'name'      => fake()->name(),
        'logo_path' => [
            UploadedFile::fake()->image('tampered.php'),
        ],
    ])
        ->assertJsonValidationErrorFor('direction')
        ->assertJsonValidationErrorFor('logo_path.0')
        ->assertUnprocessable();

    Storage::assertMissing('locales/'.$locale->code.'.php');
});

it('should delete the locale', function () {
    // Arrange.
    $locale = Locale::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.settings.locales.delete', $locale->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.locales.index.delete-success'));

    $this->assertDatabaseMissing('locales', [
        'id' => $locale->id,
    ]);
});
