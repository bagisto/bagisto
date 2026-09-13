<?php

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Webkul\CartRule\Models\CartRule;
use Webkul\DataGrid\DataGrid;
use Webkul\DataGrid\Exceptions\InvalidColumnExpressionException;
use Webkul\DataGrid\Exceptions\InvalidDataGridException;
use Webkul\DataGrid\Exports\DataGridExport;
use Webkul\DataGrid\Tests\Fixtures\CartRuleFixtureDataGrid;

/**
 * Create a cart rule carrying the batch marker the fixture grid scopes to.
 */
function fixtureRule(string $batch, array $attributes = []): CartRule
{
    return CartRule::factory()->create(array_merge(['description' => $batch], $attributes));
}

/**
 * Process the fixture grid for one batch with the given request parameters and return the JSON payload.
 */
function runFixtureGrid(string $batch, array $request = []): array
{
    return processGrid(new CartRuleFixtureDataGrid($batch), $request);
}

/**
 * Process a grid against the given request parameters and return the JSON payload.
 */
function processGrid(DataGrid $grid, array $request = []): array
{
    request()->replace($request);

    return $grid->process()->getData(true);
}

/**
 * Ids of the records in a grid payload, in the order the grid returned them.
 */
function recordIds(array $payload): array
{
    return array_column($payload['records'], 'rule_id');
}

beforeEach(function () {
    $this->batch = Str::uuid()->toString();
});

// ============================================================================
// Payload
// ============================================================================

it('should return the payload shape with the default pagination meta', function () {
    fixtureRule($this->batch);

    $payload = runFixtureGrid($this->batch);

    expect(Crypt::decryptString($payload['id']))->toBe(CartRuleFixtureDataGrid::class)
        ->and(array_column($payload['columns'], 'index'))->toBe(['rule_id', 'name', 'action_type', 'discount_amount', 'sort_order', 'status', 'starts_from', 'created_at'])
        ->and($payload['columns'][2]['filterable_options'])->toBe([
            ['label' => 'Percentage', 'value' => 'by_percent'],
            ['label' => 'Fixed', 'value' => 'by_fixed'],
        ])
        ->and($payload['actions'])->toHaveCount(2)
        ->and($payload['actions'][0])->toMatchArray(['index' => 'edit', 'icon' => 'icon-edit', 'title' => 'Edit', 'method' => 'GET'])
        ->and($payload['mass_actions'])->toHaveCount(2)
        ->and($payload['records'])->toHaveCount(1)
        ->and($payload['meta'])->toBe([
            'primary_column' => 'rule_id',
            'from' => 1,
            'to' => 1,
            'total' => 1,
            'per_page_options' => [10, 20, 30, 40, 50],
            'per_page' => 10,
            'current_page' => 1,
            'last_page' => 1,
        ]);
});

it('should expose the mass actions with their options', function () {
    expect(runFixtureGrid($this->batch)['mass_actions'])->toBe([
        [
            'icon' => 'icon-delete',
            'title' => 'Delete',
            'method' => 'POST',
            'url' => '/rules/mass-delete',
            'options' => [],
        ],
        [
            'icon' => '',
            'title' => 'Update Status',
            'method' => 'POST',
            'url' => '/rules/mass-update',
            'options' => [
                ['label' => 'Active', 'value' => 1],
                ['label' => 'Inactive', 'value' => 0],
            ],
        ],
    ]);
});

it('should dispatch lifecycle events named after the snake cased grid', function () {
    fixtureRule($this->batch);

    Event::fake([
        'datagrid.cart_rule_fixture_data_grid.prepare.before',
        'datagrid.cart_rule_fixture_data_grid.columns.add.after',
        'datagrid.cart_rule_fixture_data_grid.process_request.after',
    ]);

    runFixtureGrid($this->batch);

    Event::assertDispatched('datagrid.cart_rule_fixture_data_grid.prepare.before');

    Event::assertDispatchedTimes('datagrid.cart_rule_fixture_data_grid.columns.add.after', 8);

    Event::assertDispatched('datagrid.cart_rule_fixture_data_grid.process_request.after');
});

// ============================================================================
// Records
// ============================================================================

