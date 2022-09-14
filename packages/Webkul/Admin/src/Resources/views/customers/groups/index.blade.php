@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.groups.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.customers.groups.title') }}</h1>
            </div>

            <div class="page-action">
                @if (bouncer()->hasPermission('customers.groups.create'))
                    <a href="{{ route('admin.groups.create') }}" class="btn btn-lg btn-primary">
                        {{ __('admin::app.customers.groups.add-title') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.groups.index') }}"></datagrid-plus>
        </div>
    </div>
@stop
