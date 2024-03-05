<?php

namespace Webkul\Admin\DataGrids\Marketing\SearchSEO;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class URLRewriteDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('url_rewrites')
            ->addSelect(
                'url_rewrites.id',
                'url_rewrites.entity_type',
                'url_rewrites.request_path',
                'url_rewrites.target_path',
                'url_rewrites.redirect_type',
                'url_rewrites.locale',
            );

        $this->addFilter('locale', 'url_rewrites.locale');

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
            'label'      => trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'width'      => '40px',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'entity_type',
            'label'      => trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.for'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'request_path',
            'label'      => trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.request-path'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'target_path',
            'label'      => trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.target-path'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'redirect_type',
            'label'      => trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.redirect-type'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'locale',
            'label'      => trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.locale'),
            'type'       => 'dropdown',
            'options'    => [
                'type' => 'basic',

                'params' => [
                    'options' => core()->getAllLocales()
                        ->map(fn ($locale) => ['label' => $locale->name, 'value' => $locale->code])
                        ->values()
                        ->toArray(),
                ],
            ],
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('marketing.url_rewrites.edit')) {
            $this->addAction([
                'index'  => 'edit',
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.edit'),
                'method' => 'GET',
                'route'  => 'admin.marketing.search_seo.url_rewrites.update',
                'url'    => function ($row) {
                    return route('admin.marketing.search_seo.url_rewrites.update', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('marketing.url_rewrites.delete')) {
            $this->addAction([
                'index'  => 'delete',
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.marketing.search_seo.url_rewrites.delete', $row->id);
                },
            ]);
        }
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {
        if (bouncer()->hasPermission('marketing.url_rewrites.delete')) {
            $this->addMassAction([
                'title'  => trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.delete'),
                'method' => 'POST',
                'url'    => route('admin.marketing.search_seo.url_rewrites.mass_delete'),
            ]);
        }
    }
}
