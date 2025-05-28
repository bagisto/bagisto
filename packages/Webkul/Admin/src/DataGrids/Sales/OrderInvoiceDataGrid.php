<?php

namespace Webkul\Admin\DataGrids\Sales;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\Sales\Models\Invoice;

class OrderInvoiceDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $tablePrefix = DB::getTablePrefix();

        $queryBuilder = DB::table('invoices')
            ->leftJoin('orders', 'invoices.order_id', '=', 'orders.id')
            ->select(
                'invoices.id as id',
                'orders.increment_id as order_id',
                'invoices.state as state',
                'invoices.base_grand_total as base_grand_total',
                'invoices.created_at as created_at'
            )
            ->selectRaw("CASE WHEN {$tablePrefix}invoices.increment_id IS NOT NULL THEN {$tablePrefix}invoices.increment_id ELSE {$tablePrefix}invoices.id END AS increment_id");

        $this->addFilter('increment_id', 'invoices.increment_id');
        $this->addFilter('order_id', 'orders.increment_id');
        $this->addFilter('base_grand_total', 'invoices.base_grand_total');
        $this->addFilter('created_at', 'invoices.created_at');

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
            'index'      => 'increment_id',
            'label'      => trans('admin::app.sales.invoices.index.datagrid.id'),
            'type'       => 'string',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'order_id',
            'label'      => trans('admin::app.sales.invoices.index.datagrid.order-id'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'base_grand_total',
            'label'      => trans('admin::app.sales.invoices.index.datagrid.grand-total'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                return core()->formatBasePrice($row->base_grand_total);
            },
        ]);

        $this->addColumn([
            'index'      => 'state',
            'label'      => trans('admin::app.sales.invoices.index.datagrid.status'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                $dueDuration = core()->getConfigData('sales.invoice_settings.payment_terms.due_duration');

                $todayDate = Carbon::now();

                $dueDate = Carbon::parse($row->created_at)->addDays($dueDuration);

                if ($row->state == Invoice::STATUS_PAID) {
                    return '<p class="label-active">'.trans('admin::app.sales.invoices.index.datagrid.paid').'</p>';
                }

                if (
                    $row->state == Invoice::STATUS_PENDING
                    || $row->state == Invoice::STATUS_PENDING_PAYMENT
                ) {
                    $daysLeft = $todayDate->diffInDays($dueDate, false);

                    if ($daysLeft >= 0) {
                        $extra = trans('admin::app.sales.invoices.index.datagrid.days-left', ['count' => $daysLeft]);
                    } else {
                        $extra = trans('admin::app.sales.invoices.index.datagrid.overdue-by', ['count' => abs($daysLeft)]);
                    }

                    return '<div class="flex flex-col gap-1"><p class="label-pending">'.trans('admin::app.sales.invoices.index.datagrid.pending').'</p><p class="block text-xs italic leading-5 text-red-600 dark:text-gray-300">'.$extra.'</p></div>';
                }

                if ($row->state == Invoice::STATUS_OVERDUE) {
                    $daysOverdue = $dueDate->diffInDays($todayDate, false);

                    if ($daysOverdue >= 0) {
                        $extra = trans('admin::app.sales.invoices.index.datagrid.days-overdue', ['count' => $daysOverdue]);
                    } else {
                        $extra = '';
                    }

                    return '<div class="flex flex-col gap-1"><p class="label-canceled">'.trans('admin::app.sales.invoices.index.datagrid.overdue').'</p><p class="block text-xs italic leading-5 text-red-600 dark:text-gray-300">'.$extra.'</p></div>';
                }

                return $row->state;
            },
        ]);

        $this->addColumn([
            'index'           => 'created_at',
            'label'           => trans('admin::app.sales.invoices.index.datagrid.invoice-date'),
            'type'            => 'date',
            'searchable'      => true,
            'filterable'      => true,
            'filterable_type' => 'date_range',
            'sortable'        => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('sales.invoices.view')) {
            $this->addAction([
                'icon'   => 'icon-view',
                'title'  => trans('admin::app.sales.invoices.index.datagrid.view'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.sales.invoices.view', $row->id);
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
        $this->addMassAction([
            'title'   => trans('admin::app.sales.invoices.index.datagrid.update-status'),
            'url'     => route('admin.sales.invoices.mass_update.state'),
            'method'  => 'POST',
            'options' => [
                [
                    'label' => trans('admin::app.sales.invoices.index.datagrid.pending'),
                    'value' => Invoice::STATUS_PENDING,
                ],
                [
                    'label' => trans('admin::app.sales.invoices.index.datagrid.paid'),
                    'value' => Invoice::STATUS_PAID,
                ],
                [
                    'label' => trans('admin::app.sales.invoices.index.datagrid.overdue'),
                    'value' => Invoice::STATUS_OVERDUE,
                ],
            ],
        ]);
    }
}