it('should apply column closures and strip tags from record values', function () {
    fixtureRule($this->batch, ['name' => '<b>bold</b> rule', 'status' => false]);

    $record = runFixtureGrid($this->batch)['records'][0];

    expect($record['name'])->toBe('bold rule')
        ->and($record['status'])->toBe('inactive');
});

it('should attach the row actions whose condition passes to each record', function () {
    $active = fixtureRule($this->batch, ['name' => 'active rule', 'status' => true]);

    $inactive = fixtureRule($this->batch, ['name' => 'inactive rule', 'status' => false]);

    $records = collect(runFixtureGrid($this->batch)['records'])->keyBy('rule_id');

    expect($records[$active->id]['actions'])->toBe([
        ['index' => 'edit', 'icon' => 'icon-edit', 'title' => 'Edit', 'method' => 'GET', 'url' => "/rules/{$active->id}/edit"],
    ])
        ->and($records[$inactive->id]['actions'])->toBe([
            ['index' => 'edit', 'icon' => 'icon-edit', 'title' => 'Edit', 'method' => 'GET', 'url' => "/rules/{$inactive->id}/edit"],
            ['index' => 'action_2', 'icon' => 'icon-delete', 'title' => 'Delete inactive rule', 'method' => 'DELETE', 'url' => "/rules/{$inactive->id}"],
        ]);
});

// ============================================================================
// Sorting
// ============================================================================

it('should sort by the primary column descending by default', function () {
    $first = fixtureRule($this->batch);

    $second = fixtureRule($this->batch);

    expect(recordIds(runFixtureGrid($this->batch)))->toBe([$second->id, $first->id]);
});

it('should sort by a requested sortable column in the requested order', function () {
    $bravo = fixtureRule($this->batch, ['name' => 'bravo']);

    $alpha = fixtureRule($this->batch, ['name' => 'alpha']);

    $charlie = fixtureRule($this->batch, ['name' => 'charlie']);

    expect(recordIds(runFixtureGrid($this->batch, ['sort' => ['column' => 'name', 'order' => 'asc']])))->toBe([$alpha->id, $bravo->id, $charlie->id])
        ->and(recordIds(runFixtureGrid($this->batch, ['sort' => ['column' => 'name', 'order' => 'desc']])))->toBe([$charlie->id, $bravo->id, $alpha->id]);
});

it('should ignore a sort on a column that is not sortable', function () {
    $first = fixtureRule($this->batch, ['action_type' => 'by_percent']);

    $second = fixtureRule($this->batch, ['action_type' => 'by_fixed']);

    expect(recordIds(runFixtureGrid($this->batch, ['sort' => ['column' => 'action_type', 'order' => 'asc']])))->toBe([$first->id, $second->id]);
});

it('should fall back to the default sort order for an unknown order', function () {
    $first = fixtureRule($this->batch, ['name' => 'bravo']);

    $second = fixtureRule($this->batch, ['name' => 'alpha']);

    expect(recordIds(runFixtureGrid($this->batch, ['sort' => ['column' => 'name', 'order' => 'sideways']])))->toBe([$first->id, $second->id]);
});

// ============================================================================
// Pagination
// ============================================================================

it('should paginate with the requested page size and page', function () {
    $rules = collect(range(1, 5))->map(fn () => fixtureRule($this->batch));

    $payload = runFixtureGrid($this->batch, ['pagination' => ['per_page' => '2', 'page' => '2']]);

    expect(recordIds($payload))->toBe([$rules[2]->id, $rules[1]->id])
        ->and($payload['meta'])->toMatchArray([
            'from' => 3,
            'to' => 4,
            'total' => 5,
            'per_page' => 2,
            'current_page' => 2,
            'last_page' => 3,
        ]);
});

it('should honour the page size and options configured on the grid', function () {
    collect(range(1, 3))->each(fn () => fixtureRule($this->batch));

    $grid = new CartRuleFixtureDataGrid($this->batch);

    $grid->setItemsPerPage(2);

    $grid->setPerPageOptions([2, 4]);

    $payload = processGrid($grid);

    expect($payload['records'])->toHaveCount(2)
        ->and($payload['meta'])->toMatchArray([
            'per_page_options' => [2, 4],
            'per_page' => 2,
            'last_page' => 2,
        ]);
});

// ============================================================================
// Filtering
// ============================================================================

