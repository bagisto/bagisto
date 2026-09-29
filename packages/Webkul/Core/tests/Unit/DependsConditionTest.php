<?php

use Webkul\Core\SystemConfig\DependsCondition;

// ============================================================================
// Reading The Condition
// ============================================================================

it('should permit a field that declares no condition', function () {
    expect((new DependsCondition(null))->permits(true, '0'))->toBeTrue()
        ->and((new DependsCondition(''))->permits(true, '0'))->toBeTrue();
});

it('should permit a field whose sibling the save does not carry', function () {
    expect((new DependsCondition('enabled:1'))->permits(false))->toBeTrue();
});

it('should name the sibling field the condition reads', function () {
    expect((new DependsCondition('auth_type:api_key,cloud_api_key'))->fieldName())->toBe('auth_type');
});

// ============================================================================
// Matching A Value
// ============================================================================

it('should match a boolean condition against what the form posts for it', function (string $condition, mixed $value, bool $met) {
    expect((new DependsCondition($condition))->permits(true, $value))->toBe($met);
})->with([
    'on, written as 1' => ['enabled:1', '1', true],
    'off, written as 1' => ['enabled:1', '0', false],
    'on, written as true' => ['enabled:true', '1', true],
    'off, written as true' => ['enabled:true', '0', false],
    'on, written as 0' => ['enabled:0', '0', true],
    'off, written as 0' => ['enabled:0', '1', false],
    'integer value' => ['enabled:1', 1, true],
]);

it('should match a select condition against one of the values it lists', function (mixed $value, bool $met) {
    expect((new DependsCondition('auth_type:api_key,cloud_api_key'))->permits(true, $value))->toBe($met);
})->with([
    'first listed' => ['api_key', true],
    'second listed' => ['cloud_api_key', true],
    'not listed' => ['basic', false],
    'blank' => ['', false],
]);
