<?php

use Webkul\CatalogRule\Models\CatalogRule;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should returns the catalog rule page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.marketing.promotions.catalog_rules.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.catalog-rules.index.title'))
        ->assertSeeText(trans('admin::app.marketing.promotions.catalog-rules.index.create-btn'));
});

it('should returns the create page of catalog rules', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.marketing.promotions.catalog_rules.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.catalog-rules.create.title'))
        ->assertSeeText(trans('admin::app.marketing.promotions.catalog-rules.create.back-btn'))
        ->assertSeeText(trans('admin::app.marketing.promotions.catalog-rules.create.save-btn'));
});

it('should fail the validation with errors when certain field not provided when store the catalog rule', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.marketing.promotions.catalog_rules.store'))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('channels')
        ->assertJsonValidationErrorFor('customer_groups')
        ->assertJsonValidationErrorFor('action_type')
        ->assertJsonValidationErrorFor('discount_amount')
        ->assertUnprocessable();
});

it('should store the newly created catalog rule', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.marketing.promotions.catalog_rules.store', [
        'name'        => $name = fake()->name(),
        'description' => $description = substr(fake()->paragraph(), 0, 50),
        'channels'    => [
            1,
        ],

        'customer_groups' => [
            1,
            2,
            3,
        ],

        'status'          => 1,
        'action_type'     => 'by_percent',
        'discount_amount' => 0,
        'starts_from'     => '',
        'ends_till'       => '',
    ]))
        ->assertRedirect(route('admin.marketing.promotions.catalog_rules.index'))
        ->isRedirection();

    $this->assertModelWise([
        CatalogRule::class => [
            [
                'action_type'     => 'by_percent',
                'description'     => $description,
                'discount_amount' => 0,
                'name'            => $name,
                'status'          => 1,
            ],
        ],
    ]);
});

it('should returns the edit page of catalog rules', function () {
    // Arrange.
    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);
        $catalogRule->customer_groups()->sync([1, 2, 3]);
    })->create();

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.marketing.promotions.catalog_rules.edit', $catalogRule->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.catalog-rules.edit.title'))
        ->assertSeeText(trans('admin::app.marketing.promotions.catalog-rules.edit.save-btn'));
});

it('should fail the validation with errors when certain field not provided when update the catalog rule', function () {
    // Arrange.
    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);
        $catalogRule->customer_groups()->sync([1, 2, 3]);
    })->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.marketing.promotions.catalog_rules.update', $catalogRule))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('channels')
        ->assertJsonValidationErrorFor('customer_groups')
        ->assertJsonValidationErrorFor('action_type')
        ->assertJsonValidationErrorFor('discount_amount')
        ->assertUnprocessable();
});

it('should update the catalog rule', function () {
    // Arrange.
    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);
        $catalogRule->customer_groups()->sync([1, 2, 3]);
    })->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.marketing.promotions.catalog_rules.update', $catalogRule->id), [
        'name'        => $catalogRule->name,
        'description' => $catalogRule->description,
        'channels'    => [
            1,
        ],

        'customer_groups' => [
            1,
            2,
            3,
        ],

        'action_type'     => 'by_percent',
        'discount_amount' => 0,
        'starts_from'     => '',
        'ends_till'       => '',
    ])
        ->assertRedirect(route('admin.marketing.promotions.catalog_rules.index'))
        ->isRedirection();

    $this->assertModelWise([
        CatalogRule::class => [
            [
                'id'          => $catalogRule->id,
                'name'        => $catalogRule->name,
                'description' => $catalogRule->description,
                'action_type' => 'by_percent',
            ],
        ],
    ]);
});

it('should delete a specific catalog rule', function () {
    // Arrange.
    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);
        $catalogRule->customer_groups()->sync([1, 2, 3]);
    })->create();

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.promotions.catalog_rules.delete', $catalogRule->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.promotions.catalog-rules.delete-success'));
});
