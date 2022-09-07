@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.users.roles.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.users.roles.title') }}</h1>
            </div>

            <div class="page-action">
                @if (bouncer()->hasPermission('settings.users.roles.create')) 
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-lg btn-primary">
                        {{ __('admin::app.users.roles.add-role-title') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.roles.index') }}"></datagrid-plus>
        </div>
    </div>
@stop
