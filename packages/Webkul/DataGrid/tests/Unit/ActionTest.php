<?php

use Webkul\DataGrid\Action;
use Webkul\DataGrid\MassAction;

// ============================================================================
// Action
// ============================================================================

it('should show an action that has no condition', function () {
    $action = new Action(index: 'edit', icon: 'icon-edit', title: 'Edit', method: 'GET', url: fn ($row) => '/edit');

    expect($action->isVisible((object) []))->toBeTrue();
});

it('should decide visibility from the condition closure cast to a boolean', function (mixed $status, bool $visible) {
    $action = new Action(index: '', icon: '', title: 'Delete', method: 'DELETE', url: fn ($row) => '/delete', condition: fn ($row) => $row->status);

    expect($action->isVisible((object) ['status' => $status]))->toBe($visible);
})->with([
    'truthy' => [1, true],
    'falsy' => [0, false],
    'empty string' => ['', false],
    'string' => ['active', true],
]);

it('should only evaluate a condition that is a closure', function () {
    $action = new Action(index: '', icon: '', title: 'Delete', method: 'DELETE', url: fn ($row) => '/delete', condition: false);

    expect($action->isVisible((object) []))->toBeTrue();
});

it('should convert an action to its array form without the condition', function () {
    $url = fn ($row) => '/edit';

    $action = new Action(index: 'edit', icon: 'icon-edit', title: 'Edit', method: 'GET', url: $url, condition: fn ($row) => true);

    expect($action->toArray())->toBe([
        'index' => 'edit',
        'icon' => 'icon-edit',
        'title' => 'Edit',
        'method' => 'GET',
        'url' => $url,
    ]);
});

// ============================================================================
// Mass Action
// ============================================================================

it('should convert a mass action to its array form with empty options by default', function () {
    $massAction = new MassAction(icon: 'icon-delete', title: 'Delete', method: 'POST', url: '/mass-delete');

    expect($massAction->toArray())->toBe([
        'icon' => 'icon-delete',
        'title' => 'Delete',
        'method' => 'POST',
        'url' => '/mass-delete',
        'options' => [],
    ]);
});
