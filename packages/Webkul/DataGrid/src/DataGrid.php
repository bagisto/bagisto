<?php

namespace Webkul\DataGrid;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Webkul\DataGrid\Enums\ColumnTypeEnum;
use Webkul\DataGrid\Exports\DataGridExport;

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
     * Export file name.
     */
    protected string $exportFileName;

    /**
     * Export file format.
     */
    protected string $exportFileExtension = 'csv';

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
     * Set primary column.
     */
    public function setPrimaryColumn(string $primaryColumn): void
    {
        $this->primaryColumn = $primaryColumn;
    }

    /**
     * Get primary column.
     */
    public function getPrimaryColumn(): string
    {
        return $this->primaryColumn;
    }

    /**
     * Set sort column.
     */
    public function setSortColumn(string $sortColumn): void
    {
        $this->sortColumn = $sortColumn;
    }

    /**
     * Get sort column.
     */
    public function getSortColumn(): ?string
    {
        return $this->sortColumn;
    }

    /**
     * Set sort order.
     */
    public function setSortOrder(string $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * Get sort order.
     */
    public function getSortOrder(): string
    {
        return $this->sortOrder;
    }

    /**
     * Set items per page.
     */
    public function setItemsPerPage(int $itemsPerPage): void
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * Get items per page.
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * Set per page options.
     */
    public function setPerPageOptions(array $perPageOptions): void
    {
        $this->perPageOptions = $perPageOptions;
    }

    /**
     * Get per page options.
     */
    public function getPerPageOptions(): array
    {
        return $this->perPageOptions;
    }

    /**
     * Set columns.
     */
    public function setColumns(array $columns): void
    {
        $this->columns = $columns;
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
     * Get columns.
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Set actions.
     */
    public function setActions(array $actions): void
    {
        $this->actions = $actions;
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
     * Get actions.
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * Set mass actions.
     */
    public function setMassActions(array $massActions): void
    {
        $this->massActions = $massActions;
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
     * Get mass actions.
     */
    public function getMassActions(): array
    {
        return $this->massActions;
    }

    /**
     * Set query builder.
     *
     * @param  mixed  $queryBuilder
     */
    public function setQueryBuilder($queryBuilder): void
    {
        $this->queryBuilder = $queryBuilder;
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
        $this->exportable = $exportable;
    }

    /**
     * Get exportable.
     */
    public function getExportable(): bool
    {
        return $this->exportable;
    }

    /**
     * Is exportable.
     */
    public function isExportable(): bool
    {
        return $this->getExportable();
    }

    /**
     * Set export file name.
     */
    public function setExportFileName(string $exportFileName): void
    {
        $this->exportFileName = $exportFileName;
    }

    /**
     * Get export file name.
     */
    public function getExportFileName(): string
    {
        return $this->exportFileName;
    }

    /**
     * Set export file extension.
     */
    public function setExportFileExtension(string $exportFileExtension = 'csv'): void
    {
        $this->exportFileExtension = $exportFileExtension;
    }

    /**
     * Get export file extension.
     */
    public function getExportFileExtension(): string
    {
        return $this->exportFileExtension;
    }

    /**
     * Get exporter.
     */
    public function getExporter()
    {
        return new DataGridExport($this);
    }

    /**
     * Get export file name with extension.
     */
    public function getExportFileNameWithExtension(): string
    {
        return $this->getExportFileName().'.'.$this->getExportFileExtension();
    }

    /**
     * Download export file.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadExportFile()
    {
        return Excel::download($this->getExporter(), $this->getExportFileNameWithExtension());
    }

    /**
     * Process the datagrid.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
     */
    public function process()
    {
        $this->prepare();

        if ($this->isExportable()) {
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
        return $this->process();
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
     * Process requested filters.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function processRequestedFilters(array $requestedFilters)
    {
        $this->dispatchEvent('process_request.filters.before', $this);

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

        $this->dispatchEvent('process_request.filters.after', $this);
    }

    /**
     * Process requested sorting.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function processRequestedSorting($requestedSort)
    {
        $this->dispatchEvent('process_request.sorting.before', $this);

        if (! $this->sortColumn) {
            $this->sortColumn = $this->primaryColumn;
        }

        $this->queryBuilder->orderBy($requestedSort['column'] ?? $this->sortColumn, $requestedSort['order'] ?? $this->sortOrder);

        $this->dispatchEvent('process_request.sorting.after', $this);
    }

    /**
     * Process requested pagination.
     */
    protected function processRequestedPagination(array $requestedPagination): void
    {
        $this->dispatchEvent('process_request.paginated.before', $this);

        $this->paginator = $this->queryBuilder->paginate(
            $requestedPagination['per_page'] ?? $this->itemsPerPage,
            ['*'],
            'page',
            $requestedPagination['page'] ?? 1
        );

        $this->dispatchEvent('process_request.paginated.after', $this);
    }

    /**
     * Process requested export.
     */
    protected function processRequestedExport(string $exportFileExtension = 'csv'): void
    {
        $this->dispatchEvent('process_request.export.before', $this);

        $this->setExportable(true);

        $this->setExportFileName(Str::random(36));

        $this->setExportFileExtension($exportFileExtension);

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

        $this->processRequestedFilters($requestedParams['filters'] ?? []);

        $this->processRequestedSorting($requestedParams['sort'] ?? []);

        /**
         * The `export` parameter is validated as a boolean in the `validatedRequest`. An `empty` function will not work,
         * as it will always be treated as true because of "0" and "1".
         */
        isset($requestedParams['export']) && (bool) $requestedParams['export']
            ? $this->processRequestedExport($requestedParams['format'] ?? null)
            : $this->processRequestedPagination($requestedParams['pagination'] ?? []);

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

        /**
         * Prepare columns.
         */
        $this->dispatchEvent('columns.prepare.before', $this);

        $this->prepareColumns();

        $this->dispatchEvent('columns.prepare.after', $this);

        /**
         * Prepare actions.
         */
        $this->dispatchEvent('actions.prepare.before', $this);

        $this->prepareActions();

        $this->dispatchEvent('actions.prepare.after', $this);

        /**
         * Prepare mass actions.
         */
        $this->dispatchEvent('mass_actions.prepare.before', $this);

        $this->prepareMassActions();

        $this->dispatchEvent('mass_actions.prepare.after', $this);

        /**
         * Prepare query builder.
         */
        $this->dispatchEvent('query_builder.prepare.before', $this);

        $this->setQueryBuilder($this->prepareQueryBuilder());

        $this->dispatchEvent('query_builder.prepare.after', $this);

        /**
         * Process request.
         */
        $this->processRequest();

        $this->dispatchEvent('prepare.after', $this);
    }
}
