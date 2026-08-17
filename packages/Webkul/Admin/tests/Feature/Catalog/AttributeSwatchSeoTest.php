<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Attribute\Repositories\AttributeOptionRepository;

function makeImageSwatchOption(): AttributeOption
{
    $attribute = Attribute::factory()->create([
        'type' => 'select',
        'swatch_type' => 'image',
    ]);

    $option = AttributeOption::create([
        'attribute_id' => $attribute->id,
        'admin_name' => 'Blue',
        'sort_order' => 1,
        'swatch_value' => AttributeOptionRepository::SWATCH_DIRECTORY.'/kw82ndlq.png',
    ]);

    Storage::put($option->swatch_value, 'swatch-contents');

    return $option;
}

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

    $option = $option->fresh();

    expect($option->translate('en')->swatch_alt)->toBe('Blue fabric swatch');

    expect($option->translate('fr')->swatch_alt)->toBe('Échantillon de tissu bleu');

    app()->setLocale('en');
});

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

    $attribute = Attribute::factory()->create([
        'type' => 'select',
        'swatch_type' => 'color',
    ]);

    $option = AttributeOption::create([
        'attribute_id' => $attribute->id,
        'admin_name' => 'Blue',
        'sort_order' => 1,
        'swatch_value' => '#0000ff',
    ]);

    app(AttributeOptionRepository::class)->update([
        'swatch_file_name' => 'Blue Fabric Swatch',
    ], $option->id);

    expect($option->fresh()->swatch_value)->toBe('#0000ff');
});

it('should name a newly uploaded swatch after the requested file name', function () {
    Storage::fake();

    $attribute = Attribute::factory()->create([
        'type' => 'select',
        'swatch_type' => 'image',
    ]);

    $option = app(AttributeOptionRepository::class)->create([
        'attribute_id' => $attribute->id,
        'admin_name' => 'Blue',
        'sort_order' => 1,
        'swatch_value' => UploadedFile::fake()->image('DSC_0004.png', 20, 20),
        'swatch_file_name' => 'Blue Fabric Swatch',
        'swatch_alt' => 'Blue fabric swatch',
    ]);

    $option = $option->fresh();

    expect($option->swatch_value)->toBe(AttributeOptionRepository::SWATCH_DIRECTORY.'/blue-fabric-swatch.png');

    expect($option->swatch_alt)->toBe('Blue fabric swatch');

    Storage::assertExists($option->swatch_value);
});
