<?php

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Webkul\CartRule\Models\CartRule;
use Webkul\DataGrid\Column;
use Webkul\DataGrid\Exceptions\InvalidColumnExpressionException;

// ============================================================================
// Value Parsing
// ============================================================================

it('should translate an integer filter into the matching where clause', function (string $filter, array $expectedWhere) {
    $queryBuilder = DB::table('cart_rules');

    filterColumn('integer', 'sort_order')->processFilter($queryBuilder, $filter);

    expect(nestedWheres($queryBuilder))->toHaveCount(1)
        ->and(nestedWheres($queryBuilder)[0])->toMatchArray($expectedWhere);
})->with([
    'equality' => ['5', ['type' => 'Basic', 'column' => 'sort_order', 'operator' => '=', 'value' => 5, 'boolean' => 'or']],
    'operator' => ['>= 5', ['type' => 'Basic', 'column' => 'sort_order', 'operator' => '>=', 'value' => 5, 'boolean' => 'or']],
    'range' => ['1 - 5', ['type' => 'between', 'column' => 'sort_order', 'values' => [1, 5], 'boolean' => 'or']],
    'non numeric' => ['abc', ['type' => 'Basic', 'column' => 'sort_order', 'operator' => '=', 'value' => 0, 'boolean' => 'or']],
]);

it('should translate a decimal filter into the matching where clause', function (string $filter, array $expectedWhere) {
    $queryBuilder = DB::table('cart_rules');

    filterColumn('decimal', 'discount_amount')->processFilter($queryBuilder, $filter);

    expect(nestedWheres($queryBuilder))->toHaveCount(1)
        ->and(nestedWheres($queryBuilder)[0])->toMatchArray($expectedWhere);
})->with([
    'equality' => ['2.25', ['type' => 'Basic', 'column' => 'discount_amount', 'operator' => '=', 'value' => 2.25, 'boolean' => 'or']],
    'operator' => ['> 1.5', ['type' => 'Basic', 'column' => 'discount_amount', 'operator' => '>', 'value' => 1.5, 'boolean' => 'or']],
    'range' => ['1.5 - 2.5', ['type' => 'between', 'column' => 'discount_amount', 'values' => [1.5, 2.5], 'boolean' => 'or']],
]);

it('should combine several text values with a like clause each', function () {
    $queryBuilder = DB::table('cart_rules');

    $column = filterColumn('string', 'name');

    $column->processFilter($queryBuilder, ['alpha', 'beta']);

    expect(nestedWheres($queryBuilder))->toHaveCount(2)
        ->and(nestedWheres($queryBuilder)[0])->toMatchArray(['column' => 'name', 'operator' => $column->likeOperator(), 'value' => '%alpha%', 'boolean' => 'or'])
        ->and(nestedWheres($queryBuilder)[1])->toMatchArray(['column' => 'name', 'operator' => $column->likeOperator(), 'value' => '%beta%', 'boolean' => 'or']);
});

it('should reject a filter value that is neither a string nor an array', function (string $type, ?string $filterableType) {
    filterColumn($type, 'field', $filterableType)->processFilter(DB::table('cart_rules'), 5);
})->with([
    'string' => ['string', null],
    'string dropdown' => ['string', 'dropdown'],
    'integer' => ['integer', null],
    'decimal' => ['decimal', null],
    'boolean' => ['boolean', null],
    'date' => ['date', null],
    'datetime' => ['datetime', null],
    'aggregate' => ['aggregate', null],
    'aggregate dropdown' => ['aggregate', 'dropdown'],
])->throws(InvalidColumnExpressionException::class);

// ============================================================================
// Aggregate
// ============================================================================

it('should filter a grouped query through a having clause on an aggregate column', function () {
    $pair = Str::uuid()->toString();

    $single = Str::uuid()->toString();

    CartRule::factory()->count(2)->create(['description' => $pair]);

    CartRule::factory()->create(['description' => $single]);

    $queryBuilder = DB::table('cart_rules')
        ->select('description', DB::raw('COUNT(*) as rule_count'))
        ->whereIn('description', [$pair, $single])
        ->groupBy('description');

    $column = filterColumn('aggregate', 'rule_count', 'dropdown');

    $column->setColumnName(DB::raw('COUNT(*)'));

    $column->processFilter($queryBuilder, '2');

    expect($queryBuilder->pluck('description')->all())->toBe([$pair]);
});

it('should match an aggregate text filter with a like on the having clause', function () {
    $queryBuilder = DB::table('cart_rules');

    $column = filterColumn('aggregate', 'rule_count');

    $column->processFilter($queryBuilder, '3');

    expect($queryBuilder->havings[0]['query']->havings[0])
        ->toMatchArray(['column' => 'rule_count', 'operator' => $column->likeOperator(), 'value' => '%3%', 'boolean' => 'or']);
});

/**
 * Resolve a filterable column of the given type, optionally with a filter type.
 */
function filterColumn(string $type, string $index, ?string $filterableType = null): Column
{
    return Column::resolveType([
        'index' => $index,
        'label' => Str::headline($index),
        'type' => $type,
        'filterable' => true,
        'filterable_type' => $filterableType,
    ]);
}

/**
 * The clauses of the nested where group a column filter adds to the builder.
 */
function nestedWheres(Builder $queryBuilder): array
{
    return $queryBuilder->wheres[0]['query']->wheres;
}
