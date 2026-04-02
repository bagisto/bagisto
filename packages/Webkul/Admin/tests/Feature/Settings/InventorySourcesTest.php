<?php

use Webkul\Inventory\Models\InventorySource;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the inventory sources index page', function () {
    $this->loginAsAdmin();

    get(route('admin.settings.inventory_sources.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.inventory-sources.index.title'))
        ->assertSeeText(trans('admin::app.settings.inventory-sources.index.create-btn'));
});

it('should deny guest access to the inventory sources index page', function () {
    get(route('admin.settings.inventory_sources.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Create
// ============================================================================

it('should return the inventory source create page', function () {
    $this->loginAsAdmin();

    get(route('admin.settings.inventory_sources.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.inventory-sources.create.add-title'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created inventory source', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.inventory_sources.store'), [
        'code' => $code = fake()->lexify('code????'),
        'name' => $name = fake()->name(),
        'priority' => rand(1, 10),
        'contact_number' => rand(1111111111, 9999999999),
        'contact_email' => fake()->email(),
        'latitude' => fake()->latitude(),
        'longitude' => fake()->longitude(),
        'contact_name' => fake()->name(),
        'street' => fake()->streetName(),
        'country' => preg_replace('/[^a-zA-Z0-9]+/', '', fake()->country()),
        'state' => fake()->state(),
        'city' => fake()->city(),
        'postcode' => fake()->postcode(),
    ])
        ->assertRedirect(route('admin.settings.inventory_sources.index'));

    $this->assertDatabaseHas('inventory_sources', [
        'code' => $code,
        'name' => $name,
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.inventory_sources.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('contact_name')
        ->assertJsonValidationErrorFor('contact_email')
        ->assertJsonValidationErrorFor('contact_number')
        ->assertJsonValidationErrorFor('street')
        ->assertJsonValidationErrorFor('country')
        ->assertJsonValidationErrorFor('state')
        ->assertJsonValidationErrorFor('city')
        ->assertJsonValidationErrorFor('postcode');
});

// ============================================================================
// Edit
// ============================================================================

it('should return the inventory source edit page', function () {
    $inventorySource = InventorySource::factory()->create();

    $this->loginAsAdmin();

    get(route('admin.settings.inventory_sources.edit', $inventorySource->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.inventory-sources.edit.title'));
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing inventory source', function () {
    $inventorySource = InventorySource::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.inventory_sources.update', $inventorySource->id), [
        'code' => $code = strtolower(fake()->lexify('code????')),
        'name' => $name = fake()->name(),
        'priority' => rand(1, 10),
        'contact_number' => rand(1111111111, 9999999999),
        'contact_email' => fake()->email(),
        'latitude' => fake()->latitude(),
        'longitude' => fake()->longitude(),
        'contact_name' => fake()->name(),
        'street' => fake()->streetName(),
        'country' => preg_replace('/[^a-zA-Z0-9\s]/', '', fake()->country()),
        'state' => fake()->state(),
        'city' => fake()->city(),
        'postcode' => fake()->postcode(),
    ])
        ->assertRedirect(route('admin.settings.inventory_sources.index'));

    $this->assertDatabaseHas('inventory_sources', [
        'id' => $inventorySource->id,
        'code' => $code,
        'name' => $name,
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $inventorySource = InventorySource::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.inventory_sources.update', $inventorySource->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('contact_name')
        ->assertJsonValidationErrorFor('contact_email')
        ->assertJsonValidationErrorFor('contact_number')
        ->assertJsonValidationErrorFor('street')
        ->assertJsonValidationErrorFor('country')
        ->assertJsonValidationErrorFor('state')
        ->assertJsonValidationErrorFor('city')
        ->assertJsonValidationErrorFor('postcode');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete an inventory source', function () {
    $inventorySource = InventorySource::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.settings.inventory_sources.delete', $inventorySource->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.inventory-sources.delete-success'));

    $this->assertDatabaseMissing('inventory_sources', ['id' => $inventorySource->id]);
});
