<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Webkul\Attribute\Models\Attribute;
use Webkul\Category\Models\Category;
use Webkul\Core\Models\Channel;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Helpers
// ============================================================================

/**
 * Create a category with its translation record via the factory.
 */
function createCategory(array $attributes = []): Category
{
    return Category::factory()
        ->hasTranslations()
        ->create($attributes);
}

/**
 * Get filterable attribute IDs required by the CategoryRequest validation.
 */
function filterableAttributeIds(): array
{
    return Attribute::where('is_filterable', 1)->pluck('id')->toArray();
}

// ============================================================================
// Index
// ============================================================================

it('should return the category index page', function () {
    $this->loginAsAdmin();

    get(route('admin.catalog.categories.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.categories.index.title'));
});

it('should return category listing via datagrid', function () {
    $category = createCategory();

    $this->loginAsAdmin();

    getJson(route('admin.catalog.categories.index'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertOk()
        ->assertJsonPath('records.0.category_id', $category->id);
});

it('should deny guest access to the category index page', function () {
    get(route('admin.catalog.categories.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Create
// ============================================================================

it('should return the category create page', function () {
    $this->loginAsAdmin();

    get(route('admin.catalog.categories.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.categories.create.title'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a new category', function () {
    $this->loginAsAdmin();

    $slug = fake()->unique()->slug();

    postJson(route('admin.catalog.categories.store'), [
        'slug' => $slug,
        'name' => 'Test Category',
        'position' => 1,
        'description' => 'A test category description.',
        'attributes' => filterableAttributeIds(),
        'logo_path' => [UploadedFile::fake()->image('logo.png')],
        'banner_path' => [UploadedFile::fake()->image('banner.png')],
    ])
        ->assertRedirect(route('admin.catalog.categories.index'));

    $this->assertDatabaseHas('category_translations', [
        'slug' => $slug,
        'name' => 'Test Category',
    ]);
});

it('should store a category without images', function () {
    $this->loginAsAdmin();

    $slug = fake()->unique()->slug();

    postJson(route('admin.catalog.categories.store'), [
        'slug' => $slug,
        'name' => 'No Images Category',
        'position' => 2,
        'attributes' => filterableAttributeIds(),
    ])
        ->assertRedirect(route('admin.catalog.categories.index'));

    $this->assertDatabaseHas('category_translations', [
        'slug' => $slug,
        'name' => 'No Images Category',
    ]);
});

it('should store a category with display_mode products_only without requiring description', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.store'), [
        'slug' => fake()->unique()->slug(),
        'name' => 'Products Only',
        'position' => 1,
        'display_mode' => 'products_only',
        'attributes' => filterableAttributeIds(),
    ])
        ->assertRedirect(route('admin.catalog.categories.index'));
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('slug')
        ->assertJsonValidationErrorFor('position')
        ->assertJsonValidationErrorFor('attributes');
});

it('should fail validation when description is missing for products_and_description display mode', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.store'), [
        'display_mode' => 'products_and_description',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('description');
});

it('should fail validation when description is missing for description_only display mode', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.store'), [
        'display_mode' => 'description_only',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('description');
});

it('should fail validation when slug is already taken', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.store'), [
        'slug' => 'root',
        'name' => 'Duplicate Slug',
        'position' => 1,
        'attributes' => filterableAttributeIds(),
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('slug');
});

it('should fail validation when logo_path is not an array of images', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.store'), [
        'logo_path' => fake()->word(),
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('logo_path');
});

it('should fail validation when image files have invalid extensions', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.store'), [
        'slug' => fake()->unique()->slug(),
        'name' => 'Bad Images',
        'position' => 1,
        'attributes' => filterableAttributeIds(),
        'logo_path' => [UploadedFile::fake()->image('logo.php')],
        'banner_path' => [UploadedFile::fake()->image('banner.js')],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('logo_path.0')
        ->assertJsonValidationErrorFor('banner_path.0');
});

it('should dispatch events when storing a category', function () {
    Event::fake();

    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.store'), [
        'slug' => fake()->unique()->slug(),
        'name' => 'Event Category',
        'position' => 1,
        'attributes' => filterableAttributeIds(),
    ])
        ->assertRedirect(route('admin.catalog.categories.index'));

    Event::assertDispatched('catalog.category.create.before');
    Event::assertDispatched('catalog.category.create.after');
});

// ============================================================================
// Edit
// ============================================================================

