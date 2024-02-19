<?php

use Webkul\Core\Models\Currency;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should returns the currencies index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.currencies.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.currencies.index.title'))
        ->assertSeeText(trans('admin::app.settings.currencies.index.create-btn'));
});

it('should fail the validation with errors when certain field not provided when store the currencies', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.currencies.store'))
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name')
        ->assertUnprocessable();
});

it('should store the newly created currencies', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.currencies.store'), [
        'code' => $code = fake()->currencyCode(),
        'name' => $name = fake()->name(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.currencies.index.create-success'));

    $this->assertModelWise([
        Currency::class => [
            [
                'code' => $code,
                'name' => $name,
            ],
        ],
    ]);
});

it('should return the currencies for edit', function () {
    // Arrange
    $currency = Currency::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.currencies.edit', $currency->id))
        ->assertOk()
        ->assertJsonPath('id', $currency->id)
        ->assertJsonPath('code', $currency->code)
        ->assertJsonPath('name', $currency->name);
});

it('should fail the validation with errors when certain field not provided when update the currencies', function () {
    // Arrange
    $currency = Currency::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.settings.currencies.update'), [
        'id'   => $currency->id,
    ])
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name')
        ->assertUnprocessable();
});

it('should update the specified currency', function () {
    // Arrange
    $currency = Currency::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.settings.currencies.update'), [
        'id'   => $currency->id,
        'code' => $code = fake()->currencyCode(),
        'name' => $name = fake()->name(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.currencies.index.update-success'));

    $this->assertModelWise([
        Currency::class => [
            [
                'code' => $code,
                'name' => $name,
            ],
        ],
    ]);
});

it('should delete the currencies', function () {
    // Arrange
    $currency = Currency::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.settings.currencies.delete', $currency->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.currencies.index.delete-success'));

    $this->assertDatabaseMissing('currencies', [
        'id' => $currency->id,
    ]);
});
