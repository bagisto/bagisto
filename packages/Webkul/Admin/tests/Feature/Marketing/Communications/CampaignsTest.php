<?php

use Webkul\Marketing\Models\Campaign;
use Webkul\Marketing\Models\Template;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

afterEach(function () {
    /**
     * Cleaning up rows which are created.
     */
    Campaign::query()->delete();
    Template::query()->delete();
});

it('should return the compaign index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.campaigns.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.campaigns.index.title'))
        ->assertSeeText(trans('admin::app.marketing.communications.campaigns.index.create-btn'));
});

it('should returns the create page of compaign', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.campaigns.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.campaigns.create.title'))
        ->assertSeeText(trans('admin::app.marketing.communications.campaigns.create.back-btn'));
});

it('should store the newly created compaigns', function () {
    // Arrange
    $emailTemplate = Template::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.marketing.communications.campaigns.store'), [
        'name'                  => $name = fake()->name(),
        'subject'               => $subject = fake()->title(),
        'marketing_template_id' => $emailTemplate->id,
    ])
        ->assertRedirect(route('admin.marketing.communications.campaigns.index'))
        ->isRedirect();

    $this->assertDatabaseHas('marketing_campaigns', [
        'name'                  => $name,
        'subject'               => $subject,
        'marketing_template_id' => $emailTemplate->id,
    ]);
});

it('should show the edit page of compaigns', function () {
    // Arrange
    $compaign = Campaign::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.marketing.communications.campaigns.edit', $compaign->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.communications.campaigns.edit.title'))
        ->assertSeeText(trans('admin::app.marketing.communications.campaigns.edit.back-btn'));
});

it('should update specified the compaigns', function () {
    // Arrange
    $campaign = Campaign::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.marketing.communications.campaigns.edit', $campaign->id), [
        'name'                  => $campaign->name,
        'subject'               => $subject = fake()->title(),
        'marketing_template_id' => $campaign->marketing_template_id,
    ])
        ->assertRedirect(route('admin.marketing.communications.campaigns.index'))
        ->isRedirect();

    $this->assertDatabaseHas('marketing_campaigns', [
        'id'                    => $campaign->id,
        'name'                  => $campaign->name,
        'subject'               => $subject,
        'marketing_template_id' => $campaign->marketing_template_id,
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
