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
    protected $index = 'id'; //the column that needs to be treated as index column

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('cms_pages')->select('id', 'url_key', 'page_title', 'channel_id', 'locale_id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $channels = app('Webkul\Core\Repositories\ChannelRepository');

        $locales = app('Webkul\Core\Repositories\LocaleRepository');

        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'url_key',
            'label' => trans('admin::app.datagrid.url-key'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'page_title',
            'label' => trans('admin::app.cms.pages.page-title'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'locale_id',
            'label' => trans('admin::app.cms.pages.locale'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'wrapper' => function($row) use($channels) {
                $channelCode = $channels->find($row->channel_id)->name;

                return $row->channel_id.' ('. $channelCode. ')';
            }
        ]);

        $this->addColumn([
            'index' => 'channel_id',
            'label' => trans('admin::app.cms.pages.channel'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'wrapper' => function($row) use($locales) {
                $localeCode = $locales->find($row->locale_id)->code;

                return $row->locale_id.' ('. $localeCode. ')';
            }
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'type' => 'Edit',
            'method' => 'GET', // use GET request only for redirect purposes
            'route' => 'admin.cms.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'type' => 'Delete',
            'method' => 'POST', // use GET request only for redirect purposes
            'route' => 'admin.cms.delete',
            'icon' => 'icon trash-icon'
        ]);
    }
}