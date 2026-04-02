<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Models\Locale;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the locale index page', function () {
    $this->loginAsAdmin();

    get(route('admin.settings.locales.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.locales.index.title'))
        ->assertSeeText(trans('admin::app.settings.locales.index.create-btn'));
});

it('should deny guest access to the locale index page', function () {
    get(route('admin.settings.locales.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created locale', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.locales.store'), $data = [
        'code' => fake()->locale(),
        'name' => fake()->name(),
        'direction' => fake()->randomElement(['ltr', 'rtl']),
        'logo_path' => [
            UploadedFile::fake()->image('logo.png'),
        ],
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.locales.index.create-success'));

    $this->assertDatabaseHas('locales', [
        'code' => $data['code'],
        'name' => $data['name'],
        'direction' => $data['direction'],
    ]);

    Storage::assertExists('locales/'.$data['code'].'.png');
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.locales.store'), [
        'logo_path' => ['INVALID_FORMAT_OF_LOGO_PATH'],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('direction')
        ->assertJsonValidationErrorFor('logo_path.0');
});

it('should reject a tampered file upload on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.locales.store'), [
        'code' => fake()->locale(),
        'name' => fake()->name(),
        'direction' => 'ltr',
        'logo_path' => [
            UploadedFile::fake()->image('tampered.php'),
        ],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('logo_path.0');
});

// ============================================================================
// Edit
// ============================================================================

it('should return locale details for edit', function () {
    $locale = Locale::factory()->create();

    $this->loginAsAdmin();

    get(route('admin.settings.locales.edit', $locale->id))
        ->assertOk()
        ->assertJsonFragment($locale->toArray());
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing locale', function () {
    $locale = Locale::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.locales.update'), $data = [
        'id' => $locale->id,
        'code' => $locale->code,
        'name' => fake()->name(),
        'direction' => fake()->randomElement(['ltr', 'rtl']),
        'logo_path' => [
            UploadedFile::fake()->image('logo.png'),
        ],
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.locales.index.update-success'));

    $this->assertDatabaseHas('locales', [
        'id' => $locale->id,
        'name' => $data['name'],
        'direction' => $data['direction'],
    ]);

    Storage::assertExists('locales/'.$locale->code.'.png');
});

it('should fail validation when required fields are missing on update', function () {
    $locale = Locale::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.locales.update'), [
        'id' => $locale->id,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('direction');
});

it('should reject a tampered file upload on update', function () {
    $locale = Locale::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.locales.update'), [
        'id' => $locale->id,
        'name' => fake()->name(),
        'direction' => 'ltr',
        'logo_path' => [
            UploadedFile::fake()->image('tampered.php'),
        ],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('logo_path.0');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a locale', function () {
    $locale = Locale::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.settings.locales.delete', $locale->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.locales.index.delete-success'));

    $this->assertDatabaseMissing('locales', ['id' => $locale->id]);
});
