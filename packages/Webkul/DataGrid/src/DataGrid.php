<?php

namespace Webkul\DataGrid;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Event;
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
     * Per page options.
     *
     * @var array
     */
    protected $perPageOptions = [10, 20, 30, 40, 50];

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
    public function prepareActions() {}

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions() {}

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
        $this->dispatchEvent('columns.add.before', [$this, $column]);

        $this->columns[] = Column::resolveType($column);

        $this->dispatchEvent('columns.add.after', [$this, $this->columns[count($this->columns) - 1]]);
    }

    /**
     * Add action.
     */
    public function addAction(array $action): void
    {
        $this->dispatchEvent('actions.add.before', [$this, $action]);

        $this->actions[] = new Action(
            index: $action['index'] ?? '',
            icon: $action['icon'] ?? '',
            title: $action['title'],
            method: $action['method'],
            url: $action['url'],
        );

        $this->dispatchEvent('actions.add.after', [$this, $this->actions[count($this->actions) - 1]]);
    }

    /**
     * Add mass action.
     */
    public function addMassAction(array $massAction): void
    {
        $this->dispatchEvent('mass_actions.add.before', [$this, $massAction]);

        $this->massActions[] = new MassAction(
            icon: $massAction['icon'] ?? '',
            title: $massAction['title'],
            method: $massAction['method'],
            url: $massAction['url'],
            options: $massAction['options'] ?? [],
        );

        $this->dispatchEvent('mass_actions.add.after', [$this, $this->massActions[count($this->massActions) - 1]]);
    }

    /**
     * Set query builder.
     *
     * @param  mixed  $queryBuilder
     */
    public function setQueryBuilder($queryBuilder = null): void
    {
        $this->dispatchEvent('query_builder.set.before', [$this, $queryBuilder]);

        $this->queryBuilder = $queryBuilder ?: $this->prepareQueryBuilder();

        $this->dispatchEvent('query_builder.set.after', $this);
    }

    /**
     * Get query builder.
     */
    public function getQueryBuilder(): mixed
    {
        return $this->queryBuilder;
    }

    /**
     * Map your filter.
     */
    public function addFilter(string $datagridColumn, mixed $queryColumn): void
    {
        $this->dispatchEvent('filters.add.before', [$this, $datagridColumn, $queryColumn]);

        foreach ($this->columns as $column) {
            if ($column->getIndex() === $datagridColumn) {
                $column->setColumnName($queryColumn);

                break;
            }
        }

        $this->dispatchEvent('filters.add.after', [$this, $datagridColumn, $queryColumn]);
    }

    /**
     * Set exportable.
     */
    public function setExportable(bool $exportable): void
    {
        $this->dispatchEvent('exportable.set.before', [$this, $exportable]);

        $this->exportable = $exportable;

        $this->dispatchEvent('exportable.set.after', $this);
    }

    /**
     * Get exportable.
     */
    public function getExportable(): bool
    {
        return $this->exportable;
    }

    /**
     * Set export file.
     *
     * @param  string  $format
     * @return void
     */
    public function setExportFile($format = 'csv')
    {
        $this->dispatchEvent('export_file.set.before', [$this, $format]);

        $this->setExportable(true);

        $this->exportFile = Excel::download(new DataGridExport($this), Str::random(36).'.'.$format);

        $this->dispatchEvent('export_file.set.after', $this);
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
     * Process the datagrid.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
     */
    public function process()
    {
        $this->prepare();

        if ($this->getExportable()) {
            return $this->downloadExportFile();
        }

        return response()->json($this->formatData());
    }

    /**
     * To json. The reason for deprecation is that it is not an action returning JSON; instead,
     * it is a process method which returns a download as well as a JSON response.
     *
     * @deprecated
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
     */
    public function toJson()
    {
        $this->prepare();

        if ($this->getExportable()) {
            return $this->downloadExportFile();
        }

        return response()->json($this->formatData());
    }

    /**
     * Validated request.
     */
    protected function validatedRequest(): array
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
    protected function processRequestedFilters(array $requestedFilters)
    {
        foreach ($requestedFilters as $requestedColumn => $requestedValues) {
            if ($requestedColumn === 'all') {
                $this->queryBuilder->where(function ($scopeQueryBuilder) use ($requestedValues) {
                    foreach ($requestedValues as $value) {
                        collect($this->columns)
                            ->filter(fn ($column) => $column->getSearchable() && ! in_array($column->getType(), [
                                ColumnTypeEnum::BOOLEAN->value,
                                ColumnTypeEnum::AGGREGATE->value,
                            ]))
                            ->each(fn ($column) => $scopeQueryBuilder->orWhere($column->getColumnName(), 'LIKE', '%'.$value.'%'));
                    }
                });
            } else {
                collect($this->columns)
                    ->first(fn ($column) => $column->getIndex() === $requestedColumn)
                    ->processFilter($this->queryBuilder, $requestedValues);
            }
        }

        return $this->queryBuilder;
    }

    /**
     * Process requested sorting.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function processRequestedSorting($requestedSort)
    {
        if (! $this->sortColumn) {
            $this->sortColumn = $this->primaryColumn;
        }

        return $this->queryBuilder->orderBy($requestedSort['column'] ?? $this->sortColumn, $requestedSort['order'] ?? $this->sortOrder);
    }

    /**
     * Process requested pagination.
     */
    protected function processRequestedPagination($requestedPagination): LengthAwarePaginator
    {
        return $this->queryBuilder->paginate(
            $requestedPagination['per_page'] ?? $this->itemsPerPage,
            ['*'],
            'page',
            $requestedPagination['page'] ?? 1
        );
    }

    /**
     * Process paginated request.
     */
    protected function processPaginatedRequest(array $requestedParams): void
    {
        $this->dispatchEvent('process_request.paginated.before', $this);

        $this->paginator = $this->processRequestedPagination($requestedParams['pagination'] ?? []);

        $this->dispatchEvent('process_request.paginated.after', $this);
    }

    /**
     * Process export request.
     */
    protected function processExportRequest(array $requestedParams): void
    {
        $this->dispatchEvent('process_request.export.before', $this);

        $this->setExportFile($requestedParams['format']);

        $this->dispatchEvent('process_request.export.after', $this);
    }

    /**
     * Process request.
     */
    protected function processRequest(): void
    {
        $this->dispatchEvent('process_request.before', $this);

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
        isset($requestedParams['export']) && (bool) $requestedParams['export']
            ? $this->processExportRequest($requestedParams)
            : $this->processPaginatedRequest($requestedParams);

        $this->dispatchEvent('process_request.after', $this);
    }

    /**
     * Prepare all the setup for datagrid.
     */
    protected function sanitizeRow($row): \stdClass
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
     * Format columns.
     */
    protected function formatColumns(): array
    {
        return collect($this->columns)
            ->map(fn ($column) => $column->toArray())
            ->toArray();
    }

    /**
     * Format actions.
     */
    protected function formatActions(): array
    {
        return collect($this->actions)
            ->map(fn ($action) => $action->toArray())
            ->toArray();
    }

    /**
     * Format mass actions.
     */
    protected function formatMassActions(): array
    {
        return collect($this->massActions)
            ->map(fn ($massAction) => $massAction->toArray())
            ->toArray();
    }

    /**
     * Format records.
     */
    protected function formatRecords($records): mixed
    {
        foreach ($records as $record) {
            $record = $this->sanitizeRow($record);

            foreach ($this->columns as $column) {
                if ($closure = $column->getClosure()) {
                    $record->{$column->getIndex()} = $closure($record);
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

        return $records;
    }

    /**
     * Format data.
     */
    protected function formatData(): array
    {
        $paginator = $this->paginator->toArray();

        return [
            'id'           => Crypt::encryptString(get_called_class()),
            'columns'      => $this->formatColumns(),
            'actions'      => $this->formatActions(),
            'mass_actions' => $this->formatMassActions(),
            'records'      => $this->formatRecords($paginator['data']),
            'meta'         => [
                'primary_column'   => $this->primaryColumn,
                'from'             => $paginator['from'],
                'to'               => $paginator['to'],
                'total'            => $paginator['total'],
                'per_page_options' => $this->perPageOptions,
                'per_page'         => $paginator['per_page'],
                'current_page'     => $paginator['current_page'],
                'last_page'        => $paginator['last_page'],
            ],
        ];
    }

    /**
     * Dispatch event.
     */
    protected function dispatchEvent(string $eventName, mixed $payload): void
    {
        $reflection = new \ReflectionClass($this);

        $datagridName = Str::snake($reflection->getShortName());

        Event::dispatch("datagrid.{$datagridName}.{$eventName}", $payload);
    }

    /**
     * Prepare all the setup for datagrid.
     */
    protected function prepare(): void
    {
        $this->dispatchEvent('prepare.before', $this);

        $this->prepareColumns();

        $this->dispatchEvent('columns.prepare.after', $this);

        $this->prepareActions();

        $this->dispatchEvent('actions.prepare.after', $this);

        $this->prepareMassActions();

        $this->dispatchEvent('mass_actions.prepare.after', $this);

        $this->setQueryBuilder();

        $this->dispatchEvent('query_builder.prepare.after', $this);

        $this->processRequest();

        $this->dispatchEvent('prepare.after', $this);
    }
}
