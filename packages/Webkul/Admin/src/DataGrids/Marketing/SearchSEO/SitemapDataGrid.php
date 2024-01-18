<?php

namespace Webkul\Admin\DataGrids\Marketing\SearchSEO;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webkul\DataGrid\DataGrid;

class SitemapDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('sitemaps')->addSelect('id', 'file_name', 'path', 'path as url');

        return $queryBuilder;
    }

    /**
     * Add Columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'width'      => '40px',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'file_name',
            'label'      => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.file-name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'path',
            'label'      => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.path'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'url',
            'label'      => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.link-for-google'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => false,
            'closure'    => function ($row) {
                return Storage::url($row->path.'/'.$row->file_name);
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
        if (bouncer()->hasPermission('marketing.sitemaps.edit')) {
            $this->addAction([
                'index'  => 'edit',
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.edit'),
                'method' => 'GET',
                'route'  => 'admin.marketing.search_seo.sitemaps.update',
                'url'    => function ($row) {
                    return route('admin.marketing.search_seo.sitemaps.update', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('marketing.sitemaps.delete')) {
            $this->addAction([
                'index'  => 'delete',
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.marketing.search_seo.sitemaps.delete', $row->id);
                },
            ]);
        }
    }
}
