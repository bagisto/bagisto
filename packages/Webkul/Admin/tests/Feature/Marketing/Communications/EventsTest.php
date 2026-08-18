<?php

use Webkul\Marketing\Models\Campaign;
use Webkul\Marketing\Models\Event;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should return the events index page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.events.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.events.index.title'))
        ->assertSeeText(trans('admin::app.marketing.communications.events.index.create-btn'));
});

it('should fail the validation with errors when certain inputs are not provided when store in events', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.events.store'))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('date')
        ->assertUnprocessable();
});

it('should store the newly create event', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.events.store', $data = [
        'name' => fake()->name(),
        'description' => substr(fake()->paragraph(), 0, 50),
        'date' => fake()->date(),
    ]))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.events.index.create.success'));

    $this->assertModelWise([
        Event::class => [
            [
                'name' => $data['name'],
                'description' => $data['description'],
                'date' => $data['date'],
            ],
        ],
    ]);
});

it('should edit the events template', function () {
    // Arrange.
    $event = Event::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.events.edit', $event->id))
        ->assertOk()
        ->assertJsonFragment($event->toArray());
});

it('should fail the validation with errors when certain inputs are not provided when update in events', function () {
    // Arrange.
    $event = Event::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.events.store', $event->id))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('date')
        ->assertUnprocessable();
});

it('should update the existing the events', function () {
    // Arrange.
    $event = Event::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.events.update'), [
        'id' => $event->id,
        'name' => $event->name,
        'description' => $description = substr(fake()->paragraph(), 0, 50),
        'date' => $date = fake()->date(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.events.index.edit.success'));

    $this->assertModelWise([
        Event::class => [
            [
                'id' => $event->id,
                'name' => $event->name,
                'description' => $description,
                'date' => $date,
            ],
        ],
    ]);
});

it('should delete the specified events', function () {
    // Arrange.
    $event = Event::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.communications.events.delete', $event->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.events.delete-success'));
});

it('should refuse to delete an event a campaign is using', function () {
    // Arrange.
    $event = Event::factory()->create();

    Campaign::factory()->create(['marketing_event_id' => $event->id]);

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.communications.events.delete', $event->id))
        ->assertStatus(400)
        ->assertJsonPath('message', trans('admin::app.marketing.communications.events.campaign-associate'));

    $this->assertDatabaseHas('marketing_events', ['id' => $event->id]);
});

it('should delete an event no campaign is using', function () {
    // Arrange.
    $event = Event::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.communications.events.delete', $event->id))
        ->assertOk();

    $this->assertDatabaseMissing('marketing_events', ['id' => $event->id]);
});
