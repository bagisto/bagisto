<?php

namespace Webkul\Admin\DataGrids;

use Webkul\CMS\Models\CmsPage;
use Webkul\Ui\DataGrid\DataGrid;

class CMSPageDataGrid extends DataGrid
{
    protected string $index = 'id';

    protected string $sortOrder = 'desc';

    public function prepareQueryBuilder(): void
    {
		$queryBuilder = CmsPage::query()
				   ->select('cms_pages.id', 'cms_page_translations.page_title', 'cms_page_translations.url_key')
				   ->leftJoin('cms_page_translations', function ($leftJoin) {
					   $leftJoin->on('cms_pages.id', '=', 'cms_page_translations.cms_page_id')
								->where('cms_page_translations.locale', app()->getLocale());
				   });

		$this->addFilter('id', 'cms_pages.id');

        $this->setQueryBuilder($queryBuilder);
    }

	/**
	 * @throws \Webkul\Ui\Exceptions\ColumnKeyException add column failed
	 */
	public function addColumns(): void
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
	 * @throws \Webkul\Ui\Exceptions\ActionKeyException add action failed
	 */
	public function prepareActions(): void
	{
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

    public function prepareMassActions(): void
    {
        $this->addMassAction([
            'type'   => 'delete',
            'label'  => trans('admin::app.datagrid.delete'),
            'action' => route('admin.cms.mass-delete'),
            'method' => 'POST',
        ]);
    }
}
