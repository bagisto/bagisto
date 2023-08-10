<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CMSPageDataGrid extends DataGrid
{
     /**
     * Contains the keys for which extra filters to show.
     *
     * @var string[]
     */
    protected $extraFilters = [
        'locales',
    ];

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $locale = core()->getRequestedLocaleCode();

        $whereInLocales = $locale === 'all'
            ? core()->getAllLocales()->pluck('code')->toArray()
            : [$locale];

        $queryBuilder = DB::table('cms_pages')
            ->select('cms_pages.id', 'cms_page_translations.page_title', 'cms_page_translations.url_key')
            ->join('cms_page_translations', function ($leftJoin) use ($whereInLocales) {
                $leftJoin->on('cms_pages.id', '=', 'cms_page_translations.cms_page_id')
                    ->whereIn('cms_page_translations.locale', $whereInLocales);
            });

        // $this->addFilter('id', 'cms_pages.id');

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
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'page_title',
            'label'      => trans('admin::app.datagrid.page_title'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'url_key',
            'label'      => trans('admin::app.datagrid.url_key'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
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
            'title'  => trans('admin::app.datagrid.view'),
            'method' => 'GET',
            'route'  => 'shop.cms.page',
            'index'  => 'url_key',
            'target' => '_blank',
            'url'    => function ($row) {
                return route('shop.cms.page', $row->url_key);
            },
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.cms.edit',
            'url'    => function ($row) {
                return route('admin.cms.edit', $row->id);
            },
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
            'route'  => 'admin.cms.delete',
            'url'    => function ($row) {
                return route('admin.cms.delete', $row->id);
            },
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
            'label'  => trans('admin::app.cms.datagrid.delete'),
            'action' => route('admin.cms.mass_delete'),
            'method' => 'POST',
        ]);
    }
}
