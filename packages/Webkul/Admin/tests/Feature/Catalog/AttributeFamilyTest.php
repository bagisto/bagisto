<?php

use Webkul\Attribute\Models\AttributeFamily as AttributeFamilyModel;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should return attribute family listing page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.catalog.families.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.families.index.title'))
        ->assertSeeText(trans('admin::app.catalog.families.index.add'));
});

it('should return listing items of attribute family', function () {
    // Arrange
    $attributeFamily = AttributeFamilyModel::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    getJson(route('admin.catalog.families.index'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertOk()
        ->assertJsonPath('records.0.id', $attributeFamily->id)
        ->assertJsonPath('records.0.code', $attributeFamily->code)
        ->assertJsonPath('meta.total', 2);
});

it('should return attribute family create page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.catalog.families.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.families.create.title'))
        ->assertSeeText(trans('admin::app.catalog.families.create.back-btn'));
});

it('should store newly created attribute family', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.families.store'), [
        'code'             => $code = strtolower(fake()->words(1, true)),
        'name'             => $name = fake()->name(),
        'attribute_groups' => [
            [
                'code'   => $code,
                'name'   => $name,
                'column' => 1,
            ],
        ],
    ])
        ->assertRedirectToRoute('admin.catalog.families.index')
        ->isRedirection();

    $this->assertModelWise([
        AttributeFamilyModel::class => [
            [
                'code' => $code,
                'name' => $name,
            ],
        ],
    ]);
});

it('should fail the validation with errors when certain inputs are not provided when store in attribute family', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.families.store'))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('code')
        ->assertUnprocessable();
});

it('should return edit page of attribute families', function () {
    // Arrange
    $attributeFamily = AttributeFamilyModel::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.catalog.families.edit', $attributeFamily->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.families.edit.title'))
        ->assertSeeText(trans('admin::app.catalog.families.edit.back-btn'));
});

it('should fail the validation with errors when certain inputs are not provided when update in attribute family', function () {
    // Arrange
    $attributeFamily = AttributeFamilyModel::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.catalog.families.update', $attributeFamily->id))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('code')
        ->assertUnprocessable();
});

it('should update the existing attribute families', function () {
    // Arrange
    $attributeFamily = AttributeFamilyModel::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.catalog.families.update', $attributeFamily->id), [
        'code' => $updatedCode = strtolower(fake()->words(1, true)),
        'name' => $attributeFamily->name,
    ])
        ->assertRedirectToRoute('admin.catalog.families.index')
        ->isRedirection();

    $this->assertModelWise([
        AttributeFamilyModel::class => [
            [
                'code' => $updatedCode,
                'name' => $attributeFamily->name,
            ],
        ],
    ]);
});

it('should delete the existing attribute family', function () {
    // Arrange
    $attributeFamily = AttributeFamilyModel::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.families.delete', $attributeFamily->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.families.delete-success'));

    $this->assertDatabaseMissing('attribute_families', [
        'id' => $attributeFamily->id,
    ]);
});

it('should not be able to delete the attribute family if the attribute family is the only one present', function () {
    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.families.delete', $attributeFamilyId = 1))
        ->assertBadRequest()
        ->assertSeeText(trans('admin::app.catalog.families.last-delete-error'));

    $this->assertModelWise([
        AttributeFamilyModel::class => [
            [
                'id' => $attributeFamilyId,
            ],
        ],
    ]);
});
