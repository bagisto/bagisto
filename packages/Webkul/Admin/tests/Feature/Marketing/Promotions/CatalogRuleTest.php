<?php

use Webkul\CatalogRule\Models\CatalogRule;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

afterEach(function () {
    // Clean up catalog rule product price index entries to avoid interference
    // between tests when running in parallel.
    CatalogRule::query()->delete();
});

/**
 * Create a catalog rule with channels and customer groups synced.
 */
function createCatalogRule(array $attributes = []): CatalogRule
{
    return CatalogRule::factory()
        ->afterCreating(function (CatalogRule $rule) {
            $rule->channels()->sync([1]);
            $rule->customer_groups()->sync([1, 2, 3]);
        })
        ->create($attributes);
}

// ============================================================================
// Index
// ============================================================================

it('should return the catalog rule index page', function () {
    $this->loginAsAdmin();

    get(route('admin.marketing.promotions.catalog_rules.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.catalog-rules.index.title'))
        ->assertSeeText(trans('admin::app.marketing.promotions.catalog-rules.index.create-btn'));
});

it('should deny guest access to the catalog rule index page', function () {
    get(route('admin.marketing.promotions.catalog_rules.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Create
// ============================================================================

it('should return the catalog rule create page', function () {
    $this->loginAsAdmin();

    get(route('admin.marketing.promotions.catalog_rules.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.catalog-rules.create.title'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created catalog rule', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.promotions.catalog_rules.store'), [
        'name' => $name = fake()->words(3, true),
        'description' => $description = fake()->sentence(),
        'channels' => [1],
        'customer_groups' => [1, 2, 3],
        'status' => 1,
        'action_type' => 'by_percent',
        'discount_amount' => 10,
        'starts_from' => '',
        'ends_till' => '',
    ])
        ->assertRedirect(route('admin.marketing.promotions.catalog_rules.index'));

    $this->assertDatabaseHas('catalog_rules', [
        'name' => $name,
        'description' => $description,
        'action_type' => 'by_percent',
        'discount_amount' => 10,
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.promotions.catalog_rules.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('channels')
        ->assertJsonValidationErrorFor('customer_groups')
        ->assertJsonValidationErrorFor('action_type')
        ->assertJsonValidationErrorFor('discount_amount');
});

// ============================================================================
// Edit
// ============================================================================

it('should return the catalog rule edit page', function () {
    $catalogRule = createCatalogRule();

    $this->loginAsAdmin();

    get(route('admin.marketing.promotions.catalog_rules.edit', $catalogRule->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.catalog-rules.edit.title'));
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing catalog rule', function () {
    $catalogRule = createCatalogRule();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.promotions.catalog_rules.update', $catalogRule->id), [
        'name' => $name = fake()->words(3, true),
        'description' => $catalogRule->description,
        'channels' => [1],
        'customer_groups' => [1, 2, 3],
        'action_type' => 'by_fixed',
        'discount_amount' => 25,
        'starts_from' => '',
        'ends_till' => '',
    ])
        ->assertRedirect(route('admin.marketing.promotions.catalog_rules.index'));

    $this->assertDatabaseHas('catalog_rules', [
        'id' => $catalogRule->id,
        'name' => $name,
        'action_type' => 'by_fixed',
        'discount_amount' => 25,
    ]);
});

it('should persist boolean fields when storing a catalog rule', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.promotions.catalog_rules.store'), [
        'name' => fake()->words(3, true),
        'channels' => [1],
        'customer_groups' => [1, 2, 3],
        'action_type' => 'by_percent',
        'discount_amount' => 10,
        'status' => 1,
        'end_other_rules' => 1,
        'starts_from' => '',
        'ends_till' => '',
    ])
        ->assertRedirect(route('admin.marketing.promotions.catalog_rules.index'));

    $catalogRule = CatalogRule::latest('id')->first();

    expect($catalogRule->status)->toBeTrue()
        ->and($catalogRule->end_other_rules)->toBeTrue();
});

it('should persist disabled boolean fields when updating a catalog rule', function () {
    $catalogRule = createCatalogRule();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.promotions.catalog_rules.update', $catalogRule->id), [
        'name' => $catalogRule->name,
        'channels' => [1],
        'customer_groups' => [1, 2, 3],
        'action_type' => 'by_percent',
        'discount_amount' => 10,
        'status' => 0,
        'end_other_rules' => 0,
        'starts_from' => '',
        'ends_till' => '',
    ])
        ->assertRedirect(route('admin.marketing.promotions.catalog_rules.index'));

    $catalogRule->refresh();

    expect($catalogRule->status)->toBeFalse()
        ->and($catalogRule->end_other_rules)->toBeFalse();
});

it('should fail validation when required fields are missing on update', function () {
    $catalogRule = createCatalogRule();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.promotions.catalog_rules.update', $catalogRule->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('channels')
        ->assertJsonValidationErrorFor('customer_groups')
        ->assertJsonValidationErrorFor('action_type')
        ->assertJsonValidationErrorFor('discount_amount');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a catalog rule', function () {
    $catalogRule = createCatalogRule();

    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.promotions.catalog_rules.delete', $catalogRule->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.catalog-rules.delete-success'));

    $this->assertDatabaseMissing('catalog_rules', ['id' => $catalogRule->id]);
});
