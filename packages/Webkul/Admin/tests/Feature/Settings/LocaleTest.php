<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
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

// ============================================================================
// Events
// ============================================================================

it('should announce a locale creation exactly once from the controller', function () {
    Event::fake();

    $this->loginAsAdmin();

    postJson(route('admin.settings.locales.store'), $data = [
        'code' => 'nb_NO',
        'name' => fake()->name(),
        'direction' => 'ltr',
    ])->assertOk();

    Event::assertDispatchedTimes('core.locale.create.before', 1);

    Event::assertDispatchedTimes('core.locale.create.after', 1);

    Event::assertDispatched('core.locale.create.after', fn ($event, $payload) => $payload instanceof Locale
        && $payload->code === $data['code']);
});

it('should announce a locale update exactly once, carrying the id then the model', function () {
    $locale = Locale::factory()->create();

    Event::fake();

    $this->loginAsAdmin();

    putJson(route('admin.settings.locales.update'), [
        'id' => $locale->id,
        'code' => $locale->code,
        'name' => $name = fake()->name(),
        'direction' => 'ltr',
    ])->assertOk();

    Event::assertDispatchedTimes('core.locale.update.before', 1);

    Event::assertDispatchedTimes('core.locale.update.after', 1);

    Event::assertDispatched('core.locale.update.before', fn ($event, $payload) => $payload == $locale->id);

    Event::assertDispatched('core.locale.update.after', fn ($event, $payload) => $payload instanceof Locale
        && $payload->name === $name);
});

it('should carry the stored logo on the locale creation event', function () {
    Event::fake();

    $this->loginAsAdmin();

    postJson(route('admin.settings.locales.store'), [
        'code' => 'sv_SE',
        'name' => fake()->name(),
        'direction' => 'ltr',
        'logo_path' => [
            UploadedFile::fake()->image('logo.png'),
        ],
    ])->assertOk();

    Event::assertDispatched('core.locale.create.after', fn ($event, $payload) => $payload->logo_path === 'locales/sv_SE.png');
});

it('should announce a locale deletion exactly once from the repository', function () {
    Locale::factory()->create();

    $locale = Locale::factory()->create();

    Event::fake();

    $this->loginAsAdmin();

    deleteJson(route('admin.settings.locales.delete', $locale->id))->assertOk();

    Event::assertDispatchedTimes('core.locale.delete.before', 1);

    Event::assertDispatchedTimes('core.locale.delete.after', 1);

    Event::assertDispatched('core.locale.delete.after', fn ($event, $payload) => $payload == $locale->id);
});

it('should remove the stored logo when a locale is deleted', function () {
    Locale::factory()->create();

    $this->loginAsAdmin();

    postJson(route('admin.settings.locales.store'), [
        'code' => 'da_DK',
        'name' => fake()->name(),
        'direction' => 'ltr',
        'logo_path' => [
            UploadedFile::fake()->image('logo.png'),
        ],
    ])->assertOk();

    Storage::assertExists('locales/da_DK.png');

    deleteJson(route('admin.settings.locales.delete', Locale::where('code', 'da_DK')->first()->id))->assertOk();

    Storage::assertMissing('locales/da_DK.png');
});
