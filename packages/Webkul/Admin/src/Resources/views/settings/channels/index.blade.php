@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.channels.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.settings.channels.title') }}</h1>
            </div>

            <div class="page-action">
                @if (bouncer()->hasPermission('settings.channels.create'))
                    <a href="{{ route('admin.channels.create') }}" class="btn btn-lg btn-primary">
                        {{ __('admin::app.settings.channels.add-title') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.channels.index') }}"></datagrid-plus>
        </div>
    </div>
@stop
