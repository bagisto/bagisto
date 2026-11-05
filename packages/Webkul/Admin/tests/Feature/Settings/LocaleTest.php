<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Models\Locale;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

beforeEach(fn () => Storage::fake());

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

it('should store a locale with an SVG logo', function () {
    $this->loginAsAdmin();

    $code = uniqueLocaleCode();

    postJson(route('admin.settings.locales.store'), [
        'code' => $code,
        'name' => fake()->name(),
        'direction' => 'ltr',
        'logo_path' => [
            UploadedFile::fake()->createWithContent('logo.svg', '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"><rect width="10" height="10"/></svg>'),
        ],
    ])->assertOk();

    $this->assertDatabaseHas('locales', [
        'code' => $code,
        'logo_path' => 'locales/'.$code.'.svg',
    ]);

    Storage::assertExists('locales/'.$code.'.svg');
});

it('should strip a script out of an SVG logo', function () {
    $this->loginAsAdmin();

    $code = uniqueLocaleCode();

    postJson(route('admin.settings.locales.store'), [
        'code' => $code,
        'name' => fake()->name(),
        'direction' => 'ltr',
        'logo_path' => [
            UploadedFile::fake()->createWithContent('logo.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script><rect/></svg>'),
        ],
    ])->assertOk();

    expect(Storage::get('locales/'.$code.'.svg'))
        ->toContain('<rect')
        ->not->toContain('script');
});

it('should refuse an SVG logo that cannot be read and store nothing', function () {
    $this->loginAsAdmin();

    $code = uniqueLocaleCode();

    postJson(route('admin.settings.locales.store'), [
        'code' => $code,
        'name' => fake()->name(),
        'direction' => 'ltr',
        'logo_path' => [
            UploadedFile::fake()->createWithContent('logo.svg', '<svg><rect></svg>'),
        ],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['logo_path' => trans('core::app.errors.invalid-svg')]);

    $this->assertDatabaseMissing('locales', ['code' => $code]);

    expect(Storage::allFiles('locales'))->toBeEmpty();
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
        ->assertJsonValidationErrors(['logo_path.0' => 'The Locale Logo field']);
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
        ->assertJsonValidationErrors(['logo_path.0' => 'The Locale Logo field']);
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

it('should replace the SVG logo of a locale that already holds one', function () {
    $locale = Locale::factory()->create();

    $locale->forceFill(['logo_path' => 'locales/'.$locale->code.'.svg'])->save();

    Storage::put($locale->logo_path, '<svg xmlns="http://www.w3.org/2000/svg"><circle/></svg>');

    $this->loginAsAdmin();

    putJson(route('admin.settings.locales.update'), [
        'id' => $locale->id,
        'code' => $locale->code,
        'name' => fake()->name(),
        'direction' => 'ltr',
        'logo_path' => [
            UploadedFile::fake()->createWithContent('logo.svg', '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"><rect width="10" height="10"/></svg>'),
        ],
    ])->assertOk();

    expect(Storage::get($locale->logo_path))
        ->toContain('<rect')
        ->not->toContain('<circle')
        ->and(Storage::allFiles('locales'))->toBe([$locale->logo_path]);
});

it('should keep the current logo when its SVG replacement cannot be read', function () {
    $locale = Locale::factory()->create();

    $locale->forceFill(['logo_path' => 'locales/'.$locale->code.'.svg'])->save();

    Storage::put($locale->logo_path, $current = '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"><rect width="10" height="10"/></svg>');

    $this->loginAsAdmin();

    putJson(route('admin.settings.locales.update'), [
        'id' => $locale->id,
        'code' => $locale->code,
        'name' => fake()->name(),
        'direction' => 'ltr',
        'logo_path' => [
            UploadedFile::fake()->createWithContent('logo.svg', '<svg><rect></svg>'),
        ],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['logo_path' => trans('core::app.errors.invalid-svg')]);

    expect(Storage::get($locale->logo_path))->toBe($current)
        ->and(Storage::allFiles('locales'))->toBe([$locale->logo_path])
        ->and($locale->fresh()->logo_path)->toBe('locales/'.$locale->code.'.svg');
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
        ->assertJsonValidationErrors(['logo_path.0' => 'The Locale Logo field']);
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

it('should announce a locale update exactly once, carrying the ID then the model', function () {
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
