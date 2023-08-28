<?php

namespace Webkul\DataGrid;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Webkul\DataGrid\Enums\ColumnTypeEnum;

abstract class DataGrid
{
    /**
     * Primary column.
     *
     * @var string
     */
    protected $primaryColumn = 'id';

    /**
     * Default sort order of datagrid.
     *
     * @var string
     */
    protected $sortOrder = 'desc';

    /**
     * Default items per page.
     *
     * @var int
     */
    protected $itemsPerPage = 10;

    /**
     * Columns.
     *
     * @var array
     */
    protected $columns = [];

    /**
     * Actions.
     *
     * @var array
     */
    protected $actions = [];

    /**
     * Mass action.
     *
     * @var array
     */
    protected $massActions = [];

    /**
     * Query builder instance.
     *
     * @var object
     */
    protected $queryBuilder;

    /**
     * Paginator instance.
     */
    protected LengthAwarePaginator $paginator;

    /**
     * Prepare query builder.
     */
    abstract public function prepareQueryBuilder();

    /**
     * Prepare columns.
     */
    abstract public function prepareColumns();

    /**
     * Prepare actions.
     */
    public function prepareActions()
    {
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions()
    {
    }

    /**
     * Add column.
     */
    public function addColumn(array $column): void
    {
        $this->columns[] = new Column(
            index: $column['index'],
            label: $column['label'],
            type: $column['type'],
            searchable: $column['searchable'],
            filterable: $column['filterable'],
            sortable: $column['sortable'],
            closure: $column['closure'] ?? null,
        );
    }

    /**
     * Add action.
     */
    public function addAction(array $action): void
    {
        $this->actions[] = new Action(
            icon: $action['icon'] ?? '',
            title: $action['title'],
            method: $action['method'],
            url: $action['url'],
        );
    }

    /**
     * Add mass action.
     */
    public function addMassAction(array $massAction): void
    {
        $this->massActions[] = new MassAction(
            icon: $massAction['icon'] ?? '',
            title: $massAction['title'],
            method: $massAction['method'],
            url: $massAction['url'],
        );
    }

    /**
     * Map your filter.
     */
    public function addFilter(string $datagridColumn, string $queryColumn): void
    {
        foreach ($this->columns as $column) {
            if ($column->index === $datagridColumn) {
                $column->setDatabaseColumnName($queryColumn);

                break;
            }
        }
    }

    /**
     * Set query builder.
     *
     * @param  mixed  $queryBuilder
     */
    public function setQueryBuilder($queryBuilder = null): void
    {
        $this->queryBuilder = $queryBuilder ?: $this->prepareQueryBuilder();
    }

    /**
     * Validated request.
     */
    public function validatedRequest(): array
    {
        request()->validate([
            'filters'     => ['sometimes', 'required', 'array'],
            'sort'        => ['sometimes', 'required', 'array'],
            'pagination'  => ['sometimes', 'required', 'array'],
        ]);

        return request()->only(['filters', 'sort', 'pagination']);
    }

    /**
     * Process all requested filters.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function processRequestedFilters(array $requestedFilters)
    {
        foreach ($requestedFilters as $requestedColumn => $requestedValues) {
            if ($requestedColumn === 'all') {
                $this->queryBuilder->where(function ($scopeQueryBuilder) use ($requestedValues) {
                    foreach ($requestedValues as $value) {
                        collect($this->columns)
                            ->filter(fn ($column) => $column->searchable && $column->type !== ColumnTypeEnum::BOOLEAN->value)
                            ->each(fn ($column) => $scopeQueryBuilder->orWhere($column->databaseColumnName, $value));
                    }
                });
            } else {
                $column = collect($this->columns)->first(fn ($c) => $c->index === $requestedColumn);

                switch ($column->type) {
                    case ColumnTypeEnum::DATE_RANGE->value:
                    case ColumnTypeEnum::DATE_TIME_RANGE->value:
                        $this->queryBuilder->where(function ($scopeQueryBuilder) use ($column, $requestedValues) {
                            foreach ($requestedValues as $value) {
                                $scopeQueryBuilder->whereBetween($column->databaseColumnName, [$value[0] ?? '', $value[1] ?? '']);
                            }
                        });

                        break;

                    default:
                        $this->queryBuilder->where(function ($scopeQueryBuilder) use ($column, $requestedValues) {
                            foreach ($requestedValues as $value) {
                                $scopeQueryBuilder->orWhere($column->databaseColumnName, $value);
                            }
                        });

                        break;
                }
            }
        }

        return $this->queryBuilder;
    }

    /**
     * Process requested sorting.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function processRequestedSorting($requestedSort)
    {
        return $this->queryBuilder->orderBy($requestedSort['column'] ?? $this->primaryColumn, $requestedSort['order'] ?? $this->sortOrder);
    }

    /**
     * Process requested pagination.
     */
    public function processRequestedPagination($requestedPagination): LengthAwarePaginator
    {
        return $this->queryBuilder->paginate(
            $requestedPagination['per_page'] ?? $this->itemsPerPage,
            ['*'],
            'page',
            $requestedPagination['page'] ?? 1
        );
    }

    /**
     * Process request.
     */
    public function processRequest(): void
    {
        /**
         * Store all request parameters in this variable; avoid using direct request helpers afterward.
         */
        $requestedParams = $this->validatedRequest();

        $this->queryBuilder = $this->processRequestedFilters($requestedParams['filters'] ?? []);

        $this->queryBuilder = $this->processRequestedSorting($requestedParams['sort'] ?? []);

        $this->paginator = $this->processRequestedPagination($requestedParams['pagination'] ?? []);
    }

    /**
     * Format data.
     */
    public function formatData(): array
    {
        $paginator = $this->paginator->toArray();

        foreach ($paginator['data'] as $record) {
            foreach ($this->columns as $column) {
                if ($closure = $column->closure) {
                    $record->{$column->index} = $closure($record);

                    $record->is_closure = true;
                }
            }

            $record->actions = [];

            foreach ($this->actions as $action) {
                $getUrl = $action->url;

                $record->actions[] = [
                    'icon'   => $action->icon,
                    'title'  => $action->title,
                    'method' => $action->method,
                    'url'    => $getUrl($record),
                ];
            }
        }

        return [
            'columns'      => $this->columns,
            'actions'      => $this->actions,
            'mass_actions' => $this->massActions,
            'records'      => $paginator['data'],
            'meta'         => [
                'primary_column'   => $this->primaryColumn,
                'from'             => $paginator['from'],
                'to'               => $paginator['to'],
                'total'            => $paginator['total'],
                'per_page_options' => [10, 20, 30, 40, 50],
                'per_page'         => $paginator['per_page'],
                'current_page'     => $paginator['current_page'],
                'last_page'        => $paginator['last_page'],
            ],
        ];
    }

    /**
     * Get json data.
     */
    public function toJson(): JsonResponse
    {
        $this->prepareColumns();

        $this->prepareActions();

        $this->prepareMassActions();

        $this->setQueryBuilder();

        $this->processRequest();

        return response()->json($this->formatData());
    }
}
