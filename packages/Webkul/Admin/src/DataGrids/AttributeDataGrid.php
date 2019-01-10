<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\AbsGrid;
use DB;

/**
 * Product Data Grid class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AttributeDataGrid extends AbsGrid
{
    public $allColumns = [];

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('attributes')->select('id')->addSelect($this->columns);

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'column' => 'id',
            'alias' => 'attributeId',
            'label' => 'ID',
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            'column' => 'code',
            'alias' => 'attributeCode',
            'label' => 'Code',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'column' => 'admin_name',
            'alias' => 'attributeAdminName',
            'label' => 'Name',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'column' => 'type',
            'alias' => 'attributeType',
            'label' => 'Type',
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'column' => 'is_required',
            'alias' => 'attributeRequired',
            'label' => 'Required',
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'width' => '100px'
        ]);

        $this->addColumn([
            'column' => 'is_unique',
            'alias' => 'attributeIsUnique',
            'label' => 'Unique',
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'width' => '100px'
        ]);

        $this->addColumn([
            'column' => 'value_per_locale',
            'alias' => 'attributeValuePerLocale',
            'label' => 'Locale Based',
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'width' => '100px'
        ]);

        $this->addColumn([
            'column' => 'value_per_channel',
            'alias' => 'attributeValuePerChannel',
            'label' => 'Channel Based',
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'width' => '100px'
        ]);
    }

    public function prepareActions() {
        $this->prepareAction([
            'type' => 'Edit',
            'route' => 'admin.catalog.attributes.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->prepareAction([
            'type' => 'Delete',
            'route' => 'admin.catalog.attributes.delete',
            'icon' => 'icon trash-icon'
        ]);
    }

    public function prepareMassActions() {
        $this->prepareMassAction([
            'type' => 'delete',
            'action' => route('admin.catalog.attributes.massdelete'),
            'method' => 'DELETE'
        ]);
    }

    public function render()
    {
        $this->addColumns();

        $this->prepareActions();

        $this->prepareMassActions();

        $this->prepareQueryBuilder();

        return view('ui::testgrid.table')->with('results', ['records' => $this->getCollection(), 'columns' => $this->allColumns, 'actions' => $this->actions, 'massactions' => $this->massActions]);
    }
}