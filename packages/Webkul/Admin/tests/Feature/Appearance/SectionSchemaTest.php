<?php

use Webkul\Theme\Enums\SectionTypeEnum;
use Webkul\Theme\Models\Section;
use Webkul\Theme\SectionSchema;

use function Pest\Laravel\postJson;

it('should describe every section type', function () {
    $schema = app(SectionSchema::class);

    foreach (SectionTypeEnum::getValues() as $type) {
        expect($schema->for($type))->not->toBeEmpty("no schema for {$type}");
    }
});

it('should give every field a key, a type and a translated label', function () {
    $schema = app(SectionSchema::class);

    $walk = function ($fields) use (&$walk) {
        foreach ($fields as $field) {
            expect($field)->toHaveKeys(['key', 'type', 'label']);

            expect($field['label'])->toBeString()->not->toContain('appearance.sections');

            if (! empty($field['fields'])) {
                $walk($field['fields']);
            }
        }
    };

    foreach ($schema->all() as $fields) {
        $walk($fields);
    }
});

it('should cover every key the stored sections actually use, once shaped for the editor', function () {
    $schema = app(SectionSchema::class);

    $sections = Section::query()->get()->unique('type');

    expect($sections)->not->toBeEmpty();

    foreach ($sections as $section) {
        $stored = array_keys($section->getTypeInstance()->prepareForEditor($section->translate('en')?->options ?? []));

        $uncovered = array_diff($stored, $schema->keysFor($section->type));

        expect($uncovered)->toBeEmpty(
            $section->type.' stores keys the schema does not describe: '.implode(', ', $uncovered)
        );
    }
});

it('should return an empty schema for an unknown type', function () {
    expect(app(SectionSchema::class)->for('not-a-type'))->toBe([]);
});

it('should describe every type a section may take', function () {
    expect(array_keys(app(SectionSchema::class)->all()))
        ->toEqualCanonicalizing(SectionTypeEnum::getValues());
});

it('should accept exactly the types the model declares', function () {
    $this->loginAsAdmin();

    $channel = core()->getDefaultChannel();

    postJson(route('admin.appearance.sections.store', [
        'code' => $channel->theme,
        'channel' => $channel->id,
    ]), [
        'name' => 'Not A Real Type',
        'type' => 'carousel_of_carousels',
    ])->assertJsonValidationErrorFor('type');
});
