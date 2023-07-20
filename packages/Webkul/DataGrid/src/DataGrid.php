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
     * Add column.
     *
     * @param  array  $column
     * @return void
     */
    public function addColumn($column)
    {
        if ($column['type'] === 'date_range') {
            $column['options'] = [
                'today' => [
                    'from' => now()->today()->format('Y-m-d'),
                    'to' => now()->today()->format('Y-m-d'),
                ],

                'yesterday' => [
                    'from' => now()->yesterday()->format('Y-m-d'),
                    'to' => now()->yesterday()->format('Y-m-d'),
                ],

                'this_week' => [
                    'from' => now()->startOfWeek()->format('Y-m-d'),
                    'to' => now()->endOfWeek()->format('Y-m-d'),
                ],

                'this_month' => [
                    'from' => now()->startOfMonth()->format('Y-m-d'),
                    'to' => now()->endOfMonth()->format('Y-m-d'),
                ],

                'last_month' => [
                    'from' => now()->subMonth(1)->startOfMonth()->format('Y-m-d'),
                    'to' => now()->subMonth(1)->endOfMonth()->format('Y-m-d'),
                ],

                'last_three_months' => [
                    'from' => now()->subMonth(3)->startOfMonth()->format('Y-m-d'),
                    'to' => now()->subMonth(1)->endOfMonth()->format('Y-m-d'),
                ],

                'last_six_months' => [
                    'from' => now()->subMonth(6)->startOfMonth()->format('Y-m-d'),
                    'to' => now()->subMonth(1)->endOfMonth()->format('Y-m-d'),
                ],

                'this_year' => [
                    'from' => now()->startOfYear()->format('Y-m-d'),
                    'to' => now()->endOfYear()->format('Y-m-d'),
                ],
            ];
        } elseif ($column['type'] === 'datetime_range') {
            $column['options'] = [
                'today' => [
                    'from' => now()->today()->startOfDay()->format('Y-m-d H:i:s'),
                    'to' => now()->today()->endOfDay()->format('Y-m-d H:i:s'),
                ],

                'yesterday' => [
                    'from' => now()->yesterday()->startOfDay()->format('Y-m-d H:i:s'),
                    'to' => now()->yesterday()->endOfDay()->format('Y-m-d H:i:s'),
                ],

                'this_week' => [
                    'from' => now()->startOfWeek()->format('Y-m-d H:i:s'),
                    'to' => now()->endOfWeek()->format('Y-m-d H:i:s'),
                ],

                'this_month' => [
                    'from' => now()->startOfMonth()->format('Y-m-d H:i:s'),
                    'to' => now()->endOfMonth()->format('Y-m-d H:i:s'),
                ],

                'last_month' => [
                    'from' => now()->subMonth(1)->startOfMonth()->format('Y-m-d H:i:s'),
                    'to' => now()->subMonth(1)->endOfMonth()->format('Y-m-d H:i:s'),
                ],

                'last_three_months' => [
                    'from' => now()->subMonth(3)->startOfMonth()->format('Y-m-d H:i:s'),
                    'to' => now()->subMonth(1)->endOfMonth()->format('Y-m-d H:i:s'),
                ],

                'last_six_months' => [
                    'from' => now()->subMonth(6)->startOfMonth()->format('Y-m-d H:i:s'),
                    'to' => now()->subMonth(1)->endOfMonth()->format('Y-m-d H:i:s'),
                ],

                'this_year' => [
                    'from' => now()->startOfYear()->format('Y-m-d H:i:s'),
                    'to' => now()->endOfYear()->format('Y-m-d H:i:s'),
                ],
            ];
        }

        $this->columns[] = $column;
    }

    /**
     * Add action.
     *
     * @param  array  $action
     * @param  bool  $specialPermission
     * @return void
     */
    public function addAction($action, $specialPermission = false)
    {
        $this->checkPermissions($action, $specialPermission, function ($action) {
            $this->actions[] = $action;
        });
    }

    /**
     * Add mass action. Some datagrids are used in shops also. So extra
     * parameters is their. If needs to give an access just pass true
     * in second param.
     *
     * @param  array  $massAction
     * @param  bool  $specialPermission
     * @return void
     */
    public function addMassAction($massAction, $specialPermission = false)
    {
        $massAction['route'] = $this->getRouteNameFromUrl($massAction['action'], $massAction['method']);

        $this->checkPermissions($massAction, $specialPermission, function ($action) {
            $this->massActions[] = $action;
        }, 'label');
    }

    /**
     * Check permissions.
     *
     * @param  array  $action
     * @param  bool  $specialPermission
     * @param  \Closure  $operation
     * @return void
     */
    private function checkPermissions($action, $specialPermission, $operation)
    {
        $currentRouteACL = $this->fetchCurrentRouteACL($action);

        if (
            bouncer()->hasPermission($currentRouteACL['key'] ?? null)
            || $specialPermission
        ) {
            $operation($action);
        }
    }

    /**
     * Fetch current route acl. As no access to acl key, this will fetch acl by route name.
     *
     * @return array
     */
    private function fetchCurrentRouteACL($action)
    {
        return collect(config('acl'))->filter(function ($acl) use ($action) {
            return $acl['route'] === $action['route'];
        })->first();
    }

    /**
     * Fetch route name from full url, not the current one.
     *
     * @return array
     */
    private function getRouteNameFromUrl($action, $method)
    {
        return app('router')->getRoutes()
            ->match(app('request')->create(str_replace(url('/'), '', $action), $method))
            ->getName();
    }

    /**
     * Prepare data for json response.
     *
     * @return array
     */
    public function prepareData()
    {
        // dd(request()->all());
        // refactor
        $queryBuilder = $this->queryBuilder;

        $filters = request('filters', []);

        foreach ($filters as $column => $values) {
            if ($column === 'all') {
                $queryBuilder->where(function ($scopeQueryBuilder) use ($column, $values) {
                    foreach ($values as $value) {
                        collect($this->columns)
                            ->filter(fn ($column) => $column['searchable'] && $column['type'] !== 'boolean')
                            ->each(fn ($column) => $scopeQueryBuilder->orWhere($column['index'], $value));
                    }
                });
            } else {
                $foundColumn = collect($this->columns)->first(fn($c) => $c['index'] === $column);

                switch ($foundColumn['type']) {
                    case'date_range':
                    case'datetime_range':
                        $queryBuilder->where(function ($scopeQueryBuilder) use ($column, $values) {
                            foreach ($values as $value) {
                                $scopeQueryBuilder->whereBetween($column, [$value[0] ?? '', $value[1] ?? '']);
                            }
                        });
                        break;

                    default:
                        $queryBuilder->where(function ($scopeQueryBuilder) use ($column, $values) {
                            foreach ($values as $value) {
                                $scopeQueryBuilder->orWhere($column, $value);
                            }
                        });
                        break;
                }
            }
        }

        $queryBuilder->orderBy(request('sort_by', $this->primaryColumn), request('sort_order', $this->sortOrder));

        $paginator = $queryBuilder->paginate($this->itemsPerPage)->toArray();

        // refactor
        return [
            'columns' => $this->columns,

            'actions' => $this->actions,

            'mass_actions' => $this->massActions,

            'records' => $paginator['data'],

            'links' => [
                'first'    => $paginator['first_page_url'],
                'last'     => $paginator['last_page_url'],
                'previous' => $paginator['prev_page_url'],
                'next'     => $paginator['next_page_url'],
            ],

            'meta' => [
                'from'             => $paginator['from'],
                'to'               => $paginator['to'],
                'total'            => $paginator['total'],
                'per_page_options' => [10, 20, 30, 40, 50],
                'per_page'         => $paginator['per_page'],
                'current_page'     => $paginator['current_page'],
                'last_page'        => $paginator['last_page'],
                'links'            => $paginator['links'],
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
}
