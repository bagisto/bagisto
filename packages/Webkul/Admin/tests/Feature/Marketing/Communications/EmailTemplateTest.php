<?php

use Webkul\Marketing\Models\Template;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the email template index page', function () {
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.email_templates.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.templates.index.title'))
        ->assertSeeText(trans('admin::app.marketing.communications.templates.index.create-btn'));
});

it('should deny guest access to the email template index page', function () {
    get(route('admin.marketing.communications.email_templates.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Create
// ============================================================================

it('should return the email template create page', function () {
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.email_templates.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.templates.create.title'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created email template', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.email_templates.store'), [
        'name' => $name = fake()->words(3, true),
        'status' => $status = fake()->randomElement(['active', 'inactive', 'draft']),
        'content' => $content = fake()->sentence(),
    ])
        ->assertRedirect(route('admin.marketing.communications.email_templates.index'));

    $this->assertDatabaseHas('marketing_templates', [
        'name' => $name,
        'status' => $status,
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.email_templates.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('status')
        ->assertJsonValidationErrorFor('content');
});

it('should fail validation when status is invalid on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.email_templates.store'), [
        'name' => fake()->words(3, true),
        'status' => 'invalid_status',
        'content' => fake()->sentence(),
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('status');
});

// ============================================================================
// Edit
// ============================================================================

it('should return the email template edit page', function () {
    $template = Template::factory()->create();

    $this->loginAsAdmin();

    get(route('admin.marketing.communications.email_templates.edit', $template->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.templates.edit.title'));
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing email template', function () {
    $template = Template::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.email_templates.update', $template->id), [
        'name' => $template->name,
        'status' => $status = fake()->randomElement(['active', 'inactive', 'draft']),
        'content' => $content = fake()->sentence(),
    ])
        ->assertRedirect(route('admin.marketing.communications.email_templates.index'));

    $this->assertDatabaseHas('marketing_templates', [
        'id' => $template->id,
        'name' => $template->name,
        'status' => $status,
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $template = Template::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.email_templates.update', $template->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('status')
        ->assertJsonValidationErrorFor('content');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete an email template', function () {
    $template = Template::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.communications.email_templates.delete', $template->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.templates.delete-success'));

    $this->assertDatabaseMissing('marketing_templates', ['id' => $template->id]);
});