it('should filter a text column by a partial match and combine several values', function () {
    $alpha = fixtureRule($this->batch, ['name' => 'alpha rule']);

    $beta = fixtureRule($this->batch, ['name' => 'beta rule']);

    fixtureRule($this->batch, ['name' => 'gamma rule']);

    expect(recordIds(runFixtureGrid($this->batch, ['filters' => ['name' => 'alph']])))->toBe([$alpha->id])
        ->and(recordIds(runFixtureGrid($this->batch, ['filters' => ['name' => ['alpha', 'beta']]])))->toBe([$beta->id, $alpha->id]);
});

it('should filter a dropdown text column by its exact value', function () {
    $fixed = fixtureRule($this->batch, ['action_type' => 'by_fixed']);

    fixtureRule($this->batch, ['action_type' => 'by_percent']);

    expect(recordIds(runFixtureGrid($this->batch, ['filters' => ['action_type' => 'by_fixed']])))->toBe([$fixed->id])
        ->and(runFixtureGrid($this->batch, ['filters' => ['action_type' => 'by_']])['records'])->toBeEmpty();
});

it('should filter an integer column by an exact value, an operator or a range', function (string|array $filter, array $expectedOrders) {
    foreach ([1, 5, 10] as $sortOrder) {
        fixtureRule($this->batch, ['sort_order' => $sortOrder]);
    }

    $payload = runFixtureGrid($this->batch, [
        'filters' => ['sort_order' => $filter],
        'sort' => ['column' => 'sort_order', 'order' => 'asc'],
    ]);

    expect(array_column($payload['records'], 'sort_order'))->toBe($expectedOrders);
})->with([
    'exact' => ['5', [5]],
    'greater or equal' => ['>= 5', [5, 10]],
    'less than' => ['< 5', [1]],
    'range' => ['1 - 5', [1, 5]],
    'several' => [['< 5', '10'], [1, 10]],
]);

it('should filter a decimal column by an exact value, an operator or a range', function (string $filter, array $expectedAmounts) {
    foreach ([2.5, 7.5, 12.5] as $amount) {
        fixtureRule($this->batch, ['discount_amount' => $amount]);
    }

    $payload = runFixtureGrid($this->batch, [
        'filters' => ['discount_amount' => $filter],
        'sort' => ['column' => 'discount_amount', 'order' => 'asc'],
    ]);

    expect(array_column($payload['records'], 'discount_amount'))
        ->toHaveCount(count($expectedAmounts))
        ->sequence(...array_map(fn (float $expected) => fn ($amount) => $amount->toBePrice($expected), $expectedAmounts));
})->with([
    'exact' => ['7.5', [7.5]],
    'greater than' => ['> 7.5', [12.5]],
    'less or equal' => ['<= 7.5', [2.5, 7.5]],
    'range' => ['5 - 10', [7.5]],
]);

it('should filter a boolean column by its dropdown value', function (string|array $filter, array $expectedStatuses) {
    fixtureRule($this->batch, ['status' => true]);

    fixtureRule($this->batch, ['status' => false]);

    $payload = runFixtureGrid($this->batch, ['filters' => ['status' => $filter]]);

    expect(array_column($payload['records'], 'status'))->toBe($expectedStatuses);
})->with([
    'active' => ['1', ['active']],
    'inactive' => ['0', ['inactive']],
    'both' => [['1', '0'], ['inactive', 'active']],
]);

it('should filter a date column by a range, an open ended range or a named range', function (string|array $filter, array $expectedNames) {
    fixtureRule($this->batch, ['name' => 'march', 'starts_from' => '2024-03-15 00:00:00']);

    fixtureRule($this->batch, ['name' => 'april', 'starts_from' => '2024-04-20 09:30:00']);

    fixtureRule($this->batch, ['name' => 'today', 'starts_from' => now()->format('Y-m-d H:i:s')]);

    $payload = runFixtureGrid($this->batch, [
        'filters' => ['starts_from' => $filter],
        'sort' => ['column' => 'starts_from', 'order' => 'asc'],
    ]);

    expect(array_column($payload['records'], 'name'))->toBe($expectedNames);
})->with([
    'closed range' => [[['2024-03-01', '2024-04-30']], ['march', 'april']],
    'from only' => [[['2024-04-01', '']], ['april', 'today']],
    'to only' => [[['', '2024-03-31']], ['march']],
    'single day' => ['2024-03-15', ['march']],
    'named range' => ['today', ['today']],
]);

