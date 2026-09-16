<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Attribute\Repositories\AttributeOptionRepository;

use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

/**
 * Create a select attribute whose options carry swatches of the given type.
 */
function makeSwatchAttribute(string $swatchType): Attribute
{
    return Attribute::factory()->create([
        'type' => 'select',
        'swatch_type' => $swatchType,
    ]);
}

/**
 * Create an image swatch attribute with one option that points at a swatch file.
 */
function makeImageSwatchOption(): AttributeOption
{
    $option = AttributeOption::query()->create([
        'attribute_id' => makeSwatchAttribute('image')->id,
        'admin_name' => 'Blue',
        'sort_order' => 1,
        'swatch_value' => AttributeOptionRepository::SWATCH_DIRECTORY.'/kw82ndlq.png',
    ]);

    Storage::put($option->swatch_value, 'swatch-contents');

    return $option;
}

/**
 * The payload of an attribute form whose single option carries the given swatch value.
 */
function swatchAttributePayload(string $code, string $swatchType, string|int $optionKey, mixed $swatchValue): array
{
    return [
        'admin_name' => 'Colour',
        'code' => $code,
        'type' => 'select',
        'swatch_type' => $swatchType,
        'options' => [
            $optionKey => [
                'admin_name' => 'Blue',
                'sort_order' => 1,
                'swatch_value' => $swatchValue,
            ],
        ],
    ];
}

// ============================================================================
// Alt Text
// ============================================================================

it('should save the alt text of a swatch image', function () {
    Storage::fake();

    $option = makeImageSwatchOption();

    app(AttributeOptionRepository::class)->update([
        'swatch_alt' => 'Blue fabric swatch',
    ], $option->id);

    expect($option->fresh()->swatch_alt)->toBe('Blue fabric swatch');
});

it('should keep the swatch alt text of each locale apart', function () {
    Storage::fake();

    $option = makeImageSwatchOption();

    $repository = app(AttributeOptionRepository::class);

    app()->setLocale('en');

    $repository->update(['swatch_alt' => 'Blue fabric swatch'], $option->id);

    app()->setLocale('fr');

    $repository->update(['swatch_alt' => 'Échantillon de tissu bleu'], $option->id);

    app()->setLocale('en');

    $option = $option->fresh();

    expect($option->translate('en')->swatch_alt)->toBe('Blue fabric swatch')
        ->and($option->translate('fr')->swatch_alt)->toBe('Échantillon de tissu bleu');
});

// ============================================================================
// Renaming
// ============================================================================

it('should rename a swatch image while keeping its extension', function () {
    Storage::fake();

    $option = makeImageSwatchOption();

    $originalPath = $option->swatch_value;

    app(AttributeOptionRepository::class)->update([
        'swatch_file_name' => 'Blue Fabric Swatch',
    ], $option->id);

    $expected = AttributeOptionRepository::SWATCH_DIRECTORY.'/blue-fabric-swatch.png';

    expect($option->fresh()->swatch_value)->toBe($expected);

    Storage::assertExists($expected);

    Storage::assertMissing($originalPath);
});

it('should never rename a colour swatch, which holds a value rather than a path', function () {
    Storage::fake();

    $option = AttributeOption::query()->create([
        'attribute_id' => makeSwatchAttribute('color')->id,
        'admin_name' => 'Blue',
        'sort_order' => 1,
        'swatch_value' => '#0000ff',
    ]);

    app(AttributeOptionRepository::class)->update([
        'swatch_file_name' => 'Blue Fabric Swatch',
    ], $option->id);

    expect($option->fresh()->swatch_value)->toBe('#0000ff');
});

// ============================================================================
// Uploads
// ============================================================================

it('should name a newly uploaded swatch after the requested file name', function () {
    Storage::fake();

    $option = app(AttributeOptionRepository::class)->create([
        'attribute_id' => makeSwatchAttribute('image')->id,
        'admin_name' => 'Blue',
        'sort_order' => 1,
        'swatch_value' => UploadedFile::fake()->image('DSC_0004.png', 20, 20),
        'swatch_file_name' => 'Blue Fabric Swatch',
        'swatch_alt' => 'Blue fabric swatch',
    ])->fresh();

    expect($option->swatch_value)->toBe(AttributeOptionRepository::SWATCH_DIRECTORY.'/blue-fabric-swatch.webp')
        ->and($option->swatch_alt)->toBe('Blue fabric swatch');

    Storage::assertExists($option->swatch_value);
});

it('should store an uploaded swatch as webp, whatever extension its name carries', function () {
    Storage::fake();

    $option = app(AttributeOptionRepository::class)->create([
        'attribute_id' => makeSwatchAttribute('image')->id,
        'admin_name' => 'Blue',
        'sort_order' => 1,
        'swatch_value' => UploadedFile::fake()->image('shell.php', 20, 20),
        'swatch_file_name' => 'shell.php',
    ]);

    expect($option->fresh()->swatch_value)->toBe(AttributeOptionRepository::SWATCH_DIRECTORY.'/shell.webp');
});

it('should refuse a swatch upload that is not an image when an attribute is created', function () {
    Storage::fake();

    $code = 'swatch_'.Str::lower(Str::random(10));

    $this->loginAsAdmin();

    postJson(route('admin.catalog.attributes.store'), swatchAttributePayload(
        $code,
        'image',
        'option_0',
        UploadedFile::fake()->createWithContent('shell.php', '<?php echo 1;'),
    ))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('options.option_0.swatch_value');

    $this->assertDatabaseMissing('attributes', ['code' => $code]);

    expect(Storage::allFiles(AttributeOptionRepository::SWATCH_DIRECTORY))->toBeEmpty();
});

it('should refuse a swatch upload that is not an image when an attribute is updated', function () {
    Storage::fake();

    $option = makeImageSwatchOption();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.attributes.update', $option->attribute_id), swatchAttributePayload(
        $option->attribute->code,
        'image',
        $option->id,
        UploadedFile::fake()->createWithContent('shell.php', '<?php echo 1;'),
    ))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('options.'.$option->id.'.swatch_value');

    expect($option->fresh()->swatch_value)->toBe(AttributeOptionRepository::SWATCH_DIRECTORY.'/kw82ndlq.png');
});

it('should keep accepting a colour swatch value, which is not an upload', function () {
    $code = 'swatch_'.Str::lower(Str::random(10));

    $this->loginAsAdmin();

    postJson(route('admin.catalog.attributes.store'), swatchAttributePayload($code, 'color', 'option_0', '#0000ff'))
        ->assertRedirectToRoute('admin.catalog.attributes.index');

    $attribute = Attribute::query()->where('code', $code)->firstOrFail();

    expect($attribute->options->first()->swatch_value)->toBe('#0000ff');
});
