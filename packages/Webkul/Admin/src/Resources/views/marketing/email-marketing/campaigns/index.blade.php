@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.marketing.campaigns.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.marketing.campaigns.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.campaigns.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.marketing.campaigns.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.campaigns.index') }}"></datagrid-plus>
        </div>
    </div>
@stop
