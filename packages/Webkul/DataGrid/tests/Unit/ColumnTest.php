<?php

use Webkul\DataGrid\Column;
use Webkul\DataGrid\ColumnTypes\Aggregate;
use Webkul\DataGrid\ColumnTypes\Boolean;
use Webkul\DataGrid\ColumnTypes\Date;
use Webkul\DataGrid\ColumnTypes\Datetime;
use Webkul\DataGrid\ColumnTypes\Decimal;
use Webkul\DataGrid\ColumnTypes\Integer;
use Webkul\DataGrid\ColumnTypes\Text;
use Webkul\DataGrid\Enums\ColumnTypeEnum;
use Webkul\DataGrid\Enums\DateRangeOptionEnum;
use Webkul\DataGrid\Enums\FilterTypeEnum;
use Webkul\DataGrid\Exceptions\InvalidColumnException;
use Webkul\DataGrid\Exceptions\InvalidColumnTypeException;

// ============================================================================
// Type Resolution
// ============================================================================

it('should resolve each column type to its column class', function (string $type, string $class) {
    $column = Column::resolveType(['index' => 'field', 'label' => 'Field', 'type' => $type]);

    expect(ColumnTypeEnum::getClassName($type))->toBe($class)
        ->and($column)->toBeInstanceOf($class)
        ->and($column->getType())->toBe($type);
})->with([
    'string' => ['string', Text::class],
    'integer' => ['integer', Integer::class],
    'decimal' => ['decimal', Decimal::class],
    'boolean' => ['boolean', Boolean::class],
    'date' => ['date', Date::class],
    'datetime' => ['datetime', Datetime::class],
    'aggregate' => ['aggregate', Aggregate::class],
]);

it('should reject an unknown column type', function () {
    Column::resolveType(['index' => 'field', 'label' => 'Field', 'type' => 'uuid']);
})->throws(InvalidColumnTypeException::class, 'Invalid column type: uuid');

it('should require the index, label and type keys', function (array $column, string $missingKey) {
    expect(fn () => Column::resolveType($column))
        ->toThrow(InvalidColumnException::class, "The `{$missingKey}` key is required.");
})->with([
    'index' => [['label' => 'Field', 'type' => 'string'], 'index'],
    'label' => [['index' => 'field', 'type' => 'string'], 'label'],
    'type' => [['index' => 'field', 'label' => 'Field'], 'type'],
]);

// ============================================================================
// Flags
// ============================================================================

it('should apply the column defaults and expose them through toArray', function () {
    $column = Column::resolveType(['index' => 'name', 'label' => 'Name', 'type' => 'string']);

    expect($column->toArray())->toBe([
        'index' => 'name',
        'label' => 'Name',
        'type' => 'string',
        'searchable' => false,
        'filterable' => false,
        'filterable_type' => null,
        'filterable_options' => [],
        'allow_multiple_values' => true,
        'sortable' => false,
        'exportable' => true,
        'visibility' => true,
    ])
        ->and($column->getColumnName())->toBe('name')
        ->and($column->getClosure())->toBeNull();
});

it('should honour the flags a column is declared with', function () {
    $closure = fn ($row) => strtoupper($row->name);

    $column = Column::resolveType([
        'index' => 'name',
        'label' => 'Name',
        'type' => 'string',
        'searchable' => true,
        'filterable' => true,
        'filterable_type' => 'dropdown',
        'filterable_options' => [['label' => 'Alpha', 'value' => 'alpha']],
        'allow_multiple_values' => false,
        'sortable' => true,
        'exportable' => false,
        'visibility' => false,
        'closure' => $closure,
    ]);

    expect($column->toArray())->toBe([
        'index' => 'name',
        'label' => 'Name',
        'type' => 'string',
        'searchable' => true,
        'filterable' => true,
        'filterable_type' => 'dropdown',
        'filterable_options' => [['label' => 'Alpha', 'value' => 'alpha']],
        'allow_multiple_values' => false,
        'sortable' => true,
        'exportable' => false,
        'visibility' => false,
    ])
        ->and($column->getClosure())->toBe($closure);
});

it('should resolve filterable options given as a closure', function () {
    $column = Column::resolveType([
        'index' => 'group',
        'label' => 'Group',
        'type' => 'string',
        'filterable' => true,
        'filterable_type' => 'dropdown',
        'filterable_options' => fn () => [['label' => 'General', 'value' => 1]],
    ]);

    expect($column->getFilterableOptions())->toBe([['label' => 'General', 'value' => 1]]);
});

it('should map the index onto another query column without renaming the index', function () {
    $column = Column::resolveType(['index' => 'name', 'label' => 'Name', 'type' => 'string']);

    $column->setColumnName('customers.name');

    expect($column->getIndex())->toBe('name')
        ->and($column->getColumnName())->toBe('customers.name');
});

// ============================================================================
// Type Specific Rules
// ============================================================================

it('should default a boolean column to a dropdown with true and false options', function () {
    $column = Column::resolveType(['index' => 'status', 'label' => 'Status', 'type' => 'boolean', 'filterable' => true]);

    expect($column->getFilterableType())->toBe(FilterTypeEnum::DROPDOWN->value)
        ->and($column->getFilterableOptions())->toBe([
            ['label' => trans('admin::app.components.datagrid.filters.boolean-options.true'), 'value' => 1],
            ['label' => trans('admin::app.components.datagrid.filters.boolean-options.false'), 'value' => 0],
        ]);
});

it('should keep the options a boolean column is declared with', function () {
    $column = Column::resolveType([
        'index' => 'status',
        'label' => 'Status',
        'type' => 'boolean',
        'filterable' => true,
        'filterable_options' => [['label' => 'Enabled', 'value' => 1]],
    ]);

    expect($column->getFilterableOptions())->toBe([['label' => 'Enabled', 'value' => 1]]);
});

it('should reject a boolean column whose filter is not a dropdown', function () {
    Column::resolveType(['index' => 'status', 'label' => 'Status', 'type' => 'boolean', 'filterable_type' => 'date_range']);
})->throws(InvalidColumnException::class, 'Boolean filters will only work with `dropdown` type.');

it('should only accept the matching range filter on date and datetime columns', function (string $type, string $accepted, string $rejected) {
    $column = Column::resolveType(['index' => 'created_at', 'label' => 'Created', 'type' => $type, 'filterable_type' => $accepted]);

    expect($column->getFilterableType())->toBe($accepted)
        ->and(fn () => Column::resolveType(['index' => 'created_at', 'label' => 'Created', 'type' => $type, 'filterable_type' => $rejected]))
        ->toThrow(InvalidColumnException::class);
})->with([
    'date' => ['date', 'date_range', 'datetime_range'],
    'datetime' => ['datetime', 'datetime_range', 'dropdown'],
]);

it('should default date and datetime columns to the named date range options', function (string $type) {
    $column = Column::resolveType(['index' => 'created_at', 'label' => 'Created', 'type' => $type, 'filterable' => true]);

    expect(array_column($column->getFilterableOptions(), 'name'))
        ->toBe(array_map(fn (DateRangeOptionEnum $option) => $option->value, DateRangeOptionEnum::cases()))
        ->and($column->getFilterableOptions()[0])->toHaveKeys(['name', 'label', 'from', 'to']);
})->with(['date', 'datetime']);

it('should never allow multiple values on integer and decimal columns', function (string $type) {
    $column = Column::resolveType(['index' => 'qty', 'label' => 'Qty', 'type' => $type, 'allow_multiple_values' => true]);

    expect($column->getAllowMultipleValues())->toBeFalse();
})->with(['integer', 'decimal']);
