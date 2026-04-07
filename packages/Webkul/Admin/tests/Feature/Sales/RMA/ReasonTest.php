<?php

use Webkul\RMA\Models\RMAReason;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the RMA reasons index page', function () {
    $this->loginAsAdmin();

    get(route('admin.sales.rma.reasons.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.rma.reasons.index.title'));
});

it('should deny guest access to the RMA reasons index page', function () {
    get(route('admin.sales.rma.reasons.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created RMA reason', function () {
    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.reasons.store'), [
        'title' => $title = fake()->words(3, true),
        'status' => 1,
        'position' => 1,
        'resolution_type' => ['refund'],
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.rma.reasons.create.success'));

    $this->assertDatabaseHas('rma_reasons', [
        'title' => $title,
        'status' => true,
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.reasons.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('title')
        ->assertJsonValidationErrorFor('status')
        ->assertJsonValidationErrorFor('position')
        ->assertJsonValidationErrorFor('resolution_type');
});

// ============================================================================
// Edit
// ============================================================================

it('should return RMA reason details for edit', function () {
    $reason = RMAReason::create([
        'title' => fake()->words(3, true),
        'status' => true,
        'position' => 1,
    ]);

    $this->loginAsAdmin();

    get(route('admin.sales.rma.reasons.edit', $reason->id))
        ->assertOk();
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing RMA reason', function () {
    $reason = RMAReason::create([
        'title' => fake()->words(3, true),
        'status' => true,
        'position' => 1,
    ]);

    $this->loginAsAdmin();

    putJson(route('admin.sales.rma.reasons.update', $reason->id), [
        'title' => $title = fake()->words(3, true),
        'status' => 1,
        'position' => 2,
        'resolution_type' => ['exchange'],
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.rma.reasons.edit.success'));

    $this->assertDatabaseHas('rma_reasons', [
        'id' => $reason->id,
        'title' => $title,
    ]);
});

// ============================================================================
// Delete
// ============================================================================

it('should delete an RMA reason', function () {
    $reason = RMAReason::create([
        'title' => fake()->words(3, true),
        'status' => true,
        'position' => 1,
    ]);

    $this->loginAsAdmin();

    deleteJson(route('admin.sales.rma.reasons.delete', $reason->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.rma.reasons.index.datagrid.delete-success'));

    $this->assertDatabaseMissing('rma_reasons', ['id' => $reason->id]);
});

// ============================================================================
// Mass Delete
// ============================================================================

it('should mass delete RMA reasons', function () {
    $reasons = collect([
        RMAReason::create(['title' => fake()->words(3, true), 'status' => true, 'position' => 1]),
        RMAReason::create(['title' => fake()->words(3, true), 'status' => true, 'position' => 2]),
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.reasons.mass-delete'), [
        'indices' => $reasons->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.rma.reasons.index.datagrid.mass-delete-success'));

    foreach ($reasons as $reason) {
        $this->assertDatabaseMissing('rma_reasons', ['id' => $reason->id]);
    }
});