it('should filter a datetime column by a range that carries times', function () {
    $morning = fixtureRule($this->batch, ['created_at' => '2024-05-01 08:00:00']);

    fixtureRule($this->batch, ['created_at' => '2024-05-01 18:00:00']);

    $payload = runFixtureGrid($this->batch, ['filters' => ['created_at' => [['2024-05-01 07:00:00', '2024-05-01 12:00:00']]]]);

    expect(recordIds($payload))->toBe([$morning->id]);
});

it('should search every searchable column with the all filter', function () {
    $needle = Str::random(8);

    $match = fixtureRule($this->batch, ['name' => 'rule '.$needle]);

    fixtureRule($this->batch, ['name' => 'other rule', 'action_type' => $needle]);

    expect(recordIds(runFixtureGrid($this->batch, ['filters' => ['all' => $needle]])))->toBe([$match->id]);
});

it('should map a filter onto its query column through addFilter', function () {
    $rule = fixtureRule($this->batch);

    fixtureRule($this->batch);

    $grid = new CartRuleFixtureDataGrid($this->batch);

    $payload = processGrid($grid, ['filters' => ['rule_id' => (string) $rule->id]]);

    $column = collect($grid->getColumns())->first(fn ($column) => $column->getIndex() === 'rule_id');

    expect($column->getColumnName())->toBe('cart_rules.id')
        ->and(recordIds($payload))->toBe([$rule->id]);
});

it('should ignore a filter on a column the grid does not declare', function () {
    fixtureRule($this->batch);

    fixtureRule($this->batch);

    expect(runFixtureGrid($this->batch, ['filters' => ['unknown' => 'value']])['records'])->toHaveCount(2);
});

it('should reject a filter value that is neither a string nor an array', function () {
    fixtureRule($this->batch);

    runFixtureGrid($this->batch, ['filters' => ['sort_order' => 5]]);
})->throws(InvalidColumnExpressionException::class);

// ============================================================================
// Request Validation
// ============================================================================

it('should validate the request parameters', function (array $request) {
    runFixtureGrid($this->batch, $request);
})->with([
    'filters must be an array' => [['filters' => 'name']],
    'sort must be an array' => [['sort' => 'name']],
    'pagination must be an array' => [['pagination' => '2']],
    'export must be a boolean' => [['export' => 'yes']],
    'format must be a spreadsheet type' => [['export' => '1', 'format' => 'pdf']],
])->throws(ValidationException::class);

// ============================================================================
// Export
// ============================================================================

it('should download an export in the requested format instead of paginating', function () {
    Excel::fake();

    fixtureRule($this->batch, ['name' => 'exported rule']);

    $grid = new CartRuleFixtureDataGrid($this->batch);

    request()->replace(['export' => '1', 'format' => 'xlsx']);

    $response = $grid->process();

    expect($response)->toBeInstanceOf(BinaryFileResponse::class)
        ->and($grid->isExportable())->toBeTrue()
        ->and($grid->getExportFileNameWithExtension())->toEndWith('.xlsx');

    Excel::assertDownloaded($grid->getExportFileNameWithExtension(), function (DataGridExport $export) {
        return $export->headings() === ['ID', 'Name', 'Action Type', 'Discount', 'Priority', 'Status', 'Starts From']
            && $export->query()->pluck('name')->all() === ['exported rule'];
    });
});

it('should export as csv when no format is requested', function () {
    Excel::fake();

    fixtureRule($this->batch);

    $grid = new CartRuleFixtureDataGrid($this->batch);

    request()->replace(['export' => '1']);

    expect($grid->process())->toBeInstanceOf(BinaryFileResponse::class)
        ->and($grid->getExportFileNameWithExtension())->toEndWith('.csv');

    Excel::assertDownloaded($grid->getExportFileNameWithExtension());
});

// ============================================================================
// Helper
// ============================================================================

it('should resolve a datagrid class through the helper', function () {
    expect(datagrid(CartRuleFixtureDataGrid::class))->toBeInstanceOf(CartRuleFixtureDataGrid::class);
});

it('should reject a class that is not a datagrid in the helper', function () {
    datagrid(CartRule::class);
})->throws(InvalidDataGridException::class);
