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
            ->select(
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
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'              => 'entity_type',
            'label'              => trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.for'),
            'type'               => 'string',
            'searchable'         => false,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.product'),
                    'value' => 'product',
                ],
                [
                    'label' => trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.category'),
                    'value' => 'category',
                ],
                [
                    'label' => trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.cms-page'),
                    'value' => 'cms_page',
                ],
            ],
            'sortable'         => true,
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
            'index'              => 'redirect_type',
            'label'              => trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.redirect-type'),
            'type'               => 'string',
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.temporary-redirect'),
                    'value' => 302,
                ],
                [
                    'label' => trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.permanent-redirect'),
                    'value' => 301,
                ],
            ],
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'              => 'locale',
            'label'              => trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.locale'),
            'type'               => 'string',
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => core()->getAllLocales()
                ->map(fn ($locale) => ['label' => $locale->name, 'value' => $locale->code])
                ->values()
                ->toArray(),
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
        if (bouncer()->hasPermission('marketing.search_seo.url_rewrites.edit')) {
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

        if (bouncer()->hasPermission('marketing.search_seo.url_rewrites.delete')) {
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
        if (bouncer()->hasPermission('marketing.search_seo.url_rewrites.delete')) {
            $this->addMassAction([
                'title'  => trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.delete'),
                'method' => 'POST',
                'url'    => route('admin.marketing.search_seo.url_rewrites.mass_delete'),
            ]);
        }
    }
}
