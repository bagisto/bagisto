<?php

namespace Webkul\Ui\DataGrid\Traits;

trait ProvideTranslations
{
    public function getTranslations()
    {
        return [
            'allChannels' => trans('admin::app.admin.system.all-channels'),
            'allLocales' => __('admin::app.admin.system.all-locales'),
            'allCustomerGroups' => __('admin::app.admin.system.all-customer-groups'),
            'search' => __('ui::app.datagrid.search'),
            'itemsPerPage' => __('ui::app.datagrid.items-per-page'),
            'filter' => __('ui::app.datagrid.filter'),
            'column' => __('ui::app.datagrid.column'),
            'condition' => __('ui::app.datagrid.condition'),
            'contains' => __('ui::app.datagrid.contains'),
            'ncontains' => __('ui::app.datagrid.ncontains'),
            'equals' => __('ui::app.datagrid.equals'),
            'nequals' => __('ui::app.datagrid.nequals'),
            'greater' => __('ui::app.datagrid.greater'),
            'less' => __('ui::app.datagrid.less'),
            'greatere' => __('ui::app.datagrid.greatere'),
            'lesse' => __('ui::app.datagrid.lesse'),
            'true' => __('ui::app.datagrid.true'),
            'false' => __('ui::app.datagrid.false'),
            'value' => __('ui::app.datagrid.value'),
            'valueHere' => __('ui::app.datagrid.value-here'),
            'numericValueHere' => __('ui::app.datagrid.numeric-value-here'),
            'apply' => __('ui::app.datagrid.apply'),
            'submit' => __('ui::app.datagrid.submit'),
            'actions' => __('ui::app.datagrid.actions'),
            'filterFieldsMissing' => __('ui::app.datagrid.filter-fields-missing'),
            'zeroIndex' => __('ui::app.datagrid.zero-index'),
            'clickOnAction' => __('ui::app.datagrid.click_on_action'),
            'norecords' => __('ui::app.datagrid.no-records'),
        ];
    }
}