<?php

use Webkul\DataGrid\Exports\DataGridExport;
use Webkul\DataGrid\Tests\Fixtures\CartRuleFixtureDataGrid;

/**
 * A raw grid row with every fixture column, overridden by the given values.
 */
function fixtureRecord(array $overrides = []): object
{
    return (object) array_merge([
        'rule_id' => 1,
        'name' => 'Rule',
        'action_type' => 'by_percent',
        'discount_amount' => '10.0000',
        'sort_order' => 1,
        'status' => 1,
        'starts_from' => null,
        'created_at' => '2024-02-01 00:00:00',
    ], $overrides);
}

beforeEach(function () {
    $grid = new CartRuleFixtureDataGrid;

    $grid->prepareColumns();

    $this->export = $grid->getExporter();
});

// ============================================================================
// Headings And Mapping
// ============================================================================

it('should build the headings from the exportable columns only', function () {
    expect($this->export)->toBeInstanceOf(DataGridExport::class)
        ->and($this->export->headings())->toBe(['ID', 'Name', 'Action Type', 'Discount', 'Priority', 'Status', 'Starts From']);
});

it('should map a record onto the exportable column values', function () {
    $record = fixtureRecord(['rule_id' => 7, 'name' => 'Spring sale', 'starts_from' => '2024-03-01 00:00:00']);

    expect($this->export->map($record))->toBe([7, 'Spring sale', 'by_percent', '10.0000', 1, 1, '2024-03-01 00:00:00']);
});

// ============================================================================
// Formula Injection
// ============================================================================

it('should neutralise a value a spreadsheet would evaluate as a formula', function (string $value) {
    expect($this->export->map(fixtureRecord(['name' => $value]))[1])->toBe("'".$value);
})->with([
    'equals' => '=SUM(A1:A2)',
    'plus' => '+1',
    'minus' => '-1',
    'at' => '@cmd',
    'pipe' => '|cmd',
    'percent' => '%x',
    'leading tab' => "\t=x",
    'leading spaces' => '  =x',
]);

it('should leave harmless values untouched', function (mixed $value) {
    expect($this->export->map(fixtureRecord(['name' => $value]))[1])->toBe($value);
})->with([
    'plain text' => 'Spring sale',
    'dash inside' => 'spring-sale',
    'empty' => '',
    'blank' => '   ',
    'integer' => 7,
    'null' => null,
]);
