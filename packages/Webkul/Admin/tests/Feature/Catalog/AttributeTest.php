<?php

use Illuminate\Support\Facades\Event;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Product\Models\Product;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Datasets
// ============================================================================

dataset('attribute_types', [
    'text' => ['text'],
    'textarea' => ['textarea'],
    'price' => ['price'],
    'boolean' => ['boolean'],
    'select' => ['select'],
    'multiselect' => ['multiselect'],
    'date' => ['date'],
    'datetime' => ['datetime'],
    'image' => ['image'],
    'file' => ['file'],
    'checkbox' => ['checkbox'],
]);

dataset('option_types', [
    'select' => ['select'],
    'multiselect' => ['multiselect'],
    'checkbox' => ['checkbox'],
]);

dataset('non_option_types', [
    'text' => ['text'],
    'textarea' => ['textarea'],
    'price' => ['price'],
    'boolean' => ['boolean'],
    'date' => ['date'],
    'datetime' => ['datetime'],
    'image' => ['image'],
    'file' => ['file'],
]);

dataset('non_filterable_types', [
    'text' => ['text'],
    'textarea' => ['textarea'],
    'price' => ['price'],
    'date' => ['date'],
    'datetime' => ['datetime'],
    'image' => ['image'],
    'file' => ['file'],
]);

dataset('filterable_types', [
    'select' => ['select'],
    'multiselect' => ['multiselect'],
    'boolean' => ['boolean'],
    'checkbox' => ['checkbox'],
]);

dataset('locale_unset_types', [
    'select' => ['select'],
    'multiselect' => ['multiselect'],
    'boolean' => ['boolean'],
]);

dataset('swatch_types', [
    'dropdown' => ['dropdown'],
    'color' => ['color'],
    'image' => ['image'],
    'text' => ['text'],
]);

dataset('validation_types', [
    'numeric' => ['numeric'],
    'email' => ['email'],
    'decimal' => ['decimal'],
    'url' => ['url'],
    'regex' => ['regex'],
]);

// ============================================================================
// Index
// ============================================================================

it('should return the attribute index page', function () {
    $this->loginAsAdmin();

    get(route('admin.catalog.attributes.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.attributes.index.title'))
        ->assertSeeText(trans('admin::app.catalog.attributes.index.create-btn'));
});

