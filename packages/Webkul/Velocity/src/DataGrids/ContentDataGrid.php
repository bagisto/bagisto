<?php

namespace Webkul\Velocity\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class ContentDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    protected $index = 'content_id';

    /**
     * Sort order.
     *
     * @var string
     */
    protected $sortOrder = 'desc';

    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('velocity_contents as con')
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
     * @return void
     */
    public function addColumns()
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
                if ($value->status == 1) {
                    return 'Active';
                } else {
                    return 'Inactive';
                }
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
                if ($value->content_type == 'category') {
                    return 'Category Slug';
                } elseif ($value->content_type == 'link') {
                    return 'Link';
                } elseif ($value->content_type == 'product') {
                    return 'Product';
                } elseif ($value->content_type == 'static') {
                    return 'Static';
                }
            },
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
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
    public function prepareMassActions()
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
                'Active'   => 1,
                'Inactive' => 0,
            ],
        ]);
    }
}
