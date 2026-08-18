<?php

use Webkul\RMA\Models\RMACustomField;
use Webkul\RMA\Models\RMAReason;
use Webkul\RMA\Models\RMARule;

use function Pest\Laravel\postJson;

$staleId = 999999;

function makeReason(string $title): RMAReason
{
    return RMAReason::create([
        'title' => $title,
        'status' => 1,
        'position' => 1,
    ]);
}

function makeRule(string $name): RMARule
{
    return RMARule::create([
        'name' => $name,
        'description' => 'created for a test',
        'status' => 1,
        'return_period' => 7,
        'default' => 0,
    ]);
}

function makeCustomField(string $code): RMACustomField
{
    return RMACustomField::create([
        'code' => $code,
        'label' => 'Test Field',
        'type' => 'text',
        'status' => 1,
        'is_required' => 0,
        'position' => 1,
    ]);
}

it('should delete the reasons that remain and report the ones already gone', function () use ($staleId) {
    $reason = makeReason('reason to delete');

    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.reasons.mass-delete'), [
        'indices' => [$staleId, $reason->id],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.sales.rma.reasons.index.datagrid.mass-delete-partial', [
            'deleted' => 1,
            'skipped' => 1,
        ]));

    expect(RMAReason::find($reason->id))->toBeNull();
});

it('should delete the reasons listed before one that is already gone', function () use ($staleId) {
    $reason = makeReason('reason listed first');

    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.reasons.mass-delete'), [
        'indices' => [$reason->id, $staleId],
    ])->assertOk();

    expect(RMAReason::find($reason->id))->toBeNull();
});

it('should report the ordinary success message when every selected reason exists', function () {
    $reasons = collect(['first reason', 'second reason'])->map(fn ($title) => makeReason($title));

    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.reasons.mass-delete'), [
        'indices' => $reasons->pluck('id')->all(),
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.sales.rma.reasons.index.datagrid.mass-delete-success'));

    expect(RMAReason::whereIn('id', $reasons->pluck('id'))->count())->toBe(0);
});

it('should not fail when every selected reason is already gone', function () use ($staleId) {
    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.reasons.mass-delete'), [
        'indices' => [$staleId, $staleId + 1],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.sales.rma.reasons.index.datagrid.mass-delete-partial', [
            'deleted' => 0,
            'skipped' => 2,
        ]));
});

it('should delete the rules that remain and report the ones already gone', function () use ($staleId) {
    $rule = makeRule('rule to delete');

    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.rules.mass-delete'), [
        'indices' => [$staleId, $rule->id],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.sales.rma.rules.index.datagrid.mass-delete-partial', [
            'deleted' => 1,
            'skipped' => 1,
        ]));

    expect(RMARule::find($rule->id))->toBeNull();
});

it('should delete the custom fields that remain and report the ones already gone', function () use ($staleId) {
    $customField = makeCustomField('field_to_delete');

    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.custom-fields.mass-delete'), [
        'indices' => [$staleId, $customField->id],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.sales.rma.custom-field.index.datagrid.mass-delete-partial', [
            'deleted' => 1,
            'skipped' => 1,
        ]));

    expect(RMACustomField::find($customField->id))->toBeNull();
});

it('should reject a custom field mass delete that does not name a list of records', function () {
    $this->loginAsAdmin();

    postJson(route('admin.sales.rma.custom-fields.mass-delete'), [
        'indices' => 'not-an-array',
    ])->assertJsonValidationErrorFor('indices');
});
