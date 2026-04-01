<?php

use Webkul\Marketing\Models\Campaign;
use Webkul\Marketing\Models\Event;
use Webkul\Marketing\Models\Template;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the campaign index page', function () {
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.campaigns.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.campaigns.index.title'))
        ->assertSeeText(trans('admin::app.marketing.communications.campaigns.index.create-btn'));
});

it('should deny guest access to the campaign index page', function () {
    get(route('admin.marketing.communications.campaigns.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Create
// ============================================================================

it('should return the campaign create page', function () {
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.campaigns.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.campaigns.create.title'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created campaign', function () {
    $template = Template::factory()->create();
    $event = Event::factory()->create();

    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.campaigns.store'), $data = [
        'name' => fake()->words(3, true),
        'subject' => fake()->sentence(),
        'marketing_template_id' => $template->id,
        'marketing_event_id' => $event->id,
        'channel_id' => 1,
        'customer_group_id' => rand(1, 3),
    ])
        ->assertRedirect(route('admin.marketing.communications.campaigns.index'));

    $this->assertDatabaseHas('marketing_campaigns', [
        'name' => $data['name'],
        'subject' => $data['subject'],
        'marketing_template_id' => $template->id,
        'marketing_event_id' => $event->id,
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.campaigns.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('subject')
        ->assertJsonValidationErrorFor('marketing_template_id')
        ->assertJsonValidationErrorFor('marketing_event_id')
        ->assertJsonValidationErrorFor('channel_id')
        ->assertJsonValidationErrorFor('customer_group_id');
});

it('should fail validation when status is invalid on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.campaigns.store'), [
        'status' => fake()->word(),
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('status');
});

// ============================================================================
// Edit
// ============================================================================

it('should return the campaign edit page', function () {
    $campaign = Campaign::factory()->create();

    $this->loginAsAdmin();

    get(route('admin.marketing.communications.campaigns.edit', $campaign->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.campaigns.edit.title'));
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing campaign', function () {
    $campaign = Campaign::factory()->create();
    $event = Event::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.campaigns.update', $campaign->id), $data = [
        'name' => $campaign->name,
        'subject' => fake()->sentence(),
        'marketing_template_id' => $campaign->marketing_template_id,
        'marketing_event_id' => $event->id,
        'channel_id' => 1,
        'customer_group_id' => rand(1, 3),
    ])
        ->assertRedirect(route('admin.marketing.communications.campaigns.index'));

    $this->assertDatabaseHas('marketing_campaigns', [
        'id' => $campaign->id,
        'name' => $campaign->name,
        'subject' => $data['subject'],
        'marketing_event_id' => $event->id,
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $campaign = Campaign::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.campaigns.update', $campaign->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('subject')
        ->assertJsonValidationErrorFor('marketing_template_id')
        ->assertJsonValidationErrorFor('marketing_event_id')
        ->assertJsonValidationErrorFor('channel_id')
        ->assertJsonValidationErrorFor('customer_group_id');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a campaign', function () {
    $campaign = Campaign::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.communications.campaigns.delete', $campaign->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.campaigns.delete-success'));

    $this->assertDatabaseMissing('marketing_campaigns', ['id' => $campaign->id]);
});
