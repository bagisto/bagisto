<?php

use Webkul\Marketing\Models\Event;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the events index page', function () {
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.events.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.events.index.title'))
        ->assertSeeText(trans('admin::app.marketing.communications.events.index.create-btn'));
});

it('should deny guest access to the events index page', function () {
    get(route('admin.marketing.communications.events.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created event', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.events.store'), $data = [
        'name' => fake()->words(3, true),
        'description' => fake()->sentence(),
        'date' => fake()->date(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.events.index.create.success'));

    $this->assertDatabaseHas('marketing_events', [
        'name' => $data['name'],
        'description' => $data['description'],
        'date' => $data['date'],
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.events.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('date');
});

// ============================================================================
// Edit
// ============================================================================

it('should return event details as JSON', function () {
    $event = Event::factory()->create();

    $this->loginAsAdmin();

    get(route('admin.marketing.communications.events.edit', $event->id))
        ->assertOk()
        ->assertJsonFragment(['name' => $event->name]);
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing event', function () {
    $event = Event::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.events.update'), [
        'id' => $event->id,
        'name' => $event->name,
        'description' => $description = fake()->sentence(),
        'date' => $date = fake()->date(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.events.index.edit.success'));

    $this->assertDatabaseHas('marketing_events', [
        'id' => $event->id,
        'name' => $event->name,
        'description' => $description,
        'date' => $date,
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.events.update'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('date');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete an event', function () {
    $event = Event::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.communications.events.delete', $event->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.events.delete-success'));

    $this->assertDatabaseMissing('marketing_events', ['id' => $event->id]);
});
