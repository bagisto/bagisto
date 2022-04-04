<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webkul\Ui\DataGrid\DataGrid;

class SitemapDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('sitemaps')->addSelect('id', 'file_name', 'path', 'path as url');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'width'      => '40px',
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'file_name',
            'label'      => trans('admin::app.datagrid.file-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'path',
            'label'      => trans('admin::app.datagrid.path'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'url',
            'label'      => trans('admin::app.datagrid.link-for-google'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => false,
            'filterable' => false,
            'closure'    => function ($row) {
                return '<a href="' . ($url = Storage::url($row->path . '/' . $row->file_name)) . '" target="_blank">' . $url . '</a>';
            },
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.sitemaps.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
            'route'  => 'admin.sitemaps.delete',
            'icon'   => 'icon trash-icon',
        ]);
    }
}