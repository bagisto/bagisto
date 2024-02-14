<?php

namespace Webkul\DataGrid;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Webkul\Admin\Exports\DataGridExport;
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
     * Default sort column of datagrid.
     *
     * @var ?string
     */
    protected $sortColumn;

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
     * Exportable.
     */
    protected bool $exportable = false;

    /**
     * Export meta information.
     */
    protected mixed $exportFile = null;

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
     * Get columns.
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Get actions.
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * Get mass actions.
     */
    public function getMassActions(): array
    {
        return $this->massActions;
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
            options: $column['options'] ?? null,
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
            index: $action['index'] ?? '',
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
            options: $massAction['options'] ?? [],
        );
    }

    /**
     * Map your filter.
     */
    public function addFilter(string $datagridColumn, mixed $queryColumn): void
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
            'export'      => ['sometimes', 'required', 'boolean'],
            'format'      => ['sometimes', 'required', 'in:csv,xls,xlsx'],
        ]);

        return request()->only(['filters', 'sort', 'pagination', 'export', 'format']);
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
                            ->each(fn ($column) => $scopeQueryBuilder->orWhere($column->getDatabaseColumnName(), 'LIKE', '%'.$value.'%'));
                    }
                });
            } else {
                $column = collect($this->columns)->first(fn ($c) => $c->index === $requestedColumn);

                switch ($column->type) {
                    case ColumnTypeEnum::STRING->value:
                        $this->queryBuilder->where(function ($scopeQueryBuilder) use ($column, $requestedValues) {
                            foreach ($requestedValues as $value) {
                                $scopeQueryBuilder->orWhere($column->getDatabaseColumnName(), 'LIKE', '%'.$value.'%');
                            }
                        });

                    case ColumnTypeEnum::INTEGER->value:
                        $this->queryBuilder->where(function ($scopeQueryBuilder) use ($column, $requestedValues) {
                            foreach ($requestedValues as $value) {
                                $scopeQueryBuilder->orWhere($column->getDatabaseColumnName(), $value);
                            }
                        });

                    case ColumnTypeEnum::DROPDOWN->value:
                        $this->queryBuilder->where(function ($scopeQueryBuilder) use ($column, $requestedValues) {
                            foreach ($requestedValues as $value) {
                                $scopeQueryBuilder->orWhere($column->getDatabaseColumnName(), $value);
                            }
                        });

                        break;

                    case ColumnTypeEnum::DATE_RANGE->value:
                    case ColumnTypeEnum::DATE_TIME_RANGE->value:
                        $this->queryBuilder->where(function ($scopeQueryBuilder) use ($column, $requestedValues) {
                            foreach ($requestedValues as $value) {
                                $scopeQueryBuilder->whereBetween($column->getDatabaseColumnName(), [$value[0] ?? '', $value[1] ?? '']);
                            }
                        });

                        break;

                    default:
                        $this->queryBuilder->where(function ($scopeQueryBuilder) use ($column, $requestedValues) {
                            foreach ($requestedValues as $value) {
                                $scopeQueryBuilder->orWhere($column->getDatabaseColumnName(), 'LIKE', '%'.$value.'%');
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
        if (! $this->sortColumn) {
            $this->sortColumn = $this->primaryColumn;
        }

        return $this->queryBuilder->orderBy($requestedSort['column'] ?? $this->sortColumn, $requestedSort['order'] ?? $this->sortOrder);
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

        /**
         * The `export` parameter is validated as a boolean in the `validatedRequest`. An `empty` function will not work,
         * as it will always be treated as true because of "0" and "1".
         */
        if (isset($requestedParams['export']) && (bool) $requestedParams['export']) {
            $this->exportable = true;

            $this->setExportFile($this->queryBuilder->get(), $requestedParams['format']);

            return;
        }

        $this->paginator = $this->processRequestedPagination($requestedParams['pagination'] ?? []);
    }

    /**
     * Set export file.
     *
     * @param  \Illuminate\Support\Collection  $records
     * @param  string  $format
     * @return void
     */
    public function setExportFile($records, $format = 'csv')
    {
        $this->exportFile = Excel::download(new DataGridExport($records), Str::random(36).'.'.$format);
    }

    /**
     * Download export file.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadExportFile()
    {
        return $this->exportFile;
    }

    /**
     * Format data.
     */
    public function formatData(): array
    {
        $paginator = $this->paginator->toArray();

        /**
         * TODO: need to handle this...
         */
        foreach ($this->columns as $column) {
            $column->input_type = $column->getFormInputType();

            $column->options = $column->getFormOptions();
        }

        foreach ($paginator['data'] as $record) {
            $record = $this->sanitizeRow($record);

            foreach ($this->columns as $column) {
                if ($closure = $column->closure) {
                    $record->{$column->index} = $closure($record);

                    $record->is_closure = true;
                }
            }

            $record->actions = [];

            foreach ($this->actions as $index => $action) {
                $getUrl = $action->url;

                $record->actions[] = [
                    'index'  => ! empty($action->index) ? $action->index : 'action_'.$index + 1,
                    'icon'   => $action->icon,
                    'title'  => $action->title,
                    'method' => $action->method,
                    'url'    => $getUrl($record),
                ];
            }
        }

        return [
            'id'           => Crypt::encryptString(get_called_class()),
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
     * Prepare all the setup for datagrid.
     */
    public function prepare(): void
    {
        $this->prepareColumns();

        $this->prepareActions();

        $this->prepareMassActions();

        $this->setQueryBuilder();

        $this->processRequest();
    }

    /**
     * Prepare all the setup for datagrid.
     */
    public function sanitizeRow($row): \stdClass
    {
        /**
         * Convert stdClass to array.
         */
        $tempRow = json_decode(json_encode($row), true);

        foreach ($tempRow as $column => $value) {
            if (! is_string($tempRow[$column])) {
                continue;
            }

            if (is_array($value)) {
                return $this->sanitizeRow($tempRow[$column]);
            } else {
                $row->{$column} = strip_tags($value);
            }
        }

        return $row;
    }

    /**
     * To json.
     */
    public function toJson()
    {
        $this->prepare();

        if ($this->exportable) {
            return $this->downloadExportFile();
        }

        return response()->json($this->formatData());
    }
}
