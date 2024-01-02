<?php

namespace Webkul\Admin\DataGrids\Marketing\SearchSEO;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class SearchTermDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('search_terms')
            ->addSelect(
                'search_terms.id',
                'search_terms.term',
                'search_terms.results',
                'search_terms.uses',
                'search_terms.redirect_url',
                'search_terms.channel_id',
                'channel_translations.name as channel_name',
                'search_terms.locale',
            )
            ->leftJoin('channel_translations', function ($leftJoin) {
                $leftJoin->on('search_terms.channel_id', '=', 'channel_translations.channel_id')
                    ->where('channel_translations.locale', app()->getLocale());
            });

        $this->addFilter('channel_id', 'search_terms.channel_id');
        $this->addFilter('locale', 'search_terms.locale');

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
            'label'      => trans('admin::app.marketing.search-seo.search-terms.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'width'      => '40px',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'term',
            'label'      => trans('admin::app.marketing.search-seo.search-terms.index.datagrid.search-query'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'results',
            'label'      => trans('admin::app.marketing.search-seo.search-terms.index.datagrid.results'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'uses',
            'label'      => trans('admin::app.marketing.search-seo.search-terms.index.datagrid.uses'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'redirect_url',
            'label'      => trans('admin::app.marketing.search-seo.search-terms.index.datagrid.redirect-url'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'channel_id',
            'label'      => trans('admin::app.marketing.search-seo.search-terms.index.datagrid.channel'),
            'type'       => 'dropdown',
            'options'    => [
                'type' => 'basic',

                'params' => [
                    'options' => core()->getAllChannels()
                        ->map(fn ($channel) => ['label' => $channel->name, 'value' => $channel->id])
                        ->values()
                        ->toArray(),
                ],
            ],
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'locale',
            'label'      => trans('admin::app.marketing.search-seo.search-terms.index.datagrid.locale'),
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
        if (bouncer()->hasPermission('marketing.search_terms.edit')) {
            $this->addAction([
                'index'  => 'edit',
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.marketing.search-seo.search-terms.index.datagrid.edit'),
                'method' => 'GET',
                'route'  => 'admin.marketing.search_seo.search_terms.update',
                'url'    => function ($row) {
                    return route('admin.marketing.search_seo.search_terms.update', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('marketing.search_terms.delete')) {
            $this->addAction([
                'index'  => 'delete',
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.marketing.search-seo.search-terms.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.marketing.search_seo.search_terms.delete', $row->id);
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
        if (bouncer()->hasPermission('marketing.search_terms.delete')) {
            $this->addMassAction([
                'title'  => trans('admin::app.marketing.search-seo.search-terms.index.datagrid.delete'),
                'method' => 'POST',
                'url'    => route('admin.marketing.search_seo.search_terms.mass_delete'),
            ]);
        }
    }
}
