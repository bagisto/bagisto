<?php

namespace Webkul\Admin\DataGrids\CMS;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CMSPageDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $whereInLocales = core()->getRequestedLocaleCode() === 'all'
            ? core()->getAllLocales()->pluck('code')->toArray()
            : [core()->getRequestedLocaleCode()];

        $queryBuilder = DB::table('cms_pages')
            ->select(
                'cms_pages.id',
                'cms_page_translations.page_title',
                'cms_page_translations.url_key'
            )
            ->join('cms_page_translations', function ($leftJoin) use ($whereInLocales) {
                $leftJoin->on('cms_pages.id', '=', 'cms_page_translations.cms_page_id')
                    ->whereIn('cms_page_translations.locale', $whereInLocales);
            });

        $this->addFilter('id', 'cms_pages.id');

        return $queryBuilder;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.cms.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'page_title',
            'label'      => trans('admin::app.cms.index.datagrid.page-title'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'url_key',
            'label'      => trans('admin::app.cms.index.datagrid.url-key'),
            'type'       => 'string',
            'searchable' => true,
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
        $this->addAction([
            'icon'   => 'icon-view',
            'title'  => trans('admin::app.cms.index.datagrid.view'),
            'method' => 'GET',
            'index'  => 'url_key',
            'target' => '_blank',
            'url'    => function ($row) {
                return route('shop.cms.page', $row->url_key);
            },
        ]);

        if (bouncer()->hasPermission('cms.edit')) {
            $this->addAction([
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.cms.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.cms.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('cms.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.cms.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.cms.delete', $row->id);
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
        if (bouncer()->hasPermission('cms.mass-delete')) {
            $this->addMassAction([
                'title'  => trans('admin::app.cms.index.datagrid.delete'),
                'method' => 'POST',
                'url'    => route('admin.cms.mass_delete'),
            ]);
        }
    }
}
