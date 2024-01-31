<?php

use Webkul\Attribute\Models\Attribute;
use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;
use Webkul\Faker\Helpers\Category as CategoryFaker;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

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
    // Arrange
    $attributes = Attribute::where('is_filterable', 1)->pluck('id')->toArray();

    // Act & Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.store'), [
        'slug'        => $slug = fake()->slug(),
        'name'        => $name = fake()->name(),
        'position'    => rand(1, 5),
        'description' => $description = substr(fake()->paragraph(), 0, 50),
        'attributes'  => $attributes,
    ])
        ->assertRedirect(route('admin.catalog.categories.index'))
        ->isRedirection();

    $this->assertModelWise([
        CategoryTranslation::class => [
            [
                'slug'        => $slug,
                'name'        => $name,
                'description' => $description,
            ],
        ],
    ]);
});

it('should fail the validation with errors when certain inputs are not provided when store in category', function () {
    // Act & Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.store'))
        ->assertJsonValidationErrorFor('attributes')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('position')
        ->assertJsonValidationErrorFor('slug')
        ->assertUnprocessable();
});

it('should fail the validation with errors of description if display mode products_and_description when store', function () {
    // Act & Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.store'), [
        'display_mode' => 'products_and_description',
    ])
        ->assertJsonValidationErrorFor('attributes')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('position')
        ->assertJsonValidationErrorFor('slug')
        ->assertUnprocessable();
});

it('should fail the validation with errors of logo_path and banner_path is not an array and image', function () {
    // Act & Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.store'), [
        'logo_path'   => fake()->word(),
        'banner_path' => fake()->word(),
    ])
        ->assertJsonValidationErrorFor('attributes')
        ->assertJsonValidationErrorFor('banner_path')
        ->assertJsonValidationErrorFor('logo_path')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('position')
        ->assertJsonValidationErrorFor('slug')
        ->assertUnprocessable();
});

it('should fail the validation with errors slug is already taken', function () {
    // Act & Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.store'), [
        'slug' => 'root',
    ])
        ->assertJsonValidationErrorFor('attributes')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('position')
        ->assertJsonValidationErrorFor('slug')
        ->assertUnprocessable();
});

it('should fail the validation with errors when certain inputs are not provided when update in category', function () {
    // Arrange
    $category = (new CategoryFaker())->factory()->create();

    $localeCode = core()->getRequestedLocaleCode();

    // Act & Assert
    $this->loginAsAdmin();

    putJson(route('admin.catalog.categories.update', $category->id))
        ->assertJsonValidationErrorFor($localeCode.'.name')
        ->assertJsonValidationErrorFor($localeCode.'.slug')
        ->assertJsonValidationErrorFor('position')
        ->assertJsonValidationErrorFor('attributes')
        ->assertUnprocessable();
});

it('should fail the validation with errors when certain inputs are not provided and display mode products and description when update in category', function () {
    // Arrange
    $category = (new CategoryFaker())->factory()->create();

    $localeCode = core()->getRequestedLocaleCode();

    // Act & Assert
    $this->loginAsAdmin();

    putJson(route('admin.catalog.categories.update', $category->id), [
        'display_mode' => 'products_and_description',
    ])
        ->assertJsonValidationErrorFor($localeCode.'.name')
        ->assertJsonValidationErrorFor($localeCode.'.slug')
        ->assertJsonValidationErrorFor($localeCode.'.description')
        ->assertJsonValidationErrorFor('position')
        ->assertJsonValidationErrorFor('attributes')
        ->assertUnprocessable();
});

it('should update a category', function () {
    // Arrange
    $category = (new CategoryFaker())->factory()->create();

    $attributes = Attribute::where('is_filterable', 1)->pluck('id')->toArray();

    // Act & Assert
    $this->loginAsAdmin();

    putJson(route('admin.catalog.categories.update', $category->id), [
        'en' => [
            'name'        => $name = fake()->name(),
            'slug'        => $category->slug,
            'description' => $description = substr(fake()->paragraph(), 0, 50),
        ],

        'locale'     => config('app.locale'),
        'attributes' => $attributes,
        'position'   => rand(1, 5),
    ])
        ->assertRedirect(route('admin.catalog.categories.index'))
        ->isRedirection();

    $this->assertModelWise([
        CategoryTranslation::class => [
            [
                'name'        => $name,
                'slug'        => $category->slug,
                'description' => $description,
            ],
        ],
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
        $this->assertModelWise([
            Category::class => [
                [
                    'id'     => $category->id,
                    'status' => 1,
                ],
            ],
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
