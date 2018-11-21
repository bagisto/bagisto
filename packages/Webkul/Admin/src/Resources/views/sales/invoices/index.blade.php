@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.sales.invoices.title') }}
@stop

@section('content')
    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.sales.invoices.title') }}</h1>
            </div>

            <div class="page-action">
            </div>
        </div>

        <div class="page-content">
            @inject('orderInvoicesGrid', 'Webkul\Admin\DataGrids\OrderInvoicesDataGrid')
            {!! $orderInvoicesGrid->render() !!}
        </div>
    </div>
@stop