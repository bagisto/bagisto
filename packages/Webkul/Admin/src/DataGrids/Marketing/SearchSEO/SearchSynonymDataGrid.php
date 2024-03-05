<?php

namespace Webkul\Admin\DataGrids\Marketing\SearchSEO;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class SearchSynonymDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        return DB::table('search_synonyms')
            ->addSelect(
                'id',
                'name',
                'terms',
            );
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
            'label'      => trans('admin::app.marketing.search-seo.search-synonyms.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'width'      => '40px',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.marketing.search-seo.search-synonyms.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'terms',
            'label'      => trans('admin::app.marketing.search-seo.search-synonyms.index.datagrid.terms'),
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
        if (bouncer()->hasPermission('marketing.search_synonyms.edit')) {
            $this->addAction([
                'index'  => 'edit',
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.marketing.search-seo.search-synonyms.index.datagrid.edit'),
                'method' => 'GET',
                'route'  => 'admin.marketing.search_seo.search_synonyms.update',
                'url'    => function ($row) {
                    return route('admin.marketing.search_seo.search_synonyms.update', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('marketing.search_synonyms.delete')) {
            $this->addAction([
                'index'  => 'delete',
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.marketing.search-seo.search-synonyms.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.marketing.search_seo.search_synonyms.delete', $row->id);
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
        if (bouncer()->hasPermission('marketing.search_synonyms.delete')) {
            $this->addMassAction([
                'title'  => trans('admin::app.marketing.search-seo.search-synonyms.index.datagrid.delete'),
                'method' => 'POST',
                'url'    => route('admin.marketing.search_seo.search_synonyms.mass_delete'),
            ]);
        }
    }
}
