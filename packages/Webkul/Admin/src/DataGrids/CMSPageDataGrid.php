<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * CMSPagesDataGrid class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CMSPageDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('cms_pages')
            ->select('cms_pages.id', 'cms_page_translations.page_title', 'cms_page_translations.url_key')
            ->leftJoin('cms_page_translations', function($leftJoin) {
                $leftJoin->on('cms_pages.id', '=', 'cms_page_translations.cms_page_id')
                         ->where('cms_page_translations.locale', app()->getLocale());
            });

        $this->addFilter('id', 'cms_pages.id');

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

    public function prepareActions() {
        $this->addAction([
            'title'  => 'Edit CMSPage',
            'method' => 'GET',
            'route'  => 'admin.cms.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'  => 'Delete CMSPage',
            'method' => 'POST',
            'route'  => 'admin.cms.delete',
            'icon'   => 'icon trash-icon',
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type'   => 'delete',
            'label'  => trans('admin::app.datagrid.delete'),
            'action' => route('admin.cms.mass-delete'),
            'method' => 'DELETE',
        ]);
    }
}