<?php

use Webkul\RMA\Models\RMAStatus;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the RMA statuses index page', function () {
    $this->loginAsAdmin();

    get(route('admin.sales.rma.statuses.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.rma.rma-status.index.title'));
});

it('should deny guest access to the RMA statuses index page', function () {
    get(route('admin.sales.rma.statuses.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created RMA status', function () {
    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.statuses.store'), [
        'title' => $title = fake()->unique()->words(3, true),
        'status' => 1,
        'color' => '#ff0000',
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.rma.rma-status.create.success'));

    $this->assertDatabaseHas('rma_statuses', [
        'title' => $title,
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.statuses.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('title')
        ->assertJsonValidationErrorFor('status');
});

// ============================================================================
// Edit
// ============================================================================

it('should return RMA status details for edit', function () {
    $status = RMAStatus::create([
        'title' => fake()->unique()->words(3, true),
        'status' => true,
    ]);

    $this->loginAsAdmin();

    get(route('admin.sales.rma.statuses.edit', $status->id))
        ->assertOk();
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing RMA status', function () {
    $status = RMAStatus::create([
        'title' => fake()->unique()->words(3, true),
        'status' => true,
    ]);

    $this->loginAsAdmin();

    putJson(route('admin.sales.rma.statuses.update', $status->id), [
        'title' => $title = fake()->unique()->words(3, true),
        'status' => 1,
        'color' => '#00ff00',
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.rma.rma-status.edit.success'));

    $this->assertDatabaseHas('rma_statuses', [
        'id' => $status->id,
        'title' => $title,
    ]);
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a non-default RMA status', function () {
    $status = RMAStatus::create([
        'title' => fake()->unique()->words(3, true),
        'status' => true,
        'default' => 0,
    ]);

    $this->loginAsAdmin();

    deleteJson(route('admin.sales.rma.statuses.delete', $status->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.rma.rma-status.index.datagrid.delete-success'));

    $this->assertDatabaseMissing('rma_statuses', ['id' => $status->id]);
});

// ============================================================================
// Mass Delete
// ============================================================================

it('should mass delete non-default RMA statuses', function () {
    $statuses = collect([
        RMAStatus::create(['title' => fake()->unique()->words(3, true), 'status' => true, 'default' => 0]),
        RMAStatus::create(['title' => fake()->unique()->words(3, true), 'status' => true, 'default' => 0]),
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.statuses.mass-delete'), [
        'indices' => $statuses->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.rma.rma-status.index.datagrid.mass-delete-success'));

    foreach ($statuses as $status) {
        $this->assertDatabaseMissing('rma_statuses', ['id' => $status->id]);
    }
});
