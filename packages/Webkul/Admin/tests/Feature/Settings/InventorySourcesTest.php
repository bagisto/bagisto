<?php

use Webkul\Inventory\Models\InventorySource;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should returns the inventory sources index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.inventory_sources.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.inventory-sources.index.title'))
        ->assertSeeText(trans('admin::app.settings.inventory-sources.index.create-btn'));
});

it('should return the inventory sources create page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.inventory_sources.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.inventory-sources.create.add-title'))
        ->assertSeeText(trans('admin::app.marketing.communications.campaigns.create.back-btn'));
});

it('should fail the validation with errors when certain field not provided when store the inventory sources', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.inventory_sources.store'))
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('contact_name')
        ->assertJsonValidationErrorFor('contact_email')
        ->assertJsonValidationErrorFor('contact_number')
        ->assertJsonValidationErrorFor('street')
        ->assertJsonValidationErrorFor('country')
        ->assertJsonValidationErrorFor('state')
        ->assertJsonValidationErrorFor('city')
        ->assertJsonValidationErrorFor('postcode')
        ->assertUnprocessable();
});

it('should store the newly created inventory sources', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.inventory_sources.store'), [
        'code'           => $code = strtolower(fake()->word),
        'name'           => $name = fake()->name(),
        'priority'       => $priority = rand(1, 10),
        'contact_number' => $contactNumber = rand(1111111111, 9999999999),
        'contact_email'  => $contactEmail = fake()->email(),
        'latitude'       => fake()->latitude(),
        'longitude'      => fake()->longitude(),
        'contact_name'   => fake()->unique()->regexify('[A-Z0-9]{10}'),
        'street'         => fake()->streetName(),
        'country'        => preg_replace('/[^a-zA-Z0-9]+/', '', fake()->country()),
        'state'          => fake()->state(),
        'city'           => fake()->city(),
        'postcode'       => fake()->postcode(),
    ])
        ->assertRedirect(route('admin.settings.inventory_sources.index'))
        ->isRedirection();

    $this->assertModelWise([
        InventorySource::class => [
            [
                'code'           => $code,
                'name'           => $name,
                'priority'       => $priority,
                'contact_email'  => $contactEmail,
                'contact_number' => $contactNumber,
            ],
        ],
    ]);
});

it('should return the edit of the inventory sources', function () {
    // Arrange
    $inventorySource = InventorySource::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.inventory_sources.edit', $inventorySource->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.inventory-sources.edit.title'))
        ->assertSeeText(trans('admin::app.settings.inventory-sources.edit.back-btn'))
        ->assertSeeText(trans('admin::app.settings.inventory-sources.edit.save-btn'));
});

it('should fail the validation with errors when certain field not provided when update the inventory sources', function () {
    // Arrange
    $inventorySources = InventorySource::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.settings.inventory_sources.update', $inventorySources->id))
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('contact_name')
        ->assertJsonValidationErrorFor('contact_email')
        ->assertJsonValidationErrorFor('contact_number')
        ->assertJsonValidationErrorFor('street')
        ->assertJsonValidationErrorFor('country')
        ->assertJsonValidationErrorFor('state')
        ->assertJsonValidationErrorFor('city')
        ->assertJsonValidationErrorFor('postcode')
        ->assertUnprocessable();
});

it('should update the inventory sources', function () {
    // Arrange
    $inventorySources = InventorySource::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.settings.inventory_sources.update', $inventorySources->id), [
        'code'           => $code = strtolower(fake()->regexify('/^[a-zA-Z]+[a-zA-Z0-9_]+$/')),
        'name'           => $name = fake()->name(),
        'priority'       => $priority = rand(1, 10),
        'contact_number' => $contactNumber = rand(1111111111, 9999999999),
        'contact_email'  => $contactEmail = fake()->email(),
        'latitude'       => fake()->latitude(),
        'longitude'      => fake()->longitude(),
        'contact_name'   => fake()->unique()->regexify('[A-Z0-9]{10}'),
        'street'         => fake()->streetName(),
        'country'        => preg_replace("/[^a-zA-Z0-9\s]/", '', fake()->country()),
        'state'          => fake()->state(),
        'city'           => fake()->city(),
        'postcode'       => fake()->postcode(),
    ])
        ->assertRedirect(route('admin.settings.inventory_sources.index'))
        ->isRedirection();

    $this->assertModelWise([
        InventorySource::class => [
            [
                'code'           => $code,
                'name'           => $name,
                'priority'       => $priority,
                'contact_email'  => $contactEmail,
                'contact_number' => $contactNumber,
            ],
        ],
    ]);
});

it('should delete the inventory source', function () {
    // Arrange
    $inventorySource = InventorySource::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.settings.inventory_sources.delete', $inventorySource->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.inventory-sources.delete-success'));
});