it('should return the category edit page', function () {
    $category = createCategory();

    $this->loginAsAdmin();

    get(route('admin.catalog.categories.edit', $category->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.categories.edit.title'));
});

it('should return 404 for a non-existent category edit page', function () {
    $this->loginAsAdmin();

    get(route('admin.catalog.categories.edit', 99999))
        ->assertNotFound();
});

// ============================================================================
// Update
// ============================================================================

it('should update a category', function () {
    $category = createCategory();

    $localeCode = core()->getRequestedLocaleCode();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.categories.update', $category->id), [
        $localeCode => [
            'name' => 'Updated Name',
            'description' => 'Updated description.',
            'slug' => $category->slug,
        ],
        'locale' => $localeCode,
        'attributes' => filterableAttributeIds(),
        'position' => 3,
        'logo_path' => [UploadedFile::fake()->image('logo.png')],
        'banner_path' => [UploadedFile::fake()->image('banner.png')],
    ])
        ->assertRedirect(route('admin.catalog.categories.index'));

    $this->assertDatabaseHas('category_translations', [
        'category_id' => $category->id,
        'name' => 'Updated Name',
        'description' => 'Updated description.',
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $category = createCategory();

    $localeCode = core()->getRequestedLocaleCode();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.categories.update', $category->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor($localeCode.'.name')
        ->assertJsonValidationErrorFor($localeCode.'.slug')
        ->assertJsonValidationErrorFor('position')
        ->assertJsonValidationErrorFor('attributes');
});

it('should fail validation when description is missing for products_and_description on update', function () {
    $category = createCategory();

    $localeCode = core()->getRequestedLocaleCode();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.categories.update', $category->id), [
        'display_mode' => 'products_and_description',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor($localeCode.'.description');
});

it('should fail validation when image files have invalid extensions on update', function () {
    $category = createCategory();

    $localeCode = core()->getRequestedLocaleCode();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.categories.update', $category->id), [
        $localeCode => [
            'name' => 'Test',
            'slug' => $category->slug,
        ],
        'locale' => $localeCode,
        'attributes' => filterableAttributeIds(),
        'position' => 1,
        'logo_path' => [UploadedFile::fake()->image('logo.py')],
        'banner_path' => [UploadedFile::fake()->image('banner.js')],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('logo_path.0')
        ->assertJsonValidationErrorFor('banner_path.0');
});

it('should dispatch events when updating a category', function () {
    Event::fake();

    $category = createCategory();

    $localeCode = core()->getRequestedLocaleCode();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.categories.update', $category->id), [
        $localeCode => [
            'name' => 'Updated',
            'slug' => $category->slug,
        ],
        'locale' => $localeCode,
        'attributes' => filterableAttributeIds(),
        'position' => 1,
    ])
        ->assertRedirect(route('admin.catalog.categories.index'));

    Event::assertDispatched('catalog.category.update.before');
    Event::assertDispatched('catalog.category.update.after');
});

// ============================================================================
// Destroy
// ============================================================================

it('should delete a category', function () {
    $category = createCategory();

    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.categories.delete', $category->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.categories.delete-success'));

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

it('should not delete the root category', function () {
    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.categories.delete', 1))
        ->assertBadRequest()
        ->assertSeeText(trans('admin::app.catalog.categories.delete-category-root'));

    $this->assertDatabaseHas('categories', ['id' => 1]);
});

it('should not delete a channel root category', function () {
    $this->loginAsAdmin();

    // The default channel's root_category_id is 1, which is already the
    // system root. Create a second root category and assign it to a channel
    // to test the channel root guard separately.
    $rootCategory = Category::factory()->hasTranslations()->create(['parent_id' => null]);

    $channel = Channel::first();
    $originalRoot = $channel->root_category_id;
    $channel->update(['root_category_id' => $rootCategory->id]);

    deleteJson(route('admin.catalog.categories.delete', $rootCategory->id))
        ->assertBadRequest()
        ->assertSeeText(trans('admin::app.catalog.categories.delete-category-root'));

    $this->assertDatabaseHas('categories', ['id' => $rootCategory->id]);

    // Restore original root.
    $channel->update(['root_category_id' => $originalRoot]);
});

it('should return 404 when deleting a non-existent category', function () {
    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.categories.delete', 99999))
        ->assertNotFound();
});

it('should dispatch events when deleting a category', function () {
    Event::fake();

    $category = createCategory();

    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.categories.delete', $category->id))
        ->assertOk();

    Event::assertDispatched('catalog.category.delete.before');
    Event::assertDispatched('catalog.category.delete.after');
});

// ============================================================================
// Mass Delete
// ============================================================================

it('should mass delete categories', function () {
    $categories = Category::factory()->count(3)->hasTranslations()->create();

    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.mass_delete'), [
        'indices' => $categories->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.categories.delete-success'));

    foreach ($categories as $category) {
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
});

it('should reject mass delete when root category is included', function () {
    $category = createCategory();

    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.mass_delete'), [
        'indices' => [1, $category->id],
    ])
        ->assertBadRequest()
        ->assertSeeText(trans('admin::app.catalog.categories.delete-category-root'));

    // Both should still exist.
    $this->assertDatabaseHas('categories', ['id' => 1]);
    $this->assertDatabaseHas('categories', ['id' => $category->id]);
});

it('should fail mass delete validation when indices are missing', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.mass_delete'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('indices');
});

// ============================================================================
// Mass Update
// ============================================================================

it('should mass update category status to active', function () {
    $categories = Category::factory()->count(3)->inactive()->hasTranslations()->create();

    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.mass_update'), [
        'indices' => $categories->pluck('id')->toArray(),
        'value' => 1,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.categories.update-success'));

    foreach ($categories as $category) {
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'status' => 1,
        ]);
    }
});

it('should mass update category status to inactive', function () {
    $categories = Category::factory()->count(3)->hasTranslations()->create(['status' => 1]);

    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.mass_update'), [
        'indices' => $categories->pluck('id')->toArray(),
        'value' => 0,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.categories.update-success'));

    foreach ($categories as $category) {
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'status' => 0,
        ]);
    }
});

it('should fail mass update validation when indices or value are missing', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.categories.mass_update'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('indices')
        ->assertJsonValidationErrorFor('value');
});

// ============================================================================
// Search
// ============================================================================

it('should search categories by name', function () {
    $category = createCategory();

    $this->loginAsAdmin();

    getJson(route('admin.catalog.categories.search', [
        'query' => $category->name,
    ]))
        ->assertOk()
        ->assertJsonPath('data.0.id', $category->id);
});

it('should return empty results for non-matching search query', function () {
    $this->loginAsAdmin();

    getJson(route('admin.catalog.categories.search', [
        'query' => 'nonexistent_category_xyz_99999',
    ]))
        ->assertOk()
        ->assertJsonPath('total', 0);
});

// ============================================================================
// Tree
// ============================================================================

it('should return the category tree', function () {
    $category = createCategory();

    $this->loginAsAdmin();

    getJson(route('admin.catalog.categories.tree'))
        ->assertOk()
        ->assertJsonPath('data.0.id', $category->id);
});
