<?php

use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxRate;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the tax category index page', function () {
    $this->loginAsAdmin();

    get(route('admin.settings.taxes.categories.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.taxes.categories.index.title'));
});

it('should deny guest access to the tax category index page', function () {
    get(route('admin.settings.taxes.categories.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created tax category', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.taxes.categories.store'), $data = [
        'code' => fake()->numerify('code#######'),
        'name' => fake()->words(2, true),
        'description' => fake()->sentence(10),
        'taxrates' => TaxRate::factory()->count(2)->create()->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.taxes.categories.index.create-success'));

    $this->assertDatabaseHas('tax_categories', [
        'code' => $data['code'],
        'name' => $data['name'],
        'description' => $data['description'],
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.taxes.categories.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('taxrates');
});

// ============================================================================
// Edit
// ============================================================================

it('should return tax category details for edit', function () {
    $taxCategory = TaxCategory::factory()->create();

    $this->loginAsAdmin();

    get(route('admin.settings.taxes.categories.edit', $taxCategory->id))
        ->assertOk();
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing tax category', function () {
    $taxCategory = TaxCategory::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.taxes.categories.update'), $data = [
        'id' => $taxCategory->id,
        'code' => fake()->numerify('code#######'),
        'name' => fake()->words(2, true),
        'description' => fake()->sentence(10),
        'taxrates' => TaxRate::factory()->count(2)->create()->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.settings.taxes.categories.index.update-success'));

    $this->assertDatabaseHas('tax_categories', [
        'id' => $taxCategory->id,
        'code' => $data['code'],
        'name' => $data['name'],
        'description' => $data['description'],
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $taxCategory = TaxCategory::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.taxes.categories.update'), [
        'id' => $taxCategory->id,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('taxrates');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a tax category', function () {
    $taxCategory = TaxCategory::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.settings.taxes.categories.delete', $taxCategory->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.settings.taxes.categories.index.delete-success'));

    $this->assertDatabaseMissing('tax_categories', ['id' => $taxCategory->id]);
});
