@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.locales.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.settings.locales.title') }}</h1>
            </div>

            <div class="page-action">
                @if (bouncer()->hasPermission('settings.locales.create'))
                    <a href="{{ route('admin.locales.create') }}" class="btn btn-lg btn-primary">
                        {{ __('admin::app.settings.locales.add-title') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.locales.index') }}"></datagrid-plus>
        </div>
    </div>
@stop