it('should return attribute listing via datagrid', function () {
    $attribute = Attribute::factory()->create();

    $this->loginAsAdmin();

    getJson(route('admin.catalog.attributes.index'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertOk()
        ->assertJsonPath('records.0.id', $attribute->id)
        ->assertJsonPath('records.0.code', $attribute->code);
});

it('should deny guest access to the attribute index page', function () {
    get(route('admin.catalog.attributes.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Create
// ============================================================================

it('should return the attribute create page', function () {
    $this->loginAsAdmin();

    get(route('admin.catalog.attributes.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.attributes.create.title'))
        ->assertSeeText(trans('admin::app.catalog.attributes.create.save-btn'));
});

// ============================================================================
// Store — All attribute types
// ============================================================================

it('should store a [type] attribute', function (string $type) {
    $this->loginAsAdmin();

    $code = fake()->unique()->lexify('attr_??????????');

    postJson(route('admin.catalog.attributes.store'), [
        'code' => $code,
        'admin_name' => "Test {$type} attribute",
        'type' => $type,
    ])
        ->assertRedirectToRoute('admin.catalog.attributes.index');

    $this->assertDatabaseHas('attributes', [
        'code' => $code,
        'type' => $type,
    ]);
})->with('attribute_types');

// ============================================================================
// Store — Option-based types with options
// ============================================================================

it('should store a [type] attribute with options', function (string $type) {
    $this->loginAsAdmin();

    $code = fake()->unique()->lexify('attr_??????????');

    postJson(route('admin.catalog.attributes.store'), [
        'code' => $code,
        'admin_name' => "Test {$type} with options",
        'type' => $type,
        'options' => [
            ['admin_name' => 'Option A', 'sort_order' => 1],
            ['admin_name' => 'Option B', 'sort_order' => 2],
            ['admin_name' => 'Option C', 'sort_order' => 3],
        ],
    ])
        ->assertRedirectToRoute('admin.catalog.attributes.index');

    $attribute = Attribute::where('code', $code)->first();

    expect($attribute)->not->toBeNull();
    expect($attribute->options)->toHaveCount(3);
})->with('option_types');

it('should ignore options for non-option [type] attributes', function (string $type) {
    $this->loginAsAdmin();

    $code = fake()->unique()->lexify('attr_??????????');

    postJson(route('admin.catalog.attributes.store'), [
        'code' => $code,
        'admin_name' => "Test {$type} ignores options",
        'type' => $type,
        'options' => [
            ['admin_name' => 'Should be ignored', 'sort_order' => 1],
        ],
    ])
        ->assertRedirectToRoute('admin.catalog.attributes.index');

    $attribute = Attribute::where('code', $code)->first();

    expect($attribute->options)->toHaveCount(0);
})->with('non_option_types');

// ============================================================================
// Store — Boolean default value
// ============================================================================

it('should store a boolean attribute with valid default value', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.attributes.store'), [
        'code' => fake()->unique()->lexify('attr_??????????'),
        'admin_name' => 'Test Boolean Attribute',
        'type' => 'boolean',
        'default_value' => 1,
    ])
        ->assertRedirectToRoute('admin.catalog.attributes.index');
});

it('should fail validation for invalid boolean default value on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.attributes.store'), [
        'code' => fake()->unique()->lexify('attr_??????????'),
        'admin_name' => 'Boolean Invalid',
        'type' => 'boolean',
        'default_value' => 5,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('default_value');
});

// ============================================================================
// Store — Business logic: filterable, configurable, locale
// ============================================================================

it('should force is_filterable to 0 for non-filterable [type] attributes', function (string $type) {
    $this->loginAsAdmin();

    $code = fake()->unique()->lexify('attr_??????????');

    postJson(route('admin.catalog.attributes.store'), [
        'code' => $code,
        'admin_name' => 'Filterable test',
        'type' => $type,
        'is_filterable' => 1,
    ])
        ->assertRedirectToRoute('admin.catalog.attributes.index');

    $this->assertDatabaseHas('attributes', [
        'code' => $code,
        'is_filterable' => 0,
    ]);
})->with('non_filterable_types');

it('should allow is_filterable for filterable [type] attributes', function (string $type) {
    $this->loginAsAdmin();

    $code = fake()->unique()->lexify('attr_??????????');

    postJson(route('admin.catalog.attributes.store'), [
        'code' => $code,
        'admin_name' => 'Filterable test',
        'type' => $type,
        'is_filterable' => 1,
    ])
        ->assertRedirectToRoute('admin.catalog.attributes.index');

    $this->assertDatabaseHas('attributes', [
        'code' => $code,
        'is_filterable' => 1,
    ]);
})->with('filterable_types');

it('should force value_per_channel and value_per_locale to 0 when is_configurable is set', function () {
    $this->loginAsAdmin();

    $code = fake()->unique()->lexify('attr_??????????');

    postJson(route('admin.catalog.attributes.store'), [
        'code' => $code,
        'admin_name' => 'Configurable test',
        'type' => 'select',
        'is_configurable' => 1,
        'value_per_channel' => 1,
        'value_per_locale' => 1,
    ])
        ->assertRedirectToRoute('admin.catalog.attributes.index');

    $this->assertDatabaseHas('attributes', [
        'code' => $code,
        'value_per_channel' => 0,
        'value_per_locale' => 0,
    ]);
});

it('should unset value_per_locale for [type] attributes', function (string $type) {
    $this->loginAsAdmin();

    $code = fake()->unique()->lexify('attr_??????????');

    postJson(route('admin.catalog.attributes.store'), [
        'code' => $code,
        'admin_name' => 'Locale unset test',
        'type' => $type,
        'value_per_locale' => 1,
    ])
        ->assertRedirectToRoute('admin.catalog.attributes.index');

    $attribute = Attribute::where('code', $code)->first();

    // select, multiselect, boolean types cannot be locale-specific.
    expect($attribute->value_per_locale)->toBeFalsy();
})->with('locale_unset_types');

// ============================================================================
// Store — Swatch types
// ============================================================================

it('should store a select attribute with [swatch_type] swatch type', function (string $swatchType) {
    $this->loginAsAdmin();

    $code = fake()->unique()->lexify('attr_??????????');

    postJson(route('admin.catalog.attributes.store'), [
        'code' => $code,
        'admin_name' => "Swatch {$swatchType}",
        'type' => 'select',
        'swatch_type' => $swatchType,
    ])
        ->assertRedirectToRoute('admin.catalog.attributes.index');

    $this->assertDatabaseHas('attributes', [
        'code' => $code,
        'swatch_type' => $swatchType,
    ]);
})->with('swatch_types');

// ============================================================================
// Store — Validation types
// ============================================================================

it('should store a text attribute with [validation] validation', function (string $validation) {
    $this->loginAsAdmin();

    $code = fake()->unique()->lexify('attr_??????????');

    postJson(route('admin.catalog.attributes.store'), [
        'code' => $code,
        'admin_name' => "Validation {$validation}",
        'type' => 'text',
        'validation' => $validation,
    ])
        ->assertRedirectToRoute('admin.catalog.attributes.index');

    $this->assertDatabaseHas('attributes', [
        'code' => $code,
        'validation' => $validation,
    ]);
})->with('validation_types');

// ============================================================================
// Store — Validation errors
// ============================================================================

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.attributes.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('admin_name')
        ->assertJsonValidationErrorFor('type');
});

