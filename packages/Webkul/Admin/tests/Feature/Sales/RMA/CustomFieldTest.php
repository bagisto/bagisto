<?php

use Webkul\RMA\Models\RMACustomField;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

// ============================================================================
// Index
// ============================================================================

it('should return the RMA custom fields index page', function () {
    $this->loginAsAdmin();

    get(route('admin.sales.rma.custom-fields.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.rma.custom-field.index.title'));
});

it('should deny guest access to the RMA custom fields index page', function () {
    get(route('admin.sales.rma.custom-fields.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Create
// ============================================================================

it('should return the RMA custom field create page', function () {
    $this->loginAsAdmin();

    get(route('admin.sales.rma.custom-fields.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.rma.custom-field.create.create-title'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a text type custom field', function () {
    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.custom-fields.store'), [
        'label' => $label = fake()->words(2, true),
        'code' => $code = fake()->unique()->lexify('field_????'),
        'position' => 1,
        'type' => 'text',
        'status' => 1,
    ])
        ->assertRedirect();

    $this->assertDatabaseHas('rma_custom_fields', [
        'label' => $label,
        'code' => $code,
        'type' => 'text',
    ]);
});

it('should store a select type custom field with options', function () {
    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.custom-fields.store'), [
        'label' => $label = fake()->words(2, true),
        'code' => fake()->unique()->lexify('field_????'),
        'position' => 1,
        'type' => 'select',
        'status' => 1,
        'options' => ['Option A', 'Option B'],
        'value' => ['option_a', 'option_b'],
    ])
        ->assertRedirect();

    $this->assertDatabaseHas('rma_custom_fields', [
        'label' => $label,
        'type' => 'select',
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.custom-fields.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('label')
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('position')
        ->assertJsonValidationErrorFor('type');
});

// ============================================================================
// Edit
// ============================================================================

it('should return the RMA custom field edit page', function () {
    $field = RMACustomField::create([
        'label' => fake()->words(2, true),
        'code' => fake()->unique()->lexify('field_????'),
        'position' => 1,
        'type' => 'text',
        'status' => true,
    ]);

    $this->loginAsAdmin();

    get(route('admin.sales.rma.custom-fields.edit', $field->id))
        ->assertOk();
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing custom field', function () {
    $field = RMACustomField::create([
        'label' => fake()->words(2, true),
        'code' => fake()->unique()->lexify('field_????'),
        'position' => 1,
        'type' => 'text',
        'status' => true,
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.custom-fields.update', $field->id), [
        'label' => $label = fake()->words(2, true),
        'code' => $field->code,
        'position' => 2,
        'type' => 'text',
        'status' => 1,
    ])
        ->assertRedirect();

    $this->assertDatabaseHas('rma_custom_fields', [
        'id' => $field->id,
        'label' => $label,
    ]);
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a custom field', function () {
    $field = RMACustomField::create([
        'label' => fake()->words(2, true),
        'code' => fake()->unique()->lexify('field_????'),
        'position' => 1,
        'type' => 'text',
        'status' => true,
    ]);

    $this->loginAsAdmin();

    deleteJson(route('admin.sales.rma.custom-fields.delete', $field->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.rma.custom-field.index.datagrid.delete-success'));

    $this->assertDatabaseMissing('rma_custom_fields', ['id' => $field->id]);
});

// ============================================================================
// Mass Delete
// ============================================================================

it('should mass delete custom fields', function () {
    $fields = collect([
        RMACustomField::create(['label' => fake()->words(2, true), 'code' => fake()->unique()->lexify('f_????'), 'position' => 1, 'type' => 'text', 'status' => true]),
        RMACustomField::create(['label' => fake()->words(2, true), 'code' => fake()->unique()->lexify('f_????'), 'position' => 2, 'type' => 'text', 'status' => true]),
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.custom-fields.mass-delete'), [
        'indices' => $fields->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.rma.custom-field.index.datagrid.delete-success'));

    foreach ($fields as $field) {
        $this->assertDatabaseMissing('rma_custom_fields', ['id' => $field->id]);
    }
});
