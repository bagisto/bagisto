<?php

use Webkul\Marketing\Models\Event;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should return the events index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.events.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.events.index.title'))
        ->assertSeeText(trans('admin::app.marketing.communications.events.index.create-btn'));
});

it('should fail the validation with errors when certain inputs are not provided when store in events', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.events.store'))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('date')
        ->assertUnprocessable();
});

it('should store the newly create event', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.events.store', [
        'name'        => $name = fake()->name,
        'description' => $description = substr(fake()->paragraph(), 0, 50),
        'date'        => $date = fake()->date(),
    ]))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.events.index.create.success'));

    $this->assertModelWise([
        Event::class => [
            [
                'name'        => $name,
                'description' => $description,
                'date'        => $date,
            ],
        ],
    ]);
});

it('should edit the events template', function () {
    // Arrange
    $event = Event::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.events.edit', $event->id))
        ->assertOk()
        ->assertJsonPath('id', $event->id)
        ->assertJsonPath('name', $event->name)
        ->assertJsonPath('description', $event->description)
        ->assertJsonPath('date', $event->date);
});

it('should fail the validation with errors when certain inputs are not provided when update in events', function () {
    // Arrange
    $event = Event::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.events.store', $event->id))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('date')
        ->assertUnprocessable();
});

it('should update the existing the events', function () {
    // Arrange
    $event = Event::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.events.update'), [
        'id'          => $event->id,
        'name'        => $event->name,
        'description' => $description = substr(fake()->paragraph(), 0, 50),
        'date'        => $date = fake()->date(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.events.index.edit.success'));

    $this->assertModelWise([
        Event::class => [
            [
                'id'          => $event->id,
                'name'        => $event->name,
                'description' => $description,
                'date'        => $date,
            ],
        ],
    ]);
});

it('should delete the specified events', function () {
    // Arrange
    $event = Event::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.communications.events.delete', $event->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.events.delete-success'));
});
