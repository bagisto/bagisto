<?php

use Webkul\Marketing\Models\Template;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should return the email template index page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.email_templates.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.templates.index.title'))
        ->assertSeeText(trans('admin::app.marketing.communications.templates.index.create-btn'));
});

it('should return the create page of email template', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.email_templates.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.templates.create.title'))
        ->assertSeeText(trans('admin::app.marketing.communications.templates.create.save-btn'));
});

it('should fail the validation with errors when certain inputs are not provided when store in email template', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.email_templates.store'))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('status')
        ->assertJsonValidationErrorFor('content')
        ->assertUnprocessable();
});

it('should fail the validation with errors when certain inputs are not provided also status field not provided correctly when store in email template', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.email_templates.store'), [
        'status' => fake()->word(),
    ])
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('status')
        ->assertJsonValidationErrorFor('content')
        ->assertUnprocessable();
});

it('should store the newly create email template', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.email_templates.store', [
        'name'    => $name = fake()->name(),
        'status'  => $status = fake()->randomElement(['active', 'inactive', 'draft']),
        'content' => $content = substr(fake()->paragraph(), 0, 50),
    ]))
        ->assertRedirect(route('admin.marketing.communications.email_templates.index'))
        ->isRedirect();

    $this->assertModelWise([
        Template::class => [
            [
                'name'    => $name,
                'status'  => $status,
                'content' => $content,
            ],
        ],
    ]);
});

it('should edit the email template', function () {
    // Arrange.
    $marketingEmailTemplate = Template::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.email_templates.edit', $marketingEmailTemplate->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.templates.edit.title'))
        ->assertSeeText(trans('admin::app.marketing.communications.templates.edit.save-btn'))
        ->assertSeeText(trans('admin::app.marketing.communications.templates.create.content'));
});

it('should fail the validation with errors when certain inputs are not provided when update in email template', function () {
    // Arrange.
    $marketingEmailTemplate = Template::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.email_templates.update', $marketingEmailTemplate->id))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('status')
        ->assertJsonValidationErrorFor('content')
        ->assertUnprocessable();
});

it('should fail the validation with errors when certain inputs are not provided also status field not provided correctly when update in email template', function () {
    // Arrange.
    $marketingEmailTemplate = Template::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.email_templates.update', $marketingEmailTemplate->id))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('status')
        ->assertJsonValidationErrorFor('content')
        ->assertUnprocessable();
});

it('should update the existing the template', function () {
    // Arrange.
    $marketingEmailTemplate = Template::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.email_templates.update', $marketingEmailTemplate->id), $data = [
        'name'    => $marketingEmailTemplate->name,
        'status'  => fake()->randomElement(['active', 'inactive', 'draft']),
        'content' => substr(fake()->paragraph(), 0, 50),
    ])
        ->assertRedirect(route('admin.marketing.communications.email_templates.index'))
        ->isRedirect();

    $this->assertModelWise([
        Template::class => [
            [
                'name'    => $marketingEmailTemplate->name,
                'status'  => $data['status'],
                'content' => $data['content'],
            ],
        ],
    ]);
});

it('should delete the specified email template', function () {
    // Arrange.
    $marketingEmailTemplate = Template::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.communications.email_templates.delete', $marketingEmailTemplate->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.templates.delete-success'));
});
