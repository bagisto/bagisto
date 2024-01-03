<?php

use Webkul\Attribute\Models\AttributeFamily as AttributeFamilyModel;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

afterEach(function () {
    /**
     * Clean attribute families, excluding ID 1 (i.e., the default attribut family). A fresh instance will always have ID 1.
     */
    AttributeFamilyModel::query()
        ->whereNot('id', 1)
        ->delete();
});

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

    $this->assertDatabaseHas('attribute_families', [
        'code' => $code,
        'name' => $name,
    ]);
});

it('should return edit page of attribute families', function () {
    // Arrange
    $AttributeFamily = AttributeFamilyModel::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.catalog.families.edit', $AttributeFamily->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.families.edit.title'))
        ->assertSeeText(trans('admin::app.catalog.families.edit.back-btn'));
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

    $this->assertDatabaseHas('attribute_families', [
        'code' => $updatedCode,
        'name' => $attributeFamily->name,
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

    $this->assertDatabaseHas('attribute_families', [
        'id' => $attributeFamilyId,
    ]);
});
