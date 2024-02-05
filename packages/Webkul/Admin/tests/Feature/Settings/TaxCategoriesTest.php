<?php

use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxRate;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should returns the tax category index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.taxes.categories.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.taxes.categories.index.title'))
        ->assertSeeText(trans('admin::app.settings.taxes.categories.index.create.title'));
});

it('should fail the validation with errors when certain field not provided when store the tax categories', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.taxes.categories.store'))
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('taxrates')
        ->assertUnprocessable();
});

it('should store the tax category', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.taxes.categories.store'), [
        'code'        => $code = fake()->uuid(),
        'name'        => $name = fake()->words(2, true),
        'description' => $description = fake()->sentence(10),
        'taxrates'    => TaxRate::factory()->count(2)->create()->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.taxes.categories.index.create-success'));

    $this->assertModelWise([
        TaxCategory::class => [
            [
                'code'        => $code,
                'name'        => $name,
                'description' => $description,
            ],
        ],
    ]);
});

it('should returns the edit page of the tax category', function () {
    // Arrange
    $taxCategory = TaxCategory::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.taxes.categories.edit', $taxCategory->id))
        ->assertOk()
        ->assertJsonPath('data.id', $taxCategory->id)
        ->assertJsonPath('data.code', $taxCategory->code)
        ->assertJsonPath('data.name', $taxCategory->name);
});

it('should fail the validation with errors when certain field not provided when update the tax categories', function () {
    // Arrange
    $taxCategory = TaxCategory::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.settings.taxes.categories.update'), [
        'id' => $taxCategory->id,
    ])
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('taxrates')
        ->assertUnprocessable();
});

it('should update the tax category', function () {
    // Arrange
    $taxCategory = TaxCategory::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.settings.taxes.categories.update'), [
        'id'          => $taxCategory->id,
        'code'        => $code = fake()->uuid(),
        'name'        => $name = fake()->words(2, true),
        'description' => $description = fake()->sentence(10),
        'taxrates'    => TaxRate::factory()->count(2)->create()->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.settings.taxes.categories.index.update-success'));

    $this->assertModelWise([
        TaxCategory::class => [
            [
                'code'        => $code,
                'name'        => $name,
                'description' => $description,
            ],
        ],
    ]);
});

it('should delete the tax category', function () {
    // Arrange
    $taxCategory = TaxCategory::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.settings.taxes.categories.delete', $taxCategory->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.settings.taxes.categories.index.delete-success'));
});
