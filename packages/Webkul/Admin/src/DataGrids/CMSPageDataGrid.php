<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

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
     * @return void
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

        $this->addFilter('id', 'cms_pages.id');

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
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'page_title',
            'label'      => trans('admin::app.cms.pages.page-title'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'url_key',
            'label'      => trans('admin::app.datagrid.url-key'),
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
            'icon'   => 'icon eye-icon',
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.cms.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
            'route'  => 'admin.cms.delete',
            'icon'   => 'icon trash-icon',
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
            'label'  => trans('admin::app.datagrid.delete'),
            'action' => route('admin.cms.mass_delete'),
            'method' => 'POST',
        ]);
    }
}
