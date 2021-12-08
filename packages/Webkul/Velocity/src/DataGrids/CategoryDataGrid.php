<?php

namespace Webkul\Velocity\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use Webkul\Velocity\Models\Category;

class CategoryDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    protected string $index = 'category_menu_id';

    /**
     * Sort order.
     *
     * @var string
     */
    protected string $sortOrder = 'desc';

    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder(): void
    {
        $defaultChannel = core()->getCurrentChannel();

        $queryBuilder = Category::from('velocity_category as v_cat')
            ->select('v_cat.id as category_menu_id', 'v_cat.category_id', 'ct.name', 'v_cat.icon', 'v_cat.tooltip', 'v_cat.status')
            ->leftJoin('categories as c', 'c.id', '=', 'v_cat.category_id')
            ->leftJoin('category_translations as ct', function ($leftJoin) {
                $leftJoin->on('c.id', '=', 'ct.category_id')
                    ->where('ct.locale', app()->getLocale());
            })
            ->where('c.parent_id', $defaultChannel->root_category_id)
            ->groupBy('v_cat.id');

        $this->setQueryBuilder($queryBuilder);
    }

	/**
	 * Add columns.
	 *
	 * @throws \Webkul\Ui\Exceptions\ColumnKeyException add column failed
	 * @return void
	 */
    public function addColumns(): void
    {
        $this->addColumn([
            'index'      => 'category_id',
            'label'      => trans('velocity::app.admin.category.datagrid.category-id'),
            'type'       => 'number',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('velocity::app.admin.category.datagrid.category-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'icon',
            'label'      => trans('velocity::app.admin.category.datagrid.category-icon'),
            'type'       => 'string',
            'sortable'   => true,
            'searchable' => true,
            'filterable' => true,
            'closure'    => function ($row) {
                return '<span class="wk-icon ' . $row->icon . '"></span>';
            },
        ]);

		$this->addColumn([
			'index'      => 'status',
			'label'      => trans('velocity::app.admin.category.datagrid.category-status'),
			'type'       => 'string',
			'sortable'   => true,
			'searchable' => true,
			'filterable' => true,
			'closure'    => function ($row) {
				return $row->status
					? '<span class="badge badge-md badge-success">' . trans('velocity::app.admin.category.enabled')  . '</span>'
					: '<span class="badge badge-md badge-danger">'  . trans('velocity::app.admin.category.disabled') . '</span>';
			},
		]);
    }

	/**
	 * Prepare actions.
	 *
	 * @throws \Webkul\Ui\Exceptions\ActionKeyException add action failed
	 * @return void
	 */
    public function prepareActions(): void
    {
        $this->addAction([
            'title'  => trans('ui::app.datagrid.edit'),
            'type'   => 'Edit',
            'method' => 'GET',
            'route'  => 'velocity.admin.category.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'        => trans('ui::app.datagrid.delete'),
            'type'         => 'Delete',
            'method'       => 'POST',
            'route'        => 'velocity.admin.category.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'Category']),
            'icon'         => 'icon trash-icon',
        ]);
    }

	/**
	 * Prepare mass actions.
	 *
	 * @return void
	 */
	public function prepareMassActions(): void
	{
		$this->addMassAction([
			'type'   => 'delete',
			'action' => route('velocity.admin.category.mass-delete'),
			'label'  => trans('admin::app.datagrid.delete'),
			'method' => 'POST',
		]);
	}
}
