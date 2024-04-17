<?php

use Webkul\Attribute\Models\Attribute;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should show attribute page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.catalog.attributes.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.attributes.index.title'))
        ->assertSeeText(trans('admin::app.catalog.attributes.index.create-btn'));
});

it('should return listing items of attributes', function () {
    // Arrange.
    $attribute = Attribute::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    getJson(route('admin.catalog.attributes.index'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertOk()
        ->assertJsonPath('records.0.id', $attribute->id)
        ->assertJsonPath('records.0.code', $attribute->code)
        ->assertJsonPath('meta.total', 29);
});

it('should returns attributes options', function () {
    // Arrange.
    $attribute = Attribute::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.catalog.attributes.options', $attribute->id))
        ->assertOk()
        ->assertJsonIsArray();
});

it('should show create page of attribute', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.catalog.attributes.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.attributes.create.title'))
        ->assertSeeText(trans('admin::app.catalog.attributes.create.save-btn'));
});

it('should fail the validation with errors when certain inputs are not provided when store in attribute', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.catalog.attributes.store'))
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('admin_name')
        ->assertJsonValidationErrorFor('type')
        ->assertUnprocessable();
});

it('should store newly created attribute', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.catalog.attributes.store'), $data = [
        'admin_name'    => fake()->name(),
        'code'          => fake()->numerify('code########'),
        'type'          => 'text',
        'default_value' => 1,
    ])
        ->assertRedirectToRoute('admin.catalog.attributes.index')
        ->isRedirection();

    $this->assertModelWise([
        Attribute::class => [
            $data,
        ],
    ]);
});

it('should show edit page of attribute', function () {
    // Arrange.
    $attribute = Attribute::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.catalog.attributes.edit', $attribute->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.attributes.edit.title'))
        ->assertSeeText(trans('admin::app.catalog.attributes.edit.back-btn'));
});

it('should fail the validation with errors when certain inputs are not provided when update in attribute', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.catalog.attributes.store'))
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('admin_name')
        ->assertJsonValidationErrorFor('type')
        ->assertUnprocessable();
});

it('should update an attribute', function () {
    // Arrange.
    $attribute = Attribute::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.catalog.attributes.update', $attribute->id), $data = [
        'admin_name'    => fake()->name(),
        'code'          => $attribute->code,
        'type'          => $attribute->type,
        'default_value' => 1,
    ])
        ->assertRedirectToRoute('admin.catalog.attributes.index')
        ->isRedirection();

    $this->assertModelWise([
        Attribute::class => [
            $data,
        ],
    ]);
});

it('should destroy an attribute', function () {
    // Arrange.
    $attribute = Attribute::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.attributes.delete', $attribute->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.attributes.delete-success'));

    $this->assertDatabaseMissing('attributes', [
        'id' => $attribute->id,
    ]);
});

it('should not destroy an attribute if it is not user-defined', function () {
    // Arrange.
    $attribute = Attribute::factory()->create([
        'is_user_defined' => false,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.attributes.delete', $attribute->id))
        ->assertBadRequest()
        ->assertSeeText(trans('admin::app.catalog.attributes.user-define-error'));

    $this->assertModelWise([
        Attribute::class => [
            [
                'id' => $attribute->id,
            ],
        ],
    ]);
});

it('should mass delete attributes', function () {
    // Arrange.
    $attributes = Attribute::factory()->count(2)->create();

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.catalog.attributes.mass_delete', [
        'indices' => $attributes->pluck('id')->toArray(),
    ]))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.attributes.index.datagrid.mass-delete-success'));

    foreach ($attributes as $attribute) {
        $this->assertDatabaseMissing('attributes', [
            'id' => $attribute->id,
        ]);
    }
});
