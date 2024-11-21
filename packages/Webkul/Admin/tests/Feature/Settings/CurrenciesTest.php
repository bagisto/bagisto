<?php

use Illuminate\Support\Arr;
use Webkul\Core\Models\Currency;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should returns the currencies index page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.settings.currencies.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.currencies.index.title'))
        ->assertSeeText(trans('admin::app.settings.currencies.index.create-btn'));
});

it('should fail the validation with errors when certain field not provided when store the currencies', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.settings.currencies.store'))
        ->assertJsonValidationErrorFor('code')
        ->assertJsonValidationErrorFor('name')
        ->assertUnprocessable();
});

it('should store the newly created currencies', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.settings.currencies.store'), $data = [
        'code'              => fake()->randomElement(['EUR', 'GBP', 'JPY', 'AUD', 'CHF', 'CAD', 'CNY', 'BRL']),
        'name'              => fake()->name(),
        'symbol'            => fake()->randomElement(['€', '£', '¥', 'A$', 'CHF', 'C$', '¥', 'R$']),
        'decimal'           => rand(1, 4),
        'group_separator'   => '-',
        'decimal_separator' => '-',
        'currency_position' => fake()->randomElement(['left', 'left_with_space', 'right', 'right_with_space']),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.currencies.index.create-success'));

    $this->assertModelWise([
        Currency::class => [
            $data,
        ],
    ]);
});

it('should return the currencies for edit', function () {
    // Arrange.
    $currency = Currency::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.settings.currencies.edit', $currency->id))
        ->assertOk()
        ->assertJsonFragment($currency->toArray());
});

it('should fail the validation with errors when certain field not provided when update the currencies', function () {
    // Arrange.
    $currency = Currency::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.settings.currencies.update'), [
        'id' => $currency->id,
    ])
        ->assertJsonValidationErrorFor('name')
        ->assertUnprocessable();
});

it('should update the specified currency', function () {
    // Arrange.
    $currency = Currency::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.settings.currencies.update'), $data = [
        'id'   => $currency->id,
        'name' => fake()->name(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.currencies.index.update-success'));

    $this->assertModelWise([
        Currency::class => [
            [
                ...Arr::except($currency->toArray(), ['updated_at', 'created_at']),
                ...$data,
            ],
        ],
    ]);
});

it('should delete the currencies', function () {
    // Arrange.
    $currency = Currency::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.settings.currencies.delete', $currency->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.currencies.index.delete-success'));

    $this->assertDatabaseMissing('currencies', [
        'id' => $currency->id,
    ]);
});
