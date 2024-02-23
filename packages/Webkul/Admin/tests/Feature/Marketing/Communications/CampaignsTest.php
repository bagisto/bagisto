<?php

use Webkul\Marketing\Models\Campaign;
use Webkul\Marketing\Models\Event;
use Webkul\Marketing\Models\Template;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should return the campaign index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.campaigns.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.campaigns.index.title'))
        ->assertSeeText(trans('admin::app.marketing.communications.campaigns.index.create-btn'));
});

it('should returns the create page of campaigns', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.campaigns.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.campaigns.create.title'))
        ->assertSeeText(trans('admin::app.marketing.communications.campaigns.create.back-btn'));
});

it('should fail the validation with errors when certain inputs are not provided when store in campaigns', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.campaigns.store'))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('subject')
        ->assertJsonValidationErrorFor('marketing_template_id')
        ->assertJsonValidationErrorFor('marketing_event_id')
        ->assertJsonValidationErrorFor('channel_id')
        ->assertJsonValidationErrorFor('customer_group_id')
        ->assertUnprocessable();
});

it('should fail the validation with errors when certain inputs are not provided and if provided bad status type when store in campaigns', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.campaigns.store'), [
        'status' => fake()->word(),
    ])
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('subject')
        ->assertJsonValidationErrorFor('marketing_template_id')
        ->assertJsonValidationErrorFor('marketing_event_id')
        ->assertJsonValidationErrorFor('channel_id')
        ->assertJsonValidationErrorFor('customer_group_id')
        ->assertJsonValidationErrorFor('status')
        ->assertUnprocessable();
});

it('should store the newly created campaigns', function () {
    // Arrange
    $emailTemplate = Template::factory()->create();

    $event = Event::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.campaigns.store'), [
        'name'                  => $name = fake()->name(),
        'subject'               => $subject = fake()->title(),
        'marketing_template_id' => $emailTemplate->id,
        'marketing_event_id'    => $event->id,
        'channel_id'            => 1,
        'customer_group_id'     => $customerGroupId = rand(1, 3),
    ])
        ->assertRedirect(route('admin.marketing.communications.campaigns.index'))
        ->isRedirect();

    $this->assertModelWise([
        Campaign::class => [
            [
                'name'                  => $name,
                'subject'               => $subject,
                'marketing_template_id' => $emailTemplate->id,
                'marketing_event_id'    => $event->id,
                'channel_id'            => 1,
                'customer_group_id'     => $customerGroupId,
            ],
        ],
    ]);
});

it('should show the edit page of campaigns', function () {
    // Arrange
    $campaign = Campaign::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.campaigns.edit', $campaign->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.campaigns.edit.title'))
        ->assertSeeText(trans('admin::app.marketing.communications.campaigns.edit.back-btn'));
});

it('should fail the validation with errors when certain inputs are not provided when update in campaigns', function () {
    // Arrange
    $campaign = Campaign::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.campaigns.update', $campaign->id))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('subject')
        ->assertJsonValidationErrorFor('marketing_template_id')
        ->assertJsonValidationErrorFor('marketing_event_id')
        ->assertJsonValidationErrorFor('channel_id')
        ->assertJsonValidationErrorFor('customer_group_id')
        ->assertUnprocessable();
});

it('should fail the validation with errors when certain inputs are not provided and if provided bad status type when update in campaigns', function () {
    // Arrange
    $campaign = Campaign::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.campaigns.update', $campaign->id), [
        'status' => fake()->word(),
    ])
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('subject')
        ->assertJsonValidationErrorFor('marketing_template_id')
        ->assertJsonValidationErrorFor('marketing_event_id')
        ->assertJsonValidationErrorFor('channel_id')
        ->assertJsonValidationErrorFor('customer_group_id')
        ->assertJsonValidationErrorFor('status')
        ->assertUnprocessable();
});

it('should update specified the campaigns', function () {
    // Arrange
    $campaign = Campaign::factory()->create(['marketing_template_id' => Template::factory()->create()->id]);

    $event = Event::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.campaigns.edit', $campaign->id), [
        'name'                  => $campaign->name,
        'subject'               => $subject = fake()->title(),
        'marketing_template_id' => $campaign->marketing_template_id,
        'marketing_event_id'    => $event->id,
        'channel_id'            => 1,
        'customer_group_id'     => $customerGroupId = rand(1, 3),
    ])
        ->assertRedirect(route('admin.marketing.communications.campaigns.index'))
        ->isRedirect();

    $this->assertModelWise([
        Campaign::class => [
            [
                'id'                    => $campaign->id,
                'name'                  => $campaign->name,
                'subject'               => $subject,
                'marketing_template_id' => $campaign->marketing_template_id,
                'marketing_event_id'    => $event->id,
                'channel_id'            => 1,
                'customer_group_id'     => $customerGroupId,
            ],
        ],
    ]);
});

it('should delete the campaign', function () {
    // Arrange
    $campaign = Campaign::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.communications.campaigns.delete', $campaign->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.campaigns.delete-success'));

    $this->assertDatabaseMissing('marketing_campaigns', [
        'id' => $campaign->id,
    ]);
});
