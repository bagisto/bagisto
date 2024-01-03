<?php

use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxRate;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

afterEach(function () {
    /**
     * Cleaning up rows which are created.
     */
    TaxRate::query()->delete();
    TaxCategory::query()->delete();
});

it('should returns the tax category index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.taxes.categories.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.taxes.categories.index.title'))
        ->assertSeeText(trans('admin::app.settings.taxes.categories.index.create.title'));
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

    $this->assertDatabaseHas('tax_categories', [
        'code'        => $code,
        'name'        => $name,
        'description' => $description,
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

    $this->assertDatabaseHas('tax_categories', [
        'code'        => $code,
        'name'        => $name,
        'description' => $description,
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
