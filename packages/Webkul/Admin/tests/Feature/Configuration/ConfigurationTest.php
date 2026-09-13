<?php

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

// ============================================================================
// Index
// ============================================================================

it('should return the configuration page of a known section', function () {
    $this->loginAsAdmin();

    get(route('admin.configuration.index', ['general', 'design']))
        ->assertOk();
});

it('should not found a section whose group is unknown', function () {
    $this->loginAsAdmin();

    get(route('admin.configuration.index', ['general-test', 'general']))
        ->assertNotFound();
});

it('should not found a section unknown within a known group', function () {
    $this->loginAsAdmin();

    get(route('admin.configuration.index', ['general', 'general-test']))
        ->assertNotFound();
});

it('should list every section when no section is asked for', function () {
    $this->loginAsAdmin();

    get(route('admin.configuration.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.configuration.index.title'));
});

it('should deny guest access to the configuration page', function () {
    get(route('admin.configuration.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store — Shipping And Payment Guards
// ============================================================================

it('should refuse a shipping configuration that disables every carrier', function () {
    $this->loginAsAdmin();

    $title = fake()->unique()->sentence();

    postJson(route('admin.configuration.store', ['sales', 'carriers']), [
        'sales' => [
            'carriers' => [
                'flatrate' => ['active' => 0, 'title' => $title],
                'free' => ['active' => 0],
            ],
        ],
    ])
        ->assertRedirect()
        ->assertSessionHas('error', trans('admin::app.configuration.index.enable-at-least-one-shipping'));

    $this->assertDatabaseMissing('core_config', [
        'code' => 'sales.carriers.flatrate.title',
        'value' => $title,
    ]);
});

it('should save a shipping configuration that keeps one carrier enabled', function () {
    $this->loginAsAdmin();

    $title = fake()->unique()->sentence();

    postJson(route('admin.configuration.store', ['sales', 'carriers']), [
        'sales' => [
            'carriers' => [
                'flatrate' => ['active' => 1, 'title' => $title],
                'free' => ['active' => 0],
            ],
        ],
    ])
        ->assertRedirect()
        ->assertSessionHas('success', trans('admin::app.configuration.index.save-message'));

    $this->assertDatabaseHas('core_config', [
        'code' => 'sales.carriers.flatrate.title',
        'value' => $title,
    ]);
});

it('should refuse a payment configuration that disables every method', function () {
    $this->loginAsAdmin();

    $title = fake()->unique()->sentence();

    postJson(route('admin.configuration.store', ['sales', 'payment_methods']), [
        'sales' => [
            'payment_methods' => [
                'cashondelivery' => ['active' => 0, 'title' => $title],
                'moneytransfer' => ['active' => 0],
            ],
        ],
    ])
        ->assertRedirect()
        ->assertSessionHas('error', trans('admin::app.configuration.index.enable-at-least-one-payment'));

    $this->assertDatabaseMissing('core_config', [
        'code' => 'sales.payment_methods.cashondelivery.title',
        'value' => $title,
    ]);
});

it('should save a payment configuration that keeps one method enabled', function () {
    $this->loginAsAdmin();

    $title = fake()->unique()->sentence();

    postJson(route('admin.configuration.store', ['sales', 'payment_methods']), [
        'sales' => [
            'payment_methods' => [
                'cashondelivery' => ['active' => 1, 'title' => $title],
                'moneytransfer' => ['active' => 0],
            ],
        ],
    ])
        ->assertRedirect()
        ->assertSessionHas('success', trans('admin::app.configuration.index.save-message'));

    $this->assertDatabaseHas('core_config', [
        'code' => 'sales.payment_methods.cashondelivery.title',
        'value' => $title,
    ]);
});