it('should fail validation when code starts with a number', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.attributes.store'), [
        'code' => '123invalid',
        'admin_name' => 'Invalid Code',
        'type' => 'text',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code');
});

it('should fail validation when code contains special characters', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.attributes.store'), [
        'code' => 'invalid-code!',
        'admin_name' => 'Invalid Code',
        'type' => 'text',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code');
});

it('should fail validation when code is a reserved word', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.attributes.store'), [
        'code' => 'type',
        'admin_name' => 'Reserved Code',
        'type' => 'text',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code');

    postJson(route('admin.catalog.attributes.store'), [
        'code' => 'attribute_family_id',
        'admin_name' => 'Reserved Code',
        'type' => 'text',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code');
});

it('should fail validation when code already exists on store', function () {
    $existing = Attribute::factory()->create();

    $this->loginAsAdmin();

    postJson(route('admin.catalog.attributes.store'), [
        'code' => $existing->code,
        'admin_name' => 'Duplicate Code',
        'type' => 'text',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code');
});

// ============================================================================
// Store — Events
// ============================================================================

it('should dispatch create events when storing a [type] attribute', function (string $type) {
    Event::fake();

    $this->loginAsAdmin();

    postJson(route('admin.catalog.attributes.store'), [
        'code' => fake()->unique()->lexify('attr_??????????'),
        'admin_name' => "Event {$type} attribute",
        'type' => $type,
    ])
        ->assertRedirectToRoute('admin.catalog.attributes.index');

    Event::assertDispatched('catalog.attribute.create.before');
    Event::assertDispatched('catalog.attribute.create.after');
})->with('attribute_types');

// ============================================================================
// Edit
// ============================================================================

it('should return the attribute edit page', function () {
    $attribute = Attribute::factory()->create();

    $this->loginAsAdmin();

    get(route('admin.catalog.attributes.edit', $attribute->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.attributes.edit.title'));
});

it('should return 404 for a non-existent attribute edit page', function () {
    $this->loginAsAdmin();

    get(route('admin.catalog.attributes.edit', 99999))
        ->assertNotFound();
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing attribute', function () {
    $attribute = Attribute::factory()->create(['type' => 'text']);

    $this->loginAsAdmin();

    putJson(route('admin.catalog.attributes.update', $attribute->id), [
        'code' => $attribute->code,
        'admin_name' => 'Updated Admin Name',
        'type' => $attribute->type,
    ])
        ->assertRedirectToRoute('admin.catalog.attributes.index');

    $this->assertDatabaseHas('attributes', [
        'id' => $attribute->id,
        'admin_name' => 'Updated Admin Name',
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $attribute = Attribute::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.attributes.update', $attribute->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('admin_name')
        ->assertJsonValidationErrorFor('type');
});

it('should disable boolean fields when set to zero on update', function () {
    $attribute = Attribute::factory()->create([
        'type' => 'text',
        'is_required' => true,
        'is_unique' => true,
        'is_visible_on_front' => true,
        'is_comparable' => true,
    ]);

    $this->loginAsAdmin();

    putJson(route('admin.catalog.attributes.update', $attribute->id), [
        'code' => $attribute->code,
        'admin_name' => $attribute->admin_name,
        'type' => $attribute->type,
        'is_required' => 0,
        'is_unique' => 0,
        'is_visible_on_front' => 0,
        'is_comparable' => 0,
    ])
        ->assertRedirectToRoute('admin.catalog.attributes.index');

    $attribute->refresh();

    expect($attribute->is_required)->toBeFalse()
        ->and($attribute->is_unique)->toBeFalse()
        ->and($attribute->is_visible_on_front)->toBeFalse()
        ->and($attribute->is_comparable)->toBeFalse();
});

it('should allow updating an attribute with its own code', function () {
    $attribute = Attribute::factory()->create(['type' => 'text']);

    $this->loginAsAdmin();

    putJson(route('admin.catalog.attributes.update', $attribute->id), [
        'code' => $attribute->code,
        'admin_name' => $attribute->admin_name,
        'type' => $attribute->type,
    ])
        ->assertRedirectToRoute('admin.catalog.attributes.index');
});

it('should fail validation when code conflicts with another attribute on update', function () {
    $attributeA = Attribute::factory()->create();
    $attributeB = Attribute::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.attributes.update', $attributeB->id), [
        'code' => $attributeA->code,
        'admin_name' => 'Conflict',
        'type' => $attributeB->type,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code');
});

it('should fail validation for invalid boolean default value on update', function () {
    $attribute = Attribute::factory()->create(['type' => 'boolean']);

    $this->loginAsAdmin();

    putJson(route('admin.catalog.attributes.update', $attribute->id), [
        'code' => $attribute->code,
        'admin_name' => $attribute->admin_name,
        'type' => 'boolean',
        'default_value' => 9,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('default_value');
});

it('should dispatch update events for a [type] attribute', function (string $type) {
    Event::fake();

    $attribute = Attribute::factory()->create(['type' => $type]);

    $this->loginAsAdmin();

    putJson(route('admin.catalog.attributes.update', $attribute->id), [
        'code' => $attribute->code,
        'admin_name' => 'Updated',
        'type' => $attribute->type,
    ])
        ->assertRedirectToRoute('admin.catalog.attributes.index');

    Event::assertDispatched('catalog.attribute.update.before');
    Event::assertDispatched('catalog.attribute.update.after');
})->with('attribute_types');

// ============================================================================
// Destroy
// ============================================================================

it('should delete a user-defined attribute', function () {
    $attribute = Attribute::factory()->create(['is_user_defined' => true]);

    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.attributes.delete', $attribute->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.attributes.delete-success'));

    $this->assertDatabaseMissing('attributes', [
        'id' => $attribute->id,
    ]);
});

it('should not delete a system attribute', function () {
    $attribute = Attribute::factory()->create(['is_user_defined' => false]);

    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.attributes.delete', $attribute->id))
        ->assertBadRequest()
        ->assertSeeText(trans('admin::app.catalog.attributes.user-define-error'));

    $this->assertDatabaseHas('attributes', [
        'id' => $attribute->id,
    ]);
});

it('should return 404 when deleting a non-existent attribute', function () {
    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.attributes.delete', 99999))
        ->assertNotFound();
});

it('should dispatch delete events for a [type] attribute', function (string $type) {
    Event::fake();

    $attribute = Attribute::factory()->create([
        'type' => $type,
        'is_user_defined' => true,
    ]);

    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.attributes.delete', $attribute->id))
        ->assertOk();

    Event::assertDispatched('catalog.attribute.delete.before');
    Event::assertDispatched('catalog.attribute.delete.after');
})->with('attribute_types');

// ============================================================================
// Mass Delete
// ============================================================================

it('should mass delete user-defined attributes', function () {
    $attributes = Attribute::factory()->count(3)->create(['is_user_defined' => true]);

    $this->loginAsAdmin();

    postJson(route('admin.catalog.attributes.mass_delete'), [
        'indices' => $attributes->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.attributes.index.datagrid.mass-delete-success'));

    foreach ($attributes as $attribute) {
        $this->assertDatabaseMissing('attributes', [
            'id' => $attribute->id,
        ]);
    }
});

it('should reject mass delete when any attribute is not user-defined', function () {
    $userDefined = Attribute::factory()->create(['is_user_defined' => true]);
    $system = Attribute::factory()->create(['is_user_defined' => false]);

    $this->loginAsAdmin();

    postJson(route('admin.catalog.attributes.mass_delete'), [
        'indices' => [$userDefined->id, $system->id],
    ])
        ->assertUnprocessable()
        ->assertSeeText(trans('admin::app.catalog.attributes.delete-failed'));

    $this->assertDatabaseHas('attributes', ['id' => $userDefined->id]);
    $this->assertDatabaseHas('attributes', ['id' => $system->id]);
});

it('should fail mass delete validation when indices are missing', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.attributes.mass_delete'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('indices');
});

// ============================================================================
// Attribute Options
// ============================================================================

it('should return attribute options sorted by sort order', function () {
    $attribute = Attribute::factory()->create(['type' => 'select']);

    $optionB = AttributeOption::factory()->create([
        'attribute_id' => $attribute->id,
        'admin_name' => 'Option B',
        'sort_order' => 2,
    ]);

    $optionA = AttributeOption::factory()->create([
        'attribute_id' => $attribute->id,
        'admin_name' => 'Option A',
        'sort_order' => 1,
    ]);

    $this->loginAsAdmin();

    get(route('admin.catalog.attributes.options', $attribute->id))
        ->assertOk()
        ->assertJsonCount(2)
        ->assertJsonPath('0.admin_name', 'Option A')
        ->assertJsonPath('1.admin_name', 'Option B');
});

it('should return empty array for attribute without options', function () {
    $attribute = Attribute::factory()->create(['type' => 'text']);

    $this->loginAsAdmin();

    get(route('admin.catalog.attributes.options', $attribute->id))
        ->assertOk()
        ->assertJsonCount(0);
});

// ============================================================================
// Product Super Attributes
// ============================================================================

it('should return product super attributes', function () {
    $product = Product::factory()->create(['type' => 'configurable']);

    $this->loginAsAdmin();

    getJson(route('admin.catalog.products.configurable.options', $product->id))
        ->assertOk();
});
