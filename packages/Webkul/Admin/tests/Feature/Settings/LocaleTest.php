<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Helpers\MediaUpload;
use Webkul\Core\Models\Locale;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

beforeEach(fn () => Storage::fake());

/**
 * The message a locale logo of a type that is not allowed is refused with, listing the allowed extensions.
 */
function localeLogoExtensionsMessage(): string
{
    return trans('validation.extensions', [
        'attribute' => trans('admin::app.settings.locales.index.create.locale-logo'),
        'values' => implode(', ', [...app(MediaUpload::class)->extensions(MediaUpload::IMAGE), 'svg']),
    ]);
}

/**
 * A locale code in the `xx_YY` form that no locale holds yet.
 */
function uniqueLocaleCode(): string
{
    do {
        $code = fake()->lexify('??').'_'.strtoupper(fake()->lexify('??'));
    } while (Locale::query()->where('code', $code)->exists());

    return $code;
}

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
        'code' => uniqueLocaleCode(),
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

it('should store an svg locale logo with its script stripped', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.locales.store'), $data = [
        'code' => uniqueLocaleCode(),
        'name' => fake()->name(),
        'direction' => 'ltr',
        'logo_path' => [
            UploadedFile::fake()->createWithContent(
                'logo.svg',
                '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"><script>alert(1)</script><rect width="10" height="10"/></svg>'
            ),
        ],
    ])
        ->assertOk();

    $path = 'locales/'.$data['code'].'.svg';

    Storage::assertExists($path);

    expect(Storage::get($path))
        ->toContain('<rect')
        ->not->toContain('<script');
});

it('should fail validation when the code belongs to another locale on store', function () {
    $locale = Locale::factory()->create();

    $this->loginAsAdmin();

    postJson(route('admin.settings.locales.store'), [
        'code' => $locale->code,
        'name' => fake()->name(),
        'direction' => 'ltr',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code');
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
        ->assertJsonValidationErrors(['logo_path.0' => trans('validation.file', ['attribute' => trans('admin::app.settings.locales.index.create.locale-logo')])]);
});

it('should reject a tampered file upload on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.locales.store'), [
        'code' => uniqueLocaleCode(),
        'name' => fake()->name(),
        'direction' => 'ltr',
        'logo_path' => [
            UploadedFile::fake()->image('tampered.php'),
        ],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['logo_path.0' => localeLogoExtensionsMessage()]);
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

it('should keep the current svg logo when its replacement cannot be read', function () {
    $locale = Locale::factory()->create();

    $locale->forceFill(['logo_path' => 'locales/'.$locale->code.'.svg'])->save();

    Storage::put($locale->logo_path, '<svg><rect/></svg>');

    $this->loginAsAdmin();

    putJson(route('admin.settings.locales.update'), [
        'id' => $locale->id,
        'name' => $locale->name,
        'direction' => 'ltr',
        'logo_path' => [
            UploadedFile::fake()->createWithContent('logo.svg', '<svg><rect></svg>'),
        ],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['logo_path' => trans('core::app.errors.invalid-svg')]);

    expect(Storage::get($locale->logo_path))->toBe('<svg><rect/></svg>')
        ->and(Storage::files('locales'))->toBe([$locale->logo_path]);

    $this->assertDatabaseHas('locales', [
        'id' => $locale->id,
        'logo_path' => $locale->logo_path,
    ]);
});

it('should remove the previous logo file when it is replaced with another type', function () {
    $locale = Locale::factory()->create();

    $locale->forceFill(['logo_path' => 'locales/'.$locale->code.'.png'])->save();

    Storage::put($locale->logo_path, 'png');

    $this->loginAsAdmin();

    putJson(route('admin.settings.locales.update'), [
        'id' => $locale->id,
        'name' => $locale->name,
        'direction' => 'ltr',
        'logo_path' => [
            UploadedFile::fake()->createWithContent('logo.svg', '<svg><rect/></svg>'),
        ],
    ])
        ->assertOk();

    Storage::assertMissing('locales/'.$locale->code.'.png');

    Storage::assertExists('locales/'.$locale->code.'.svg');
});

it('should remove the logo file when the logo is cleared', function () {
    $locale = Locale::factory()->create();

    $locale->forceFill(['logo_path' => 'locales/'.$locale->code.'.png'])->save();

    Storage::put($locale->logo_path, 'png');

    $this->loginAsAdmin();

    putJson(route('admin.settings.locales.update'), [
        'id' => $locale->id,
        'name' => $locale->name,
        'direction' => 'ltr',
    ])
        ->assertOk();

    Storage::assertMissing('locales/'.$locale->code.'.png');

    $this->assertDatabaseHas('locales', [
        'id' => $locale->id,
        'logo_path' => null,
    ]);
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
        ->assertJsonValidationErrors(['logo_path.0' => localeLogoExtensionsMessage()]);
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
        'code' => uniqueLocaleCode(),
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

    $code = uniqueLocaleCode();

    postJson(route('admin.settings.locales.store'), [
        'code' => $code,
        'name' => fake()->name(),
        'direction' => 'ltr',
        'logo_path' => [
            UploadedFile::fake()->image('logo.png'),
        ],
    ])->assertOk();

    Event::assertDispatched('core.locale.create.after', fn ($event, $payload) => $payload->logo_path === 'locales/'.$code.'.png');
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

    $code = uniqueLocaleCode();

    postJson(route('admin.settings.locales.store'), [
        'code' => $code,
        'name' => fake()->name(),
        'direction' => 'ltr',
        'logo_path' => [
            UploadedFile::fake()->image('logo.png'),
        ],
    ])->assertOk();

    Storage::assertExists('locales/'.$code.'.png');

    deleteJson(route('admin.settings.locales.delete', Locale::query()->where('code', $code)->first()->id))->assertOk();

    Storage::assertMissing('locales/'.$code.'.png');
});
