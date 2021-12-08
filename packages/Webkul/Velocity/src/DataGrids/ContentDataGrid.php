<?php

namespace Webkul\Velocity\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use Webkul\Velocity\Models\Content;

class ContentDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    protected string $index = 'content_id';

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
        $queryBuilder = Content::from('velocity_contents as con')
            ->select('con.id as content_id', 'con_trans.title', 'con.position', 'con.content_type', 'con.status')
            ->leftJoin('velocity_contents_translations as con_trans', function ($leftJoin) {
                $leftJoin->on('con.id', '=', 'con_trans.content_id')
                    ->where('con_trans.locale', app()->getLocale());
            })
            ->groupBy('con.id');

        $this->addFilter('content_id', 'con.id');
        $this->addFilter('status', 'con.status');

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
            'index'      => 'content_id',
            'label'      => trans('velocity::app.admin.contents.datagrid.id'),
            'type'       => 'number',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'title',
            'label'      => trans('velocity::app.admin.contents.datagrid.title'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'position',
            'label'      => trans('velocity::app.admin.contents.datagrid.position'),
            'type'       => 'number',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('velocity::app.admin.contents.datagrid.status'),
            'type'       => 'boolean',
            'sortable'   => true,
            'searchable' => false,
			'filterable' => true,
			'closure'    => function ($value) {
				return ((int) $value->status === 1)
					? trans('admin::app.admin.datagrid.active')
					: trans('admin::app.admin.datagrid.inactive');
			},
		]);

		$this->addColumn([
			'index'      => 'content_type',
			'label'      => trans('velocity::app.admin.contents.datagrid.content-type'),
			'type'       => 'string',
			'sortable'   => true,
			'searchable' => true,
			'filterable' => false,
			'closure'    => function ($value) {
				switch ( $value->content_type ) {
					case 'category':
						return trans('velocity::app.contents.datagrid.category-slug');
					case 'link':
						return trans('velocity::app.contents.datagrid.link');
					case 'product':
						return trans('velocity::app.contents.datagrid.product');
					case 'static':
						return trans('velocity::app.contents.datagrid.static');
					default:
						return '';
				}
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
            'route'  => 'velocity.admin.content.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'        => trans('ui::app.datagrid.delete'),
            'type'         => 'Delete',
            'method'       => 'POST',
            'route'        => 'velocity.admin.content.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'content']),
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
            'action' => route('velocity.admin.content.mass-delete'),
            'label'  => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
        ]);

        $this->addMassAction([
            'type'    => 'update',
            'label'   => trans('admin::app.datagrid.update-status'),
            'action'  => route('velocity.admin.content.mass-update'),
            'method'  => 'POST',
            'options' => [
				// TODO localize?
                'Active'   => 1,
                'Inactive' => 0,
            ],
        ]);
    }
}
