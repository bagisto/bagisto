@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.marketing.sitemaps.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.marketing.sitemaps.title') }}</h1>
            </div>

            <div class="page-action">
                @if (bouncer()->hasPermission('marketing.sitemaps.create'))
                    <a href="{{ route('admin.sitemaps.create') }}" class="btn btn-lg btn-primary">
                        {{ __('admin::app.marketing.sitemaps.add-title') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.sitemaps.index') }}"></datagrid-plus>
        </div>
    </div>
@endsection
