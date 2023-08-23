<?php

namespace Webkul\DataGrid;

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
     * Map your filter.
     */
    public function addFilter(string $datagridColumn, string $queryColumn): void
    {
        foreach ($this->columns as &$column) {
            if ($column['index'] === $datagridColumn) {
                $column['column_name'] = $queryColumn;
                break;
            }
        }
    }

    /**
     * Add column.
     *
     * @param  array  $column
     * @return void
     */
    public function addColumn($column)
    {
        $column['column_name'] = $column['index'];

        if ($column['type'] === 'date_range') {
            $column['input_type'] = 'date';

            $column['options'] = $this->getDateOptions();
        } elseif ($column['type'] === 'datetime_range') {
            $column['input_type'] = 'datetime-local';

            $column['options'] = $this->getDateOptions('Y-m-d H:i:s');
        }

        $this->columns[] = $column;
    }

    /**
     * Add action.
     */
    public function addAction(array $action): void
    {
        $this->actions[] = $action;
    }

    /**
     * Add mass action.
     */
    public function addMassAction(array $massAction): void
    {
        $this->massActions[] = $massAction;
    }

    /**
     * Prepare data for json response.
     *
     * @return array
     */
    public function prepareData()
    {
        // need to refactor
        $queryBuilder = $this->queryBuilder;

        $requestedFilters = request('filters', []);

        foreach ($requestedFilters as $requestedColumn => $requestedValues) {
            if ($requestedColumn === 'all') {
                $queryBuilder->where(function ($scopeQueryBuilder) use ($requestedValues) {
                    foreach ($requestedValues as $value) {
                        collect($this->columns)
                            ->filter(fn ($column) => $column['searchable'] && $column['type'] !== 'boolean')
                            ->each(fn ($column) => $scopeQueryBuilder->orWhere($column['column_name'], $value));
                    }
                });
            } else {
                $column = collect($this->columns)->first(fn ($c) => $c['index'] === $requestedColumn);

                switch ($column['type']) {
                    case 'date_range':
                    case 'datetime_range':
                        $queryBuilder->where(function ($scopeQueryBuilder) use ($column, $requestedValues) {
                            foreach ($requestedValues as $value) {
                                $scopeQueryBuilder->whereBetween($column['column_name'], [$value[0] ?? '', $value[1] ?? '']);
                            }
                        });
                        break;

                    default:
                        $queryBuilder->where(function ($scopeQueryBuilder) use ($column, $requestedValues) {
                            foreach ($requestedValues as $value) {
                                $scopeQueryBuilder->orWhere($column['column_name'], $value);
                            }
                        });
                        break;
                }
            }
        }

        // need to make good search column method, currently this is working but still need work here...
        $queryBuilder->orderBy(request('sort.column', $this->primaryColumn), request('sort.order', $this->sortOrder));

        $paginator = $queryBuilder->paginate(
            request('pagination.per_page', $this->itemsPerPage),
            ['*'],
            'page',
            request('pagination.page', 1)
        )->toArray();

        foreach ($paginator['data'] as $data) {
            foreach ($this->columns as $column) {
                if (isset($column['closure'])) {
                    $data->{$column['index']} = $column['closure']($data);
                    
                    $data->is_closure = true;
                }
            }

            $data->actions = [];

            foreach ($this->actions as $action) {
                $data->actions[] = [
                    ...$action,

                    'url' => $action['url']($data),
                ];
            }
        }

        // refactor
        return [
            'columns' => $this->columns,

            'actions' => $this->actions,

            'mass_actions' => $this->massActions,

            'records' => $paginator['data'],

            'meta' => [
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
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function toJson()
    {
        $this->prepareColumns();

        $this->prepareActions();

        $this->prepareMassActions();

        $this->queryBuilder = $this->prepareQueryBuilder();

        return response()->json($this->prepareData());
    }

    /**
     * Get date options.
     *
     * @return array
     */
    public function getDateOptions($format = 'Y-m-d')
    {
        return [
            [
                'name'  => 'today',
                'label' => 'Today',
                'from'  => now()->today()->format($format),
                'to'    => now()->today()->format($format),
            ],
            [
                'name'  => 'yesterday',
                'label' => 'Yesterday',
                'from'  => now()->yesterday()->format($format),
                'to'    => now()->yesterday()->format($format),
            ],
            [
                'name'  => 'this_week',
                'label' => 'This Week',
                'from'  => now()->startOfWeek()->format($format),
                'to'    => now()->endOfWeek()->format($format),
            ],
            [
                'name'  => 'this_month',
                'label' => 'This Month',
                'from'  => now()->startOfMonth()->format($format),
                'to'    => now()->endOfMonth()->format($format),
            ],
            [
                'name'  => 'last_month',
                'label' => 'Last Month',
                'from'  => now()->subMonth(1)->startOfMonth()->format($format),
                'to'    => now()->subMonth(1)->endOfMonth()->format($format),
            ],
            [
                'name'  => 'last_three_months',
                'label' => 'Last 3 Months',
                'from'  => now()->subMonth(3)->startOfMonth()->format($format),
                'to'    => now()->subMonth(1)->endOfMonth()->format($format),
            ],
            [
                'name'  => 'last_six_months',
                'label' => 'Last 6 Months',
                'from'  => now()->subMonth(6)->startOfMonth()->format($format),
                'to'    => now()->subMonth(1)->endOfMonth()->format($format),
            ],
            [
                'name'  => 'this_year',
                'label' => 'This Year',
                'from'  => now()->startOfYear()->format($format),
                'to'    => now()->endOfYear()->format($format),
            ],
        ];
    }
}
