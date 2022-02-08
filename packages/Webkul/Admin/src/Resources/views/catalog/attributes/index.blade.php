@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.attributes.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.catalog.attributes.title') }}</h1>
            </div>

            <div class="page-action">
                <a
                    href="{{ route('admin.catalog.attributes.create') }}"
                    class="btn btn-lg btn-primary"
                >
                    {{ __('admin::app.catalog.attributes.add-title') }}
                </a>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.catalog.attributes.list.before') !!}

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.catalog.attributes.index') }}"></datagrid-plus>
        </div>

        {!! view_render_event('bagisto.admin.catalog.attributes.list.after') !!}
    </div>
@stop
