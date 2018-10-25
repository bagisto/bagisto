@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.sales.orders.title') }}
@stop

@section('content')
    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.sales.orders.title') }}</h1>
            </div>

            <div class="page-action">
            </div>
        </div>

        <div class="page-content">
            @inject('orderGrid', 'Webkul\Admin\DataGrids\OrderDataGrid')
            {!! $orderGrid->render() !!}
        </div>
    </div>
@stop