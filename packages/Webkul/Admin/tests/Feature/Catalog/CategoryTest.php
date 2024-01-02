<?php

use Webkul\Category\Models\Category;
use Webkul\Faker\Helpers\Category as CategoryFaker;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

afterEach(function () {
    /**
     * Clean categories, excluding ID 1 (i.e., the root category). A fresh instance will always have ID 1.
     */
    Category::query()->whereNot('id', 1)->delete();
});

it('should show category page', function () {
    // Act & Assert
    $this->loginAsAdmin();

    get(route('admin.catalog.categories.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.categories.index.title'));
});

it('should show category edit page', function () {
    // Arrange
    $category = (new CategoryFaker())->factory()->create();

    // Act & Assert
    $this->loginAsAdmin();

    get(route('admin.catalog.categories.edit', $category->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.categories.edit.title'));
});

it('should return listing items of categories', function () {
    // Arrange
    $category = (new CategoryFaker())->factory()->create();

    // Act & Assert
    $this->loginAsAdmin();

    getJson(route('admin.catalog.categories.index'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertOk()
        ->assertJsonPath('records.0.category_id', $category->id)
        ->assertJsonPath('meta.total', 2);
});

it('should create a category', function () {
    // Act & Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.store'), [
        'slug'        => $slug = fake()->slug(),
        'name'        => $name = fake()->name(),
        'description' => $description = substr(fake()->paragraph(), 0, 50),
    ])
        ->assertRedirect(route('admin.catalog.categories.index'))
        ->isRedirection();

    $this->assertDatabaseHas('category_translations', [
        'slug'        => $slug,
        'name'        => $name,
        'description' => $description,
    ]);
});

it('should update a category', function () {
    // Arrange
    $category = (new CategoryFaker())->factory()->create();

    // Act & Assert
    $this->loginAsAdmin();

    putJson(route('admin.catalog.categories.update', $category->id), [
        'en' => [
            'name'        => $name = fake()->name(),
            'slug'        => $category->slug,
            'description' => $description = substr(fake()->paragraph(), 0, 50),
        ],

        'locale' => config('app.locale'),
    ])
        ->assertRedirect(route('admin.catalog.categories.index'))
        ->isRedirection();

    $this->assertDatabaseHas('category_translations', [
        'name'        => $name,
        'slug'        => $category->slug,
        'description' => $description,
    ]);
});

it('should delete a category', function () {
    // Arrange
    $category = (new CategoryFaker())->factory()->create();

    // Act & Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.categories.delete', $category->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.categories.delete-success'));

    $this->assertDatabaseMissing('categories', [
        'id' => $category->id,
    ]);
});

it('should delete mass categories', function () {
    // Arrange
    $categories = (new CategoryFaker())->create(5);

    // Act & Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.mass_delete', [
        'indices' => $categories->pluck('id')->toArray(),
    ]))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.categories.delete-success'));

    foreach ($categories as $category) {
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
});

it('should update mass categories', function () {
    // Arrange
    $categories = (new CategoryFaker())->create(5);

    // Act & Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.mass_update', [
        'indices' => $categories->pluck('id')->toArray(),
        'value'   => 1,
    ]))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.categories.update-success'));

    foreach ($categories as $category) {
        $this->assertDatabaseHas('categories', [
            'id'     => $category->id,
            'status' => 1,
        ]);
    }
});

it('should search categories with mega search', function () {
    // Arrange
    $category = (new CategoryFaker())->factory()->create();

    // Act & Assert
    $this->loginAsAdmin();

    getJson(route('admin.catalog.categories.search', [
        'query' => $category->name,
    ]))
        ->assertOk()
        ->assertJsonPath('data.0.id', $category->id)
        ->assertJsonPath('total', 1);
});

it('should show the tree view of categories', function () {
    // Arrange
    $category = (new CategoryFaker())->factory()->create();

    // Act & Assert
    $this->loginAsAdmin();

    getJson(route('admin.catalog.categories.tree'))
        ->assertOk()
        ->assertJsonPath('data.0.id', $category->id);
});
