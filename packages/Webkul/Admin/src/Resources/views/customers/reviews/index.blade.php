@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.reviews.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.customers.reviews.title') }}</h1>
            </div>

            <div class="page-action"></div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.customer.review.index') }}"></datagrid-plus>
        </div>
    </div>
@stop
