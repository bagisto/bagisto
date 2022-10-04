@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.tax-categories.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.settings.tax-categories.title') }}</h1>
            </div>

            <div class="page-action">
                @if (bouncer()->hasPermission('settings.taxes.tax-categories.create'))
                    <a href="{{ route('admin.tax_categories.create') }}" class="btn btn-lg btn-primary">
                        {{ __('admin::app.settings.tax-categories.add-title') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.tax_categories.index') }}"></datagrid-plus>
        </div>
    </div>
@stop
