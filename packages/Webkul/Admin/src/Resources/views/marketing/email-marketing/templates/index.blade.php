@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.marketing.templates.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.marketing.templates.title') }}</h1>
            </div>

            <div class="page-action">
                @if (bouncer()->hasPermission('marketing.email-marketing.email-templates.create'))
                    <a href="{{ route('admin.email-templates.create') }}" class="btn btn-lg btn-primary">
                        {{ __('admin::app.marketing.templates.add-title') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.email-templates.index') }}"></datagrid-plus>
        </div>
    </div>
@stop
