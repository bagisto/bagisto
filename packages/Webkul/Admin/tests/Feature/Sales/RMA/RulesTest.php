<?php

use Webkul\RMA\Models\RMARule;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the RMA rules index page', function () {
    $this->loginAsAdmin();

    get(route('admin.sales.rma.rules.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.rma.rules.index.title'));
});

it('should deny guest access to the RMA rules index page', function () {
    get(route('admin.sales.rma.rules.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created RMA rule', function () {
    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.rules.store'), [
        'name' => $name = fake()->words(3, true),
        'description' => fake()->sentence(),
        'status' => 1,
        'return_period' => 30,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.rma.rules.create.success'));

    $this->assertDatabaseHas('rma_rules', [
        'name' => $name,
        'status' => true,
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.rules.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('status')
        ->assertJsonValidationErrorFor('description');
});

// ============================================================================
// Edit
// ============================================================================

it('should return RMA rule details for edit', function () {
    $rule = RMARule::create([
        'name' => fake()->words(3, true),
        'description' => fake()->sentence(),
        'status' => true,
    ]);

    $this->loginAsAdmin();

    get(route('admin.sales.rma.rules.edit', $rule->id))
        ->assertOk();
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing RMA rule', function () {
    $rule = RMARule::create([
        'name' => fake()->words(3, true),
        'description' => fake()->sentence(),
        'status' => true,
    ]);

    $this->loginAsAdmin();

    putJson(route('admin.sales.rma.rules.update', $rule->id), [
        'name' => $name = fake()->words(3, true),
        'description' => fake()->sentence(),
        'status' => 1,
        'return_period' => 15,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.rma.rules.edit.success'));

    $this->assertDatabaseHas('rma_rules', [
        'id' => $rule->id,
        'name' => $name,
    ]);
});

// ============================================================================
// Delete
// ============================================================================

it('should delete an RMA rule', function () {
    $rule = RMARule::create([
        'name' => fake()->words(3, true),
        'description' => fake()->sentence(),
        'status' => true,
    ]);

    $this->loginAsAdmin();

    deleteJson(route('admin.sales.rma.rules.delete', $rule->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.rma.rules.index.datagrid.delete-success'));

    $this->assertDatabaseMissing('rma_rules', ['id' => $rule->id]);
});

// ============================================================================
// Mass Delete
// ============================================================================

it('should mass delete RMA rules', function () {
    $rules = collect([
        RMARule::create(['name' => fake()->words(3, true), 'description' => fake()->sentence(), 'status' => true]),
        RMARule::create(['name' => fake()->words(3, true), 'description' => fake()->sentence(), 'status' => true]),
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.rules.mass-delete'), [
        'indices' => $rules->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.rma.rules.index.datagrid.mass-delete-success'));

    foreach ($rules as $rule) {
        $this->assertDatabaseMissing('rma_rules', ['id' => $rule->id]);
    }
});
